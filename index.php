<?php
include_once 'includes/db_connect.inc.php';
include_once 'includes/functions.inc.php';
$matricula = $_POST['matricula'];
$email = $_POST['email'];
if (verificaValidadeCampo($matricula) && verificaValidadeCampo($email)) {
    //Consulta matrícula no BD
    $aluno = verificaUsuario($matricula, $email, $mysqli);
    if ($aluno != null) {
        session_start();
        $_SESSION['NOME_ALUNO'] = $aluno['nome_aluno'];
        $_SESSION['ID_ALUNO'] = $aluno['id_aluno'];

        header('Location: questionario.php');
         
    } else {
        echo '<script> alert("Dados não encontrado, tente novamente.");</script>';
    }
}
?>

<!DOCTYPE html>
<html style="height: 100%;">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Questionário de Estilo de Aprendizagem</title>
        <style type="text/css">
        	.copyright { min-height:40px; background-color:#000000;}
 			.copyright p {color:#FFF; padding:10px 0; margin-bottom:0px;}
        </style>
    </head>
    <body style="background-color:#f6f6f6; position: relative; margin: 0;min-height:100%;">
        <div class="container text-center" style="background-color:#fff; box-shadow: 0 2px 4px rgba(0,0,0,.15);padding-bottom: 40px; margin-top: 40px; width:600px">
            <h3 style="font-size: 23px">Digite os dados para acesso ao seu questionário</h3>
            <hr style="width: 500px; border: 0; border-top: 2px solid #204d74;"/>
            <div style="width: 300px; margin-top: 20px; margin: auto" class="text-left">
                <form action="" method="post" name="login_form">
                    <div class="form-group">
                        <label for="matricula">Matrícula</label>
                        <input type="text" name="matricula" id="matricula" placeholder="Informe sua matrícula" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" placeholder="Informe seu e-mail" class="form-control">
                    </div>
                    <div class="text-right" style="margin-top: 20px;">
                        <input type="submit" value="Entrar" class="btn btn-primary"/>
                    </div>
                </form>
            </div>
            <div id="instrucoes" class="text-left" style="width: 500px; margin: auto; margin-top: 20px; padding: 12px;height: auto; text-align: justify; background-color: #e6f2ff";>
                <p><font color="red">Instruções:</font> Ao acessar o sistema você encontrará um questionário relacionado a conceitos que serão tratados no curso de Introdução à Computação.
                O objetivo deste questionário é entender melhor o conhecimento prévio dos alunos com relação aos aspectos do curso, possibilitanto a oferta de uma melhor seleção de materiais e conteúdo. É importante que as respostas de fato representem o seu conhecimento, ou seja, caso você não tenha nenhum conhecimento sobre o assunto em questão você não precisa escolher nenhuma das alternativas, bastando apenas não selecionar nenhuma das respostas.</p>
                <p style="margin-top: -10px">Não é necessário responder o questionário em um único momento, suas respostas estão sendo gravadas no decorrer do seu progresso no sistema, porém é válido se atentar à data limite, pois neste momento o sistema será retirado do ar. Você pode acompanhar seu progresso a partir das cores que são adicionadas a cada uma das questões: <font color="grey">cinza</font> indica que aquele conceito ainda precisa ser respondido, <font color="green"> verde</font> indica que aquele conceito já foi respondido e <font color="#990"> amarelo</font> indica o conceito atual. Ao final, espera-se que todos os conceitos estejam <font color="green"> verde</font>, mesmo em conceitos que você não marcou nenhuma das alternativas.</p>
                <p style="margin-top: -10px"><font color="red">Importante:</font> As respostas de cada conceito são salvas apenas quando você aperta o botão <input type="button" value="Salvar" class="btn btn-success"/> na página de Perguntas. Portanto, não basta apenas informar seu conhecimento prévio, é necessário enviar para aquele conceito ficar da cor verde.</p>
            </div>
        </div>
    </body>
    <?php
        include_once 'footer.php';
    ?>
</html>
