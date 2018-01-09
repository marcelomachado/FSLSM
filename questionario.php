<?php
include_once 'includes/db_connect.inc.php';
include_once 'includes/functions.inc.php';

session_start();
if (isset($_SESSION['ID_ALUNO'])) {
    $nome_aluno = $_SESSION['NOME_ALUNO'];
    $id_aluno = $_SESSION['ID_ALUNO'];

    $questionarioJSONFile = fopen("questionario.json", "r") or die("Não foi possível encontrar o arquivo!");
    $questionarioJSON = fread($questionarioJSONFile, filesize("questionario.json"));
    $questionarioArray = json_decode($questionarioJSON, true);


}else {
    header('Location: index.php');
}

if($stmt = $mysqli->prepare("SELECT * FROM `introducao_a_computacao`.`estilo_de_aprendizagem` WHERE id_aluno = ?")){
    $stmt->bind_param('i', $id_aluno);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1) {
        header('Location: resultado.php?respondido=1');
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
        
      
        <style type="text/css">        
            .copyright { min-height:40px; background-color:#000000;}
            .copyright p {color:#FFF; padding:10px 0; margin-bottom:0px;}           
        </style>
        <script type="text/javascript">
            $(function () {                
                $('#myModal').on('shown.bs.modal', function () {
                  $('#myInput').focus();
                });

                $("#sair").click(function () {
                    window.location.replace("logout.php");
                });


            });


        </script>
    </head>
    <body style="background-color:#f6f6f6; font-size: 140% !important; position: relative; margin: 0;min-height:100%;">
        <!-- Modal -->
         
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel"><font color="red">Instruções:</font><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button></h2>
                
              </div>
              <div class="modal-body">
                <div id="instrucoes" class="text-left" style="width: 500px; margin: auto; margin-top: 20px; padding: 12px;height: auto; text-align: justify; background-color: #e6f2ff";>
                    <p>Responda todas as questões do forumulaŕio e pressione o botão de Salvar. Em seguida você terá acesso ao seu resultado.</p>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              </div>
            </div>
          </div>
        </div>
        <div class="container text-center" style="max-width:750px; margin-top: 40px; background-color: #fff; padding-bottom: 40px; box-shadow: 0 2px 4px rgba(0,0,0,.15)">
            <div id="boas-vindas" class="text-right">
                <h5>Olá <strong><?php echo $nome_aluno; ?></strong></h5>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Instruções</button>
                <input type="button" id="sair" class="btn btn-danger" value="Sair"/>            
            </div>

            <div id="perguntas">
                <br>
                <h4>Escolha "a" ou "b" para indicar sua resposta a cada uma das questões. Se as duas alternativas "a" e "b" se aplicam a você, escolha aquela que é mais frequente. </h4>                           
                    <form id="questionario" method="post" action="resultado.php"  onsubmit="return validatePage($('.question'))">
                    <!--<form id="questionario" method="post" action="resultado.php">-->
                    <?php                    
                        $questoes = $questionarioArray["estilodeaprendizagem"]["questoes"];
                        echo '<div class="text-left">';
                        echo '<ul style="list-style-type:none">';
                        for($i=0;$i<count($questoes);$i++){
                            $id_questão = $i + 1;
                            echo $id_questão.') <font color="red">*</font>'.$questoes[$i]['enunciado'].'<br>';
                            
                            echo '<li class="question">';
                            echo '<div class="radio">';
                            echo '<label><input type="radio" value="a" name="questao'.$id_questão.'-'.$questoes[$i]['estilo'].'"/> a) '.$questoes[$i]['a'].'<br></label>';
                            echo '</div>';
                            echo '<div class="radio">';
                            echo '<label><input type="radio" value="b" name="questao'.$id_questão.'-'.$questoes[$i]['estilo'].'"/> b) '.$questoes[$i]['b'].'<br><br></label>';
                            echo '</div>';
                            echo '</li>';
                               
                        }
                        echo "<hr>";
                        echo '<font color="red">*</font> Indique o tempo você pretende/pode se dedicar ao curso de Introdução à Computação por semana:<br>';
                        echo '<li class="question">';
                        echo '<div class="radio">';
                        echo '<label><input type="radio" value="a" name="questaotempo"/> a) Até 1 hora<br></label>';
                        echo '</div>';
                        echo '<div class="radio">';
                        echo '<label><input type="radio" value="b" name="questaotempo"/> b) Entre 1 hora e 2 horas<br></label>';
                        echo '</div>';
                        echo '<div class="radio">';
                        echo '<label><input type="radio" value="c" name="questaotempo"/> c) Entre 2 hora e 4 horas<br></label>';
                        echo '</div>';
                        echo '<div class="radio">';
                        echo '<label><input type="radio" value="d" name="questaotempo"/> d) Entre 4 hora e 6 horas<br></label>';
                        echo '</div>';
                        
                        echo '<div class="radio">';
                        echo '<label><input type="radio" value="e" name="questaotempo"/> e) Mais de 6 horas<br></label>';
                        echo '</div>';
                        echo '</li>';
                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';              
                    ?>
                    <div class="text-center">
                        <input id="proxima" type="submit" value="Salvar" class="btn btn-success"/>
                    </div>                
            </div>
            <?php fclose($questionarioJSONFile); ?>
        </div>
        <script src="js/validation.js"></script>
    </body>
    <?php
        include_once 'footer.php';
    ?>
</html>