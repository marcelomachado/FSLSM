<?php
	include_once 'includes/db_connect.inc.php';
	session_start();
	if (isset($_SESSION['ID_ALUNO'])) {
		$id_aluno = $_SESSION['ID_ALUNO'];
		$nome_aluno = $_SESSION['NOME_ALUNO'];
	}else {
	    header('Location: index.php');
	}

	$resposta = $_POST;

	$atvref = 0;
	$semint = 0;
	$visver = 0;
	$seqglo = 0;	

	while ($alternativa_escolhida = current($resposta)) {
		$estilo = explode("-",key($resposta))[1];

		switch($estilo){
			case "1":
				if($alternativa_escolhida == "a"){
					$atvref++;
				}
				else{
					$atvref--;
				}
				break;
			case "2":
				if($alternativa_escolhida == "a"){
					$semint++;
				}
				else{
					$semint--;
				}
				break;
			case "3":
				if($alternativa_escolhida == "a"){
					$visver++;
				}
				else{
					$visver--;
				}
				break;
			case "4":
				if($alternativa_escolhida == "a"){
					$seqglo++;
				}
				else{
					$seqglo--;
				}
				break;
		}    
	    next($resposta);
	}

	if ($stmt = $mysqli->prepare("SELECT * FROM `introducao_a_computacao`.`estilo_de_aprendizagem` WHERE id_aluno = ?")) {
        $stmt->bind_param('i', $id_aluno);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
			if ($stmt = $mysqli->prepare("UPDATE `introducao_a_computacao`.`estilo_de_aprendizagem` SET atiref = ?, semint = ?, visver = ?, seqglo = ? WHERE `id_aluno` = ?")) {
                $stmt->bind_param("iiiii",$atvref,$semint,$visver,$seqglo,$id_aluno);
                $stmt->execute();
            }

		}else{
			if ($stmt = $mysqli->prepare("INSERT INTO `introducao_a_computacao`.`estilo_de_aprendizagem` (`id_aluno`, `atiref`, `semint`, `visver`, `seqglo`) VALUES (?,?,?,?,?)")) {
		        $stmt->bind_param("iiiii", $id_aluno,$atvref,$semint,$visver,$seqglo);
		        $stmt->execute();
		    }
		}
	}

	

?>

<!DOCTYPE html>
<html style="height: 100%;">
    <head>
        <meta charset="UTF-8">

        <title>Questionário de Estilo de Aprendizagem</title>

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-slider.min.css">

        <script type="text/JavaScript" src="js/jquery-3.2.1.min.js"></script>
        <script type="text/JavaScript" src="js/bootstrap.min.js"></script>
        <script type="text/JavaScript" src="js/bootstrap-slider.min.js"></script>
    </head>
     <body style="background-color:#f6f6f6; font-size: 140% !important; position: relative; margin: 0;min-height:100%;">
     	<div class="container text-center" style="max-width:750px; margin-top: 40px; background-color: #fff; padding-bottom: 40px; box-shadow: 0 2px 4px rgba(0,0,0,.15)">
     		<div id="boas-vindas" class="text-right">
                <h5>Olá <strong><?php echo $nome_aluno; ?></strong></h5>
                <input type="button" id="sair" class="btn btn-danger" value="Sair"/>            
            </div>
     	</div>     	
    </body>    
</html>