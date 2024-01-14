<link rel="stylesheet" href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/css/w3.css'?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/css/edit-markup.css'?>" rel='stylesheet'>
    <style>
    /* MENU */
    .menu {
      display: flex;
      overflow: hidden;
      justify-content: space-evenly;
      height: auto;
      padding-left: 140px;
      padding-right: 140px;
      background: rgba(255, 255, 255, 0.08);
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(6.2px);
      align-items: center;
      text-align: center;
      }

      .menu a {
      padding: 10px;
      text-decoration: none;
      font-size: 14px;
      color: white;
      transition: background-color 0.3s ease;
      }

      .menu a:hover {
      background: #02a146e8;
      color: #fff!important;
      -webkit-transition: 0.5s;
      scale: 1;
      border-color: none;
      box-shadow: 0 0px 30px;
      }

      @media screen and (max-width: 600px) {
      .menu {
      flex-direction: column;
      }
      }
</style>
<div class="menu">
            <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/index.php'?>" title="Página inicial"><i class="fa fa-home fa-2x" aria-hidden="true"></i><br>Página inicial</a>
            <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/pdf-para-tiff/historico.php'?>" title="Controle de Conversões"><i class="fa fa-check-square-o fa-2x" aria-hidden="true"></i><br>Controle de Conversões</a>
            <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/pdf-para-tiff/historico-faltante.php'?>" title="Relatório de Conversão"><i class="fa fa-bar-chart fa-2x" aria-hidden="true"></i><br>Relatório</a>
            <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/chancela/index.php'?>" title="Adicionar Sinal Público"><i class="fa fa-pencil-square fa-2x" aria-hidden="true"></i><br>Adicionar Sinal Público</a> 
            <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/indicador-pessoal/index.php'?>" title="Logs do Indicador Pessoal"><i class="fa fa-clock-o fa-2x" aria-hidden="true"></i><br>Logs do Indicador Pessoal</a>
            <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/carimbo-digital/index.php'?>" title="Carimbo Digital"><i class="fa fa-edit fa-2x" aria-hidden="true"></i><br>Carimbo Digital</a>
            <a href="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/docmark/pdf-para-tiff/index.php'?>" title="Converter PDF para TIFF"><i class="fa fa-image fa-2x" aria-hidden="true"></i><br>Converter PDF para TIFF</a>
</div>