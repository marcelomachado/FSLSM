<?php

	if(isset($_POST['matricula']) && $_POST['matricula'] != ""){
		$matricula = $_POST['matricula'];
		//Consulta matrícula no BD
		echo 'alert("'.$_POST['matricula'].'");';
		header('Location: ../questionario.php');
	}
	else{
		echo 'teste';	
		header('Location: ../index.php');
	}

?>
