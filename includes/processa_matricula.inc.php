<?php
	include_once 'db_connect.inc.php';
	include_once 'functions.inc.php';
	session_start();

	if(isset($_POST['matricula']) && $_POST['matricula'] != ""){
		$matricula = $_POST['matricula'];
		//Consulta matrícula no BD
		$nome_aluno = verificaUsuario($matricula,$mysqli);
		if($nome_aluno!=null){
			$_SESSION['NOME_ALUNO'] = $nome_aluno;
			header('Location: ../questionario.php');
			exit();
		}

		echo '<script> alert("'.$_POST['matricula'].' não encontrada, tente novamente.");</script>';
		
	}
	//header('Location: ../index.php');

