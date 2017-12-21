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
                    <p>Ao acessar o sistema você encontrará um questionário relacionado a conceitos que serão tratados no curso de Introdução à Computação.
                    O objetivo deste questionário é entender melhor o conhecimento prévio dos alunos com relação aos aspectos do curso, possibilitanto a oferta de uma melhor seleção de materiais e conteúdo. É importante que as respostas de fato representem o seu conhecimento, ou seja, caso você não tenha nenhum conhecimento sobre o assunto em questão você não precisa escolher nenhuma das alternativas, bastando apenas não selecionar nenhuma das respostas.</p>
                    <p style="margin-top: -10px">Não é necessário responder o questionário em um único momento, suas respostas estão sendo gravadas no decorrer do seu progresso no sistema, porém é válido se atentar à data limite, pois neste momento o sistema será retirado do ar. Você pode acompanhar seu progresso a partir das cores que são adicionadas a cada uma das questões: <font color="grey">cinza</font> indica que aquele conceito ainda precisa ser respondido, <font color="green"> verde</font> indica que aquele conceito já foi respondido e <font color="#990"> amarelo</font> indica o conceito atual. Ao final, espera-se que todos os conceitos estejam <font color="green"> verde</font>, mesmo em conceitos que você não marcou nenhuma das alternativas.</p>
                    <p style="margin-top: -10px"><font color="red">Importante:</font> As respostas de cada conceito são salvas apenas quando você aperta o botão <input type="button" value="Salvar" class="btn btn-success"/> na página de Perguntas. Portanto, não basta apenas informar seu conhecimento prévio, é necessário enviar para aquele conceito ficar da cor verde.</p>
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
                    <!--<form id="questionario" method="post" action="resultado.php"  onsubmit="return validatePage($('.question'))">-->
                        <form id="questionario" method="post" action="resultado.php">
                        <?php                    
                            $questoes = $questionarioArray["estilodeaprendizagem"]["questoes"];
                            echo '<div class="text-left">';
                            echo '<ul style="list-style-type:none">';
                            for($i=0;$i<count($questoes);$i++){
                                $id_questão = $i + 1;
                                echo $id_questão.') <font color="red">*</font>'.$questoes[$i]['enunciado'].'<br>';
                                
                                echo '<li class="question">';
                                echo '<div class="radio"><label>';
                                echo '<input type="radio" value="a" name="questao'.$id_questão.'-'.$questoes[$i]['estilo'].'"/> a) '.$questoes[$i]['a'].'<br>';
                                echo '</label></div>';
                                echo '<div class="radio"><label>';
                                echo '<input type="radio" value="b" name="questao'.$id_questão.'-'.$questoes[$i]['estilo'].'"/> b) '.$questoes[$i]['b'].'<br><br>';
                                echo '</label></div>';
                                echo '</li>';
                                   
                            }
                            echo '</ul>';
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