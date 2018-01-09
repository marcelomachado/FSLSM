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
        $id_aluno = $aluno['id_aluno'];
        if($stmt = $mysqli->prepare("SELECT * FROM `introducao_a_computacao`.`estilo_de_aprendizagem` WHERE id_aluno = ?")){
        	$stmt->bind_param('i', $id_aluno);
	        $stmt->execute();
	        $stmt->store_result();
	        if ($stmt->num_rows == 1) {
	        	header('Location: resultado.php?respondido=1');
	    	}
	    	else{       
		        header('Location: questionario.php');
		    }
        } 
         
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
                     <p>O índice de Estilos de Aprendizagem é um instrumento utilizado para determinar as preferências nas quatro dimensões (ativo/reflexivo, sensorial/intuitivo, visual/verbal e sequência/global) do modelo formulado por Richard Felder e Linda K. Silverman. A partir do conhecimento do estilo de aprendizagem é possível oferecer conteúdos educacionais mais acertivos a cada aluno</p>
            </div>
        </div>
    </body>
    <?php
        include_once 'footer.php';
    ?>
</html>
