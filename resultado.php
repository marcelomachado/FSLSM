<?php
	include_once 'includes/db_connect.inc.php';
	session_start();
	if (isset($_SESSION['ID_ALUNO'])) {
		$id_aluno = $_SESSION['ID_ALUNO'];
		$nome_aluno = $_SESSION['NOME_ALUNO'];

	}else {
	    header('Location: index.php');
	}
	// if(!isset($_POST) || empty($_POST)){
	// 	 header('Location: questionario.php');
	// }
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

        <script type="text/JavaScript" src="js/jquery-3.2.1.min.js"></script>
        <script type="text/JavaScript" src="js/bootstrap.min.js"></script>

        <style type="text/css">
        	.copyright { min-height:40px; background-color:#000000;}
 			.copyright p {color:#FFF; padding:10px 0; margin-bottom:0px;}
        </style>
    </head>
     <body style="background-color:#f6f6f6; font-size: 140% !important; position: relative; margin: 0;min-height:100%;">
     	<div class="container text-center" style="max-width:750px; margin-top: 40px; background-color: #fff; padding-bottom: 40px; box-shadow: 0 2px 4px rgba(0,0,0,.15)">
     		<div id="boas-vindas" class="text-right">
                <h5>Olá <strong><?php echo $nome_aluno; ?></strong></h5>
                <input type="button" id="sair" class="btn btn-danger" value="Sair"/>            
            </div>
            <div id="resultado">
            	<h3>Seu resultado:</h3>
            	<?php 
            		echo "Ativo/Reflexivo:".$atvref."<br>";
            		echo "Sensorial/Intuitivo:".$semint."<br>";
            		echo "Visual/Verbal:".$visver."<br>";
            		echo "Sequencial/Global:".$seqglo."<br>";
            	?>
            
            	<div>
            		<h4 style="text-align: center; margin-top: 30px;">Análise do resultado:</h4> 
        			<ul style="text-align: left; text-align: justify; padding-right: 40px">
        				<li>
        					<strong>Processamento da informação:</strong>
        					dimensão ativa – onde os estudantes discutem, aplicam conceitos e trabalham em grupos;
							dimensão reflexiva – os estudantes precisam refletir e preferem trabalhos individuais; 
        				</li>
        				<li>
        					<strong>Percepção da informação:</strong>
    						dimensão sensorial – em que os estudantes aprendem fatos, resolvem problemas e são detalhistas; 
    						dimensão intuitiva – os estudantes descobrem possibilidades e relações, lidam com novos conceitos e abstrações e são inovadores; 
        				</li>
        				<li>
        					<strong>Retenção da informação:</strong>
    						dimensão visual – onde os estudantes lembram-se do que veem; 
							dimensão verbal – os estudantes aproveitam as explicações orais ou escritas;
        				</li>            				
        				<li>
							<strong>Organização da informação:</strong>						dimensão sequencial – os estudantes aprendem deforma linear e em etapas sequenciais; 
							dimensão global – os estudantes aprendem de forma aleatória formando uma visão do todo e resolvem problemas complexos.
        				</li>        			
            		</ul>
            	</div>
            	<table align='center' border='1' width="90%" style="margin-bottom: 30px; margin-top: 30px">
            		<tr>
            			<td class="text-left">Ativo</td>
            			<td>-11</td>
            			<td>-9</td>
            			<td>-7</td>
            			<td>-5</td>
            			<td>-3</td>
            			<td>-1</td>
            			<td>+1</td>
            			<td>+3</td>
            			<td>+5</td>
            			<td>+7</td>
            			<td>+9</td>
            			<td>+11</td>
            			<td class="text-right">Reflexivo</td>
            		</tr>
            		<tr>
            			<td class="text-left">Sensorial</td>
            			<td>-11</td>
            			<td>-9</td>
            			<td>-7</td>
            			<td>-5</td>
            			<td>-3</td>
            			<td>-1</td>
            			<td>+1</td>
            			<td>+3</td>
            			<td>+5</td>
            			<td>+7</td>
            			<td>+9</td>
            			<td>+11</td>
            			<td class="text-right">Intuitivo</td>
            		</tr>
            		<tr>
            			<td class="text-left">Visual</td>
            			<td>-11</td>
            			<td>-9</td>
            			<td>-7</td>
            			<td>-5</td>
            			<td>-3</td>
            			<td>-1</td>
            			<td>+1</td>
            			<td>+3</td>
            			<td>+5</td>
            			<td>+7</td>
            			<td>+9</td>
            			<td>+11</td>
            			<td class="text-right">Verbal</td>
            		</tr>
            		<tr>
            			<td class="text-left">Sequencial</td>
            			<td>-11</td>
            			<td>-9</td>
            			<td>-7</td>
            			<td>-5</td>
            			<td>-3</td>
            			<td>-1</td>
            			<td>+1</td>
            			<td>+3</td>
            			<td>+5</td>
            			<td>+7</td>
            			<td>+9</td>
            			<td>+11</td>
            			<td class="text-right">Global</td>
            		</tr>
            	</table> 
            	          	
            	<ul class="text-left" style="text-align: justify; padding-right: 45px; padding-left: 40px;">

            		<li>Se sua pontuação na escala está entre -3 e +3 : você está claramente bem equilibrado(a) quanto às duas dimensões da escala. </li>
            		<li>Se sua pontuação na escala está entre -7 e -5 ou está entre +5 e +7: você tem uma preferência moderada por uma das dimensões da escala. </li>
            		<li>Se sua pontuação na escala está entre -11 e -9 ou está entre +9 e +11: você tem uma forte preferência por uma das dimensões da escala.</li>
            	</ul>			
            </div>

     	</div>     	
    </body>
    <?php include_once 'footer.php';?>
</html>