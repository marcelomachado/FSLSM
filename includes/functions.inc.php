<?php
 
function sec_session_start() {
    $session_name = 'sec_session_id';   // Estabeleça um nome personalizado para a sessão
    $secure = SECURE;
    // Isso impede que o JavaScript possa acessar a identificação da sessão.
    $httponly = true;
    // Força a sessão a usar apenas cookies. 
   if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    
    // Obtém params de cookies atualizados.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params(time()+60, $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    // Estabelece o nome fornecido acima como o nome da sessão.
    session_name($session_name);
    
    session_start();            // Inicia a sessão PHP 
    $inactive = 1200; //Sess�o exipira em 20 min 
    if(isset($_SESSION['timeout']) ) {
        $session_life = time() - $_SESSION['timeout'];
        if($session_life > $inactive)
        { 
            session_destroy();             
            header("Location: ../SistemaAdmSouzaNovais/login.php"); 

        }
    }
    $_SESSION['timeout'] = time();
    
    
    session_regenerate_id();    // Recupera a sessão e deleta a anterior. 
}

function login($nome_de_usuario, $senha, $mysqli) {
    
    // Usando definições pré-estabelecidas significa que a injeção de SQL (um tipo de ataque) não é possível. 
    if ($stmt = $mysqli->prepare("SELECT id_pessoa, nome_de_usuario, senha, salt 
        FROM sn_usuario
        WHERE nome_de_usuario = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $nome_de_usuario);  // Relaciona  "$nome_de_usuario" ao parâmetro.
        $stmt->execute();    // Executa a tarefa estabelecida.
        $stmt->store_result();
 
        // obtém variáveis a partir dos resultados. 
        $stmt->bind_result($id_pessoa, $nome_de_usuario, $db_senha, $salt);
        $stmt->fetch();
 
        // faz o hash da senha com um salt excusivo.
        $senha = hash('sha512', $senha . $salt);
        if ($stmt->num_rows == 1) {
            // Caso o usuário exista, confer se a conta está bloqueada
            // devido ao limite de tentativas de login ter sido ultrapassado 
 
            if (checkbrute($id_pessoa, $mysqli)) {
                // A conta está bloqueada 
                // Envia um email ao usuário informando que a conta está bloqueada
               
                return false;
            }
            
            // Verifica se a senha confere com o que consta no banco de dados
            // a senha do usuário é enviada.
            if ($db_senha == $senha) {
                // A senha está correta!
                // Obtém o string usuário-agente do usuário. 
                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                // proteção XSS conforme imprimimos este valor
                $id_pessoa = preg_replace("/[^0-9]+/", "", $id_pessoa);

                // proteção XSS conforme imprimimos este valor 
                $nome_de_usuario = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $nome_de_usuario);
                $_SESSION['nome_de_usuario'] = $nome_de_usuario;
                $_SESSION['id_pessoa'] = $id_pessoa;
                $_SESSION['login_string'] = hash('sha512', 
                          $senha . $user_browser);
                // Login concluído com sucesso.
                return true;
            } 
            // A senha não está correta
            // Registramos essa tentativa no banco de dados
            $now = time();
            $mysqli->query("INSERT INTO sn_tentativas_login(id_pessoa, tempo)
                            VALUES ('$id_pessoa', '$now')");
            return false;
            
        
        } 
        // Tal usuário não existe.
        return false;
        
    }
}


function login_check($mysqli) {
    
    
    // Verifica se todas as variáveis das sessões foram definidas 
    
    if (isset($_SESSION['id_pessoa'], 
              $_SESSION['nome_de_usuario'], 
              $_SESSION['login_string'])) {
        
        $id_pessoa = $_SESSION['id_pessoa'];
        $login_string = $_SESSION['login_string'];
        $nome_de_usuario = $_SESSION['nome_de_usuario'];
 
        // Pega a string do usuário.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT senha 
                                      FROM sn_usuario 
                                      WHERE id_pessoa = ? LIMIT 1")) {
            // Atribui "$id_pessoa" ao parâmetro. 
            $stmt->bind_param('i', $id_pessoa);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
             
      
 
            if ($stmt->num_rows == 1) {
                // Caso o usuário exista, pega variáveis a partir do resultado.                 
                $stmt->bind_result($senha);
                $stmt->fetch();
                $login_check = hash('sha512', $senha . $user_browser);
 
                if ($login_check == $login_string) {
                    // Logado!!!
                    return true;
                } else {
                    // Não foi logado 
                    return false;
                }
            } else {
                // Não foi logado 
                return false;
            }
        } else {
            // Não foi logado 
            return false;
        }
    } else {
        // Não foi logado 
        return false;
    }
}

function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // Estamos interessados somente em links relacionados provenientes de $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function checkbrute($id_pessoa, $mysqli) {
    // Registra a hora atual 
    $now = time();
 
    // Todas as tentativas de login são contadas dentro do intervalo das últimas 2 horas. 
    $valid_attempts = $now - (2 * 60 * 60);
    
 
    if ($stmt = $mysqli->prepare("SELECT tempo 
                            FROM sn_tentativas_login
                            WHERE id_pessoa = ? 
                            AND tempo > '$valid_attempts'")) {
        $stmt->bind_param('i', $id_pessoa);
 
        // Executa a tarefa pré-estabelecida. 
        $stmt->execute();
        $stmt->store_result();
 
        // Se houve mais do que 5 tentativas fracassadas de login         
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function verificaValidadeCampo($campo){
	if(isset($campo) && $campo != ""){
		return true;	
	}
	return false;
}
function verificaUsuario($matricula, $email, $mysqli){

    if(verificaValidadeCampo($matricula) && verificaValidadeCampo($email)){
         if ($stmt = $mysqli->prepare("SELECT id,nome
                                      FROM aluno
                                      WHERE matricula = ? AND email = ? LIMIT 1")) {
            // Atribui "$id_pessoa" ao parâmetro. 
            $stmt->bind_param('ss', $matricula, $email);	

            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                // Caso o usuário exista, pega variáveis a partir do resultado.                 
                $stmt->bind_result($id_aluno, $nome);
                $stmt->fetch();
                return array('id_aluno'=>$id_aluno, 'nome_aluno'=>$nome);
            }
            return null;

        }
    }
}
