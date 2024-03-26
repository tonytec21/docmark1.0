<?php
// Inclua a função verificar_sessao_ativa()
require_once 'funcoes.php';
// Verifique se a sessão está ativa
verificar_sessao_ativa();
?>
<!DOCTYPE html>
<html>
<head>
    <title>DocMark</title>
    <link rel="icon" href="img/logo.png" type="image/png">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        #form {
          display: flex;
          flex-direction: row-reverse;
        }
    </style>
  </head>
<body>
  <div class="header">
      <form id="form" action="logout.php" method="post">
          <input type="submit" class="third" value="Sair">
      </form>
      <div class="inner-header flex">
        <div class="orb"></div>
        <h1>DocMark</h1>
      </div>

  <?php include_once("menu.php");?><br>
        <div class='main-content-container'>
          <a class='container' style="text-decoration: none" href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/carimbo-digital/index.php'?>">
            <i class="fa fa-edit fa-2x" style="color: #fff" aria-hidden="true"></i><p style="color: #fff" class='image-caption'>Carimbo Digital</p>
          </a>
          <a class='container' style="text-decoration: none" href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/pdf-para-tiff/index.php'?>">
           <i class="fa fa-image fa-2x" style="color: #fff" aria-hidden="true"></i><p style="color: #fff" class='image-caption'>Converter PDF para TIFF</p>
          </a>
          <a class='container' style="text-decoration: none" href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/pdf-para-tiff/historico.php'?>">
            <i class="fa fa-check-square-o fa-2x" style="color: #fff" aria-hidden="true"></i><p style="color: #fff" class='image-caption'>Controle de Conversões</p>
          </a>
          <a class='container' style="text-decoration: none" href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/indicador-pessoal/index.php'?>">
            <i class="fa fa-clock-o fa-2x" style="color: #fff" aria-hidden="true"></i><p style="color: #fff" class='image-caption'>Logs do Indicador Pessoal</p>
          </a>
          <a class='container' style="text-decoration: none" href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/chancela/index.php'?>">
            <i class="fa fa-pencil-square fa-2x" style="color: #fff" aria-hidden="true"></i><p style="color: #fff" class='image-caption'>Adicionar Sinal Público</p>
          </a>
          <a class='container' style="text-decoration: none" href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/indicador-pessoal/matriculas.php'?>">
            <i class="fa fa-user-plus fa-2x" style="color: #fff" aria-hidden="true"></i><p style="color: #fff" class='image-caption'>Cadastrar Indicador Pessoal</p>
          </a>
        </div>
  </div>
  <?php include_once("rodape.php");?>
    <style>
            h1 {
              font-family: "Segoe UI",Arial,sans-serif;
              font-weight:300;
              letter-spacing: 2px;
              font-size:48px;
              margin-top: 4%;
            }

            p {
              font-family: "Segoe UI",Arial,sans-serif;
              letter-spacing: 1px;
              font-size:14px;
              color: #333333;
            }

            .inner-header {
              height: auto;
              width: 100%;
              margin-top: 60px;
              padding: 0;
            }

            .waves {
              position:relative;
              width: 100%;
              height:15vh;
              margin-bottom:-7px; 
              min-height:100px;
              max-height:150px;
            }

            /* Animação */

            .parallax > use {
              animation: move-forever 25s cubic-bezier(.55,.5,.45,.5)     infinite;
            }
            .parallax > use:nth-child(1) {
              animation-delay: -2s;
              animation-duration: 7s;
            }
            .parallax > use:nth-child(2) {
              animation-delay: -3s;
              animation-duration: 10s;
            }
            .parallax > use:nth-child(3) {
              animation-delay: -4s;
              animation-duration: 13s;
            }
            .parallax > use:nth-child(4) {
              animation-delay: -5s;
              animation-duration: 20s;
            }
            @keyframes move-forever {
              0% {
              transform: translate3d(-90px,0,0);
              }
              100% { 
                transform: translate3d(85px,0,0);
              }
            }

            @media (max-width: 768px) {
              .waves {
                height:40px;
                min-height:40px;
              }
              .content {
                height:30vh;
              }
              h1 {
                font-size:24px;
              }
            }

            .orb {
                left: 0px;
                top: -1px;
            }

            @import "bourbon";

            .main-content-container {
              max-width: 940px;
              margin: 0 auto;
              padding: 30px;
              background: none;
              display: flex;
              flex-flow: row wrap;
              justify-content: center;
            }

            .container {
              height: 80px;
              margin: 10px 1em;
              max-width: 100%;
              padding: 0px!important;
              width: 40%!important;
            }

            .container:nth-child(3n+1):hover, .container:nth-child(3n-1):hover, .container:nth-child(3n):hover {
              background: #383641;
              -webkit-transition: 0.5s;
              scale: 1;
              border-color: aliceblue;
              box-shadow: 0 0px 30px;
            }

            .container:nth-child(3n+1) {
              background: linear-gradient(60deg, rgb(0, 0, 0) 0%, rgb(0, 172, 193) 100%);
              -webkit-transition: 0.5s;
            }

            .container:nth-child(3n-1) {
              background: linear-gradient(60deg, rgb(0, 0, 0) 0%, rgb(0, 172, 193) 100%);
              -webkit-transition: 0.5s;
            }

            .container:nth-child(3n) {
              background: linear-gradient(60deg, rgb(0, 0, 0) 0%, rgb(0, 172, 193) 100%);
              -webkit-transition: 0.5s;
            }

            @media (max-width: 500px) {
              .main-content-container {
                padding: 8px;
              }
              .container {
                flex: 0 1 15%;
              }
            }

            .image-caption {
              background: none;
              margin: 0;
              text-align: center;
              border-bottom-left-radius: 4px;
              border-bottom-right-radius: 4px;
            }
      </style>
  </body>
</html
