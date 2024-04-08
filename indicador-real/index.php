<?php
// Inclua a função verificar_sessao_ativa()
require_once 'funcoes.php';

// Verifique se a sessão está ativa
verificar_sessao_ativa();

$files = glob('indicador/*.json');

function formatarDocumento($documento) {
    if (strlen($documento) === 11) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $documento);
    } elseif (strlen($documento) === 14) {
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $documento);
    }
    return $documento;
}


$pastaHistorico = __DIR__ . '/indicador';
$arquivos = glob($pastaHistorico . '/*');

if (!empty($arquivos)) { // Verifica se a pasta contém arquivos
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

    // Obtém os números dos nomes dos arquivos
    $numerosArquivos = array();
    foreach ($arquivos as $arquivo) {
        $nomeArquivo = basename($arquivo);
        $numeroArquivo = (int) substr($nomeArquivo, strpos($nomeArquivo, '_') + 1);
        $numerosArquivos[] = $numeroArquivo;
    }

    // Obtém o intervalo de números
    $minimo = 1;
    $maximo = max($numerosArquivos);

    // Obtém os números faltantes
    $numerosFaltantes = array();
    for ($i = $minimo; $i <= $maximo; $i++) {
        if (!in_array($i, $numerosArquivos)) {
            $numerosFaltantes[] = str_pad($i, 8, '0', STR_PAD_LEFT);
        }
    }
}




?>

<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Indicador Real</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/chart.js"></script>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/pop-up.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/chart.js"></script>
    <script src="js/chartjs-plugin-datalabels.js"></script>
    <script type="text/javascript" charset="utf8" src="js/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="js/jquery.dataTables2.js"></script>
<style>
    form {
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        align-items: center;
    }
    #sincronizar2{
        margin-left: 60%;
        margin-top: -7%;
    }
    /* Estilos para o modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0 0 0 / 59%);

}

.modal-content {
    margin: 10px auto 50px;
    padding: 20px;
    border-radius: 50px;
    background: 0 4px 30px rgba(0, 0, 0, 0.1);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(6.2px);
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

input[type="text"]{
    color: #000;
    padding-left: 10px;
    padding-right: 10px;
    border-radius: 5px;
    padding-top: 2px;
    padding-bottom: 2px;
}
.close {
    color: #fff;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
#sincronizar3{
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
}
    
    </style>

<style>
        .button3{
            color: #333 !important;
            border-radius: 5px;
            box-sizing: border-box;
            min-width: 1.5em;
            padding: 0.5em 0.9em;
            margin-bottom: 30px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            border: none;
            margin-right: 15px;
            background: rgb(255 255 255);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(13.4px);
            -webkit-backdrop-filter: blur(13.4px);
            -webkit-transition: 0.7s;
        }
        .button3:hover{
            background: #02a146e8;
            color: #fff!important;
            -webkit-transition: 0.7s;
            scale: 1;
            border-color: none;
            box-shadow: 0 0px 30px;
        }
        
        
form {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 10px;
    width: 90%;
    text-align: right;
}


label {
    display: block;
    margin-bottom: 5px;
}


input[type="text"],
select {
    width: 100%; 
    padding: 5px;
    box-sizing: border-box; 
}

.message {
    margin-top: 10px;
    font-weight: bold;
    color: green; 
}

.error {
    color: red; 
}
select{
    color: #333;
    border-radius: 5px;
}
/* .btn2 {
    margin-right: -630px!important;
    margin-left: 50px!important;
    margin-top: -13px!important;
    padding: 0.3em 0.0em!important;
} */

        </style>
</head>
<body>


<div class="orb-container">
            <div class="orb"></div>
    </div>
            <h1>DocMark - Indicador Real</h1>
            <?php include_once("../menu.php");?>
            <!-- SINAL PÚBLICO E INDICADOR PESSOAL -->
            <div class="container">
            
    <h2>Cadastrar Indicador Real</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="numero_registro">Número de Registro (*)</label>
    <input type="text" id="numero_registro" name="numero_registro" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Ex.: 123...">
    
    <label for="registro_tipo">Tipo de Registro (*)</label>
    <select id="registro_tipo" name="registro_tipo" required>
        <option value="">Selecione o tipo de registro</option>
        <option value="1">Matrícula</option>
        <option value="2">Matrícula MÃE</option>
        <option value="3">Livro 3-reg-aux</option>
        <option value="4">Transcrição</option>
    </select>
    
    <label for="tipo_imovel">Tipo de Imóvel (*)</label>
    <select id="tipo_imovel" name="tipo_imovel" required>
        <option value="">Selecione o tipo de imóvel</option>
        <option value="1">Casa</option>
        <option value="2">Apartamento</option>
        <option value="3">Loja</option>
        <option value="4">Sala/Conjunto</option>
        <option value="5">Terreno/Fração</option>
        <option value="6">Galpão</option>
        <option value="7">Prédio comercial</option>
        <option value="8">Prédio residencial</option>
        <option value="9">Fazenda/sítio/chácara</option>
        <option value="10">Vaga</option>
        <option value="11">Depósito</option>
    </select>
    
    <label for="localizacao">Localização (*)</label>
    <select id="localizacao" name="localizacao" required>
        <option value="">Selecione a localização</option>
        <option value="0">Urbano</option>
        <option value="1">Rural</option>
    </select>
    
    <label for="uf">UF (*)</label>
    <select id="uf" name="uf" required>
        <option value="21">Maranhão (MA)</option>
    </select>
    
    <label for="cidade">Cidade (*)</label>
    <select id="cidade" name="cidade" required>
        <option value="">Selecione a Cidade</option>
        <option value="2100055">Açailândia</option>
        <option value="2100105">Afonso Cunha</option>
        <option value="2100154">Água Doce do Maranhão</option>
        <option value="2100204">Alcântara</option>
        <option value="2100303">Aldeias Altas</option>
        <option value="2100402">Altamira do Maranhão</option>
        <option value="2100436">Alto Alegre do Maranhão</option>
        <option value="2100477">Alto Alegre do Pindaré</option>
        <option value="2100501">Alto Parnaíba</option>
        <option value="2100550">Amapá do Maranhão</option>
        <option value="2100600">Amarante do Maranhão</option>
        <option value="2100709">Anajatuba</option>
        <option value="2100808">Anapurus</option>
        <option value="2100832">Apicum-Açu</option>
        <option value="2100873">Araguanã</option>
        <option value="2100907">Araioses</option>
        <option value="2100956">Arame</option>
        <option value="2101004">Arari</option>
        <option value="2101103">Axixá</option>
        <option value="2101202">Bacabal</option>
        <option value="2101251">Bacabeira</option>
        <option value="2101301">Bacuri</option>
        <option value="2101350">Bacurituba</option>
        <option value="2101400">Balsas</option>
        <option value="2101509">Barão de Grajaú</option>
        <option value="2101608">Barra do Corda</option>
        <option value="2101707">Barreirinhas</option>
        <option value="2101772">Bela Vista do Maranhão</option>
        <option value="2101731">Belágua</option>
        <option value="2101806">Benedito Leite</option>
        <option value="2101905">Bequimão</option>
        <option value="2101939">Bernardo do Mearim</option>
        <option value="2101970">Boa Vista do Gurupi</option>
        <option value="2102002">Bom Jardim</option>
        <option value="2102036">Bom Jesus das Selvas</option>
        <option value="2102077">Bom Lugar</option>
        <option value="2102101">Brejo</option>
        <option value="2102150">Brejo de Areia</option>
        <option value="2102200">Buriti</option>
        <option value="2102309">Buriti Bravo</option>
        <option value="2102325">Buriticupu</option>
        <option value="2102358">Buritirana</option>
        <option value="2102374">Cachoeira Grande</option>
        <option value="2102408">Cajapió</option>
        <option value="2102507">Cajari</option>
        <option value="2102556">Campestre do Maranhão</option>
        <option value="2102606">Cândido Mendes</option>
        <option value="2102705">Cantanhede</option>
        <option value="2102754">Capinzal do Norte</option>
        <option value="2102804">Carolina</option>
        <option value="2102903">Carutapera</option>
        <option value="2103000">Caxias</option>
        <option value="2103109">Cedral</option>
        <option value="2103125">Central do Maranhão</option>
        <option value="2103158">Centro do Guilherme</option>
        <option value="2103174">Centro Novo do Maranhão</option>
        <option value="2103208">Chapadinha</option>
        <option value="2103257">Cidelândia</option>
        <option value="2103307">Codó</option>
        <option value="2103406">Coelho Neto</option>
        <option value="2103505">Colinas</option>
        <option value="2103554">Conceição do Lago-Açu</option>
        <option value="2103604">Coroatá</option>
        <option value="2103703">Cururupu</option>
        <option value="2103752">Davinópolis</option>
        <option value="2103802">Dom Pedro</option>
        <option value="2103901">Duque Bacelar</option>
        <option value="2104008">Esperantinópolis</option>
        <option value="2104057">Estreito</option>
        <option value="2104073">Feira Nova do Maranhão</option>
        <option value="2104081">Fernando Falcão</option>
        <option value="2104099">Formosa da Serra Negra</option>
        <option value="2104107">Fortaleza dos Nogueiras</option>
        <option value="2104206">Fortuna</option>
        <option value="2104305">Godofredo Viana</option>
        <option value="2104404">Gonçalves Dias</option>
        <option value="2104503">Governador Archer</option>
        <option value="2104552">Governador Edison Lobão</option>
        <option value="2104602">Governador Eugênio Barros</option>
        <option value="2104628">Governador Luiz Rocha</option>
        <option value="2104651">Governador Newton Bello</option>
        <option value="2104677">Governador Nunes Freire</option>
        <option value="2104701">Graça Aranha</option>
        <option value="2104800">Grajaú</option>
        <option value="2104909">Guimarães</option>
        <option value="2105005">Humberto de Campos</option>
        <option value="2105104">Icatu</option>
        <option value="2105153">Igarapé do Meio</option>
        <option value="2105203">Igarapé Grande</option>
        <option value="2105302">Imperatriz</option>
        <option value="2105351">Itaipava do Grajaú</option>
        <option value="2105401">Itapecuru Mirim</option>
        <option value="2105427">Itinga do Maranhão</option>
        <option value="2105450">Jatobá</option>
        <option value="2105476">Jenipapo dos Vieiras</option>
        <option value="2105500">João Lisboa</option>
        <option value="2105609">Joselândia</option>
        <option value="2105658">Junco do Maranhão</option>
        <option value="2105708">Lago da Pedra</option>
        <option value="2105807">Lago do Junco</option>
        <option value="2105948">Lago dos Rodrigues</option>
        <option value="2105906">Lago Verde</option>
        <option value="2105922">Lagoa do Mato</option>
        <option value="2105963">Lagoa Grande do Maranhão</option>
        <option value="2105989">Lajeado Novo</option>
        <option value="2106003">Lima Campos</option>
        <option value="2106102">Loreto</option>
        <option value="2106201">Luís Domingues</option>
        <option value="2106300">Magalhães de Almeida</option>
        <option value="2106326">Maracaçumé</option>
        <option value="2106359">Marajá do Sena</option>
        <option value="2106375">Maranhãozinho</option>
        <option value="2106409">Mata Roma</option>
        <option value="2106508">Matinha</option>
        <option value="2106607">Matões</option>
        <option value="2106631">Matões do Norte</option>
        <option value="2106672">Milagres do Maranhão</option>
        <option value="2106706">Mirador</option>
        <option value="2106755">Miranda do Norte</option>
        <option value="2106805">Mirinzal</option>
        <option value="2106904">Monção</option>
        <option value="2107001">Montes Altos</option>
        <option value="2107100">Morros</option>
        <option value="2107209">Nina Rodrigues</option>
        <option value="2107258">Nova Colinas</option>
        <option value="2107308">Nova Iorque</option>
        <option value="2107357">Nova Olinda do Maranhão</option>
        <option value="2107407">Olho d'Água das Cunhãs</option>
        <option value="2107456">Olinda Nova do Maranhão</option>
        <option value="2107506">Paço do Lumiar</option>
        <option value="2107605">Palmeirândia</option>
        <option value="2107704">Paraibano</option>
        <option value="2107803">Parnarama</option>
        <option value="2107902">Passagem Franca</option>
        <option value="2108009">Pastos Bons</option>
        <option value="2108058">Paulino Neves</option>
        <option value="2108108">Paulo Ramos</option>
        <option value="2108207">Pedreiras</option>
        <option value="2108256">Pedro do Rosário</option>
        <option value="2108306">Penalva</option>
        <option value="2108405">Peri Mirim</option>
        <option value="2108454">Peritoró</option>
        <option value="2108504">Pindaré-Mirim</option>
        <option value="2108603">Pinheiro</option>
        <option value="2108702">Pio XII</option>
        <option value="2108801">Pirapemas</option>
        <option value="2108900">Poção de Pedras</option>
        <option value="2109007">Porto Franco</option>
        <option value="2109056">Porto Rico do Maranhão</option>
        <option value="2109106">Presidente Dutra</option>
        <option value="2109205">Presidente Juscelino</option>
        <option value="2109239">Presidente Médici</option>
        <option value="2109270">Presidente Sarney</option>
        <option value="2109304">Presidente Vargas</option>
        <option value="2109403">Primeira Cruz</option>
        <option value="2109452">Raposa</option>
        <option value="2109502">Riachão</option>
        <option value="2109551">Ribamar Fiquene</option>
        <option value="2109601">Rosário</option>
        <option value="2109700">Sambaíba</option>
        <option value="2109759">Santa Filomena do Maranhão</option>
        <option value="2109809">Santa Helena</option>
        <option value="2109908">Santa Inês</option>
        <option value="2110005">Santa Luzia</option>
        <option value="2110039">Santa Luzia do Paruá</option>
        <option value="2110104">Santa Quitéria do Maranhão</option>
        <option value="2110203">Santa Rita</option>
        <option value="2110237">Santana do Maranhão</option>
        <option value="2110278">Santo Amaro do Maranhão</option>
        <option value="2110302">Santo Antônio dos Lopes</option>
        <option value="2110401">São Benedito do Rio Preto</option>
        <option value="2110500">São Bento</option>
        <option value="2110609">São Bernardo</option>
        <option value="2110658">São Domingos do Azeitão</option>
        <option value="2110708">São Domingos do Maranhão</option>
        <option value="2110807">São Félix de Balsas</option>
        <option value="2110856">São Francisco do Brejão</option>
        <option value="2110906">São Francisco do Maranhão</option>
        <option value="2111003">São João Batista</option>
        <option value="2111029">São João do Carú</option>
        <option value="2111052">São João do Paraíso</option>
        <option value="2111078">São João do Soter</option>
        <option value="2111102">São João dos Patos</option>
        <option value="2111201">São José de Ribamar</option>
        <option value="2111250">São José dos Basílios</option>
        <option value="2111300">São Luís</option>
        <option value="2111409">São Luís Gonzaga do Maranhão</option>
        <option value="2111508">São Mateus do Maranhão</option>
        <option value="2111532">São Pedro da Água Branca</option>
        <option value="2111573">São Pedro dos Crentes</option>
        <option value="2111607">São Raimundo das Mangabeiras</option>
        <option value="2111631">São Raimundo do Doca Bezerra</option>
        <option value="2111672">São Roberto</option>
        <option value="2111706">São Vicente Ferrer</option>
        <option value="2111722">Satubinha</option>
        <option value="2111748">Senador Alexandre Costa</option>
        <option value="2111763">Senador La Rocque</option>
        <option value="2111789">Serrano do Maranhão</option>
        <option value="2111805">Sítio Novo</option>
        <option value="2111904">Sucupira do Norte</option>
        <option value="2111953">Sucupira do Riachão</option>
        <option value="2112001">Tasso Fragoso</option>
        <option value="2112100">Timbiras</option>
        <option value="2112209">Timon</option>
        <option value="2112233">Trizidela do Vale</option>
        <option value="2112274">Tufilândia</option>
        <option value="2112308">Tuntum</option>
        <option value="2112407">Turiaçu</option>
        <option value="2112456">Turilândia</option>
        <option value="2112506">Tutóia</option>
        <option value="2112605">Urbano Santos</option>
        <option value="2112704">Vargem Grande</option>
        <option value="2112803">Viana</option>
        <option value="2112852">Vila Nova dos Martírios</option>
        <option value="2112902">Vitória do Mearim</option>
        <option value="2113009">Vitorino Freire</option>
        <option value="2114007">Zé Doca</option>
    </select>
    
    <label for="tipo_logradouro">Tipo de Logradouro (*)</label>
    <select id="tipo_logradouro" name="tipo_logradouro" required>
        <option value="">Selecione o tipo de logradouro</option>
        <option value="1">Acampamento</option>
        <option value="2">Acesso</option>
        <option value="3">Açude</option>
        <option value="4">Adro</option>
        <option value="5">Aeroporto</option>
        <option value="6">Afluente</option>
        <option value="7">Aglomerado</option>
        <option value="8">Agrovila</option>
        <option value="9">Alagado</option>
        <option value="10">Alameda</option>
        <option value="11">Aldeia</option>
        <option value="12">Aleia</option>
        <option value="13">Alto</option>
        <option value="14">Anel</option>
        <option value="15">Antiga</option>
        <option value="16">Antigo</option>
        <option value="17">Área</option>
        <option value="18">Areal</option>
        <option value="19">Arraial</option>
        <option value="20">Arroio</option>
        <option value="21">Artéria</option>
        <option value="22">Assentamento</option>
        <option value="23">Atalho</option>
        <option value="24">Aterro</option>
        <option value="25">Autódromo</option>
        <option value="26">Avenida</option>
        <option value="27">Baia</option>
        <option value="28">Bairro</option>
        <option value="29">Baixa</option>
        <option value="30">Baixada</option>
        <option value="31">Baixadão</option>
        <option value="32">Baixão</option>
        <option value="33">Baixo</option>
        <option value="34">Balão</option>
        <option value="35">Balneário</option>
        <option value="36">Barra</option>
        <option value="37">Barragem</option>
        <option value="38">Barranca</option>
        <option value="39">Barranco</option>
        <option value="40">Barreiro</option>
        <option value="41">Barro</option>
        <option value="42">Beco</option>
        <option value="43">Beira</option>
        <option value="44">Beirada</option>
        <option value="45">Belvedere</option>
        <option value="46">Bloco</option>
        <option value="47">Bocaina</option>
        <option value="48">Boqueirão</option>
        <option value="49">Bosque</option>
        <option value="50">Boulevard</option>
        <option value="51">Brejo</option>
        <option value="52">Buraco</option>
        <option value="53">Cabeceira</option>
        <option value="54">Cachoeira</option>
        <option value="55">Cachoeirinha</option>
        <option value="56">Cais</option>
        <option value="57">Calcada</option>
        <option value="58">Calçadão</option>
        <option value="59">Caminho</option>
        <option value="60">Campo</option>
        <option value="61">Canal</option>
        <option value="62">Canteiro</option>
        <option value="63">Capão</option>
        <option value="64">Capoeira</option>
        <option value="65">Cartódromo</option>
        <option value="66">Central</option>
        <option value="67">Centro</option>
        <option value="68">Cerca</option>
        <option value="69">Cerrado</option>
        <option value="70">Cerro</option>
        <option value="71">Chácara</option>
        <option value="72">Chapada</option>
        <option value="73">Chapadão</option>
        <option value="74">Charco</option>
        <option value="75">Cidade</option>
        <option value="76">Circular</option>
        <option value="77">Cohab</option>
        <option value="78">Colina</option>
        <option value="79">Colônia</option>
        <option value="80">Comunidade</option>
        <option value="81">Condomínio</option>
        <option value="82">Conjunto</option>
        <option value="83">Continuação</option>
        <option value="84">Contorno</option>
        <option value="85">Corredor</option>
        <option value="86">Córrego</option>
        <option value="87">Costa</option>
        <option value="88">Coxilha</option>
        <option value="89">Cruzamento</option>
        <option value="90">Descida</option>
        <option value="91">Desvio</option>
        <option value="92">Dique</option>
        <option value="93">Distrito</option>
        <option value="94">Divisa</option>
        <option value="95">Divisão</option>
        <option value="96">Divisor</option>
        <option value="97">Edifício</option>
        <option value="98">Eixo</option>
        <option value="99">Elevado</option>
        <option value="100">Encosta</option>
        <option value="101">Engenho</option>
        <option value="102">Enseada</option>
        <option value="103">Entrada</option>
        <option value="104">Entreposto</option>
        <option value="105">Entroncamento</option>
        <option value="106">Escada</option>
        <option value="107">Escadão</option>
        <option value="108">Escadaria</option>
        <option value="109">Escadinha</option>
        <option value="110">Espigão</option>
        <option value="111">Esplanada</option>
        <option value="112">Esquina</option>
        <option value="113">Estação</option>
        <option value="114">Estacionamento</option>
        <option value="115">Estádio</option>
        <option value="116">Estância</option>
        <option value="117">Estrada</option>
        <option value="118">Extensão</option>
        <option value="119">Faixa</option>
        <option value="120">Favela</option>
        <option value="121">Fazenda</option>
        <option value="122">Feira</option>
        <option value="123">Ferrovia</option>
        <option value="124">Final</option>
        <option value="125">Floresta</option>
        <option value="126">Folha</option>
        <option value="127">Fonte</option>
        <option value="128">Fortaleza</option>
        <option value="129">Freguesia</option>
        <option value="130">Fundos</option>
        <option value="131">Furo</option>
        <option value="132">Galeria</option>
        <option value="133">Gameleira</option>
        <option value="134">Garimpo</option>
        <option value="135">Gleba</option>
        <option value="136">Granja</option>
        <option value="137">Grota</option>
        <option value="138">Habitacional</option>
        <option value="139">Haras</option>
        <option value="140">Hipódromo</option>
        <option value="141">Horto</option>
        <option value="142">Igarapé</option>
        <option value="143">Ilha</option>
        <option value="144">Inaplicável</option>
        <option value="145">Invasão</option>
        <option value="146">Jardim</option>
        <option value="147">Jardinete</option>
        <option value="148">Ladeira</option>
        <option value="149">Lado</option>
        <option value="150">Lago</option>
        <option value="151">Lagoa</option>
        <option value="152">Lagoinha</option>
        <option value="153">Largo</option>
        <option value="154">Lateral</option>
        <option value="155">Leito</option>
        <option value="156">Ligação</option>
        <option value="157">Limeira</option>
        <option value="158">Limite</option>
        <option value="159">Limites</option>
        <option value="160">Linha</option>
        <option value="161">Lote</option>
        <option value="162">Loteamento</option>
        <option value="163">Lugarejo</option>
        <option value="164">Maloca</option>
        <option value="165">Manancial</option>
        <option value="166">Mangue</option>
        <option value="167">Margem</option>
        <option value="168">Margens</option>
        <option value="169">Marginal</option>
        <option value="170">Marina</option>
        <option value="171">Mata</option>
        <option value="172">Mato</option>
        <option value="173">Módulo</option>
        <option value="174">Monte</option>
        <option value="175">Morro</option>
        <option value="176">Muro</option>
        <option value="177">Não Especificado</option>
        <option value="178">Núcleo</option>
        <option value="179">Oca</option>
        <option value="180">Oleoduto</option>
        <option value="181">Olho</option>
        <option value="182">Olhos</option>
        <option value="183">Orla</option>
        <option value="184">Outros</option>
        <option value="185">Paco</option>
        <option value="186">Palafita</option>
        <option value="187">Pântano</option>
        <option value="188">Parada</option>
        <option value="189">Paradouro</option>
        <option value="190">Paralela</option>
        <option value="191">Parque</option>
        <option value="192">Particular</option>
        <option value="193">Passagem</option>
        <option value="194">Passarela</option>
        <option value="195">Passeio</option>
        <option value="196">Passo</option>
        <option value="197">Pasto</option>
        <option value="198">Pátio</option>
        <option value="199">Pavilhão</option>
        <option value="200">Pedra</option>
        <option value="201">Pedras</option>
        <option value="202">Pedreira</option>
        <option value="203">Penhasco</option>
        <option value="204">Perimetral</option>
        <option value="205">Perímetro</option>
        <option value="206">Perto</option>
        <option value="207">Planalto</option>
        <option value="208">Plataforma</option>
        <option value="209">Ponta</option>
        <option value="210">Ponte</option>
        <option value="211">Ponto</option>
        <option value="212">Porto</option>
        <option value="213">Posto</option>
        <option value="214">Povoado</option>
        <option value="215">Praça</option>
        <option value="216">Praia</option>
        <option value="217">Projeção</option>
        <option value="218">Projetada</option>
        <option value="219">Projeto</option>
        <option value="220">Prolongamento</option>
        <option value="221">Propriedade</option>
        <option value="222">Próximo</option>
        <option value="223">Quadra</option>
        <option value="224">Quarteirão</option>
        <option value="225">Quilombo</option>
        <option value="226">Quilometro</option>
        <option value="227">Quinta</option>
        <option value="228">Rachão</option>
        <option value="229">Ramal</option>
        <option value="230">Rampa</option>
        <option value="231">Rancho</option>
        <option value="232">Recanto</option>
        <option value="233">Região</option>
        <option value="234">Represa</option>
        <option value="235">Residencial</option>
        <option value="236">Reta</option>
        <option value="237">Retiro</option>
        <option value="238">Retorno</option>
        <option value="239">Riacho</option>
        <option value="240">Ribanceira</option>
        <option value="241">Ribeirão</option>
        <option value="242">Rincão</option>
        <option value="243">Rio</option>
        <option value="244">Rocha</option>
        <option value="245">Rochedo</option>
        <option value="246">Rodovia</option>
        <option value="247">Rotatória</option>
        <option value="248">Rotula</option>
        <option value="249">Rua</option>
        <option value="250">Ruela</option>
        <option value="251">Saco</option>
        <option value="252">Saída</option>
        <option value="253">Sanga</option>
        <option value="254">Sede</option>
        <option value="255">Sem</option>
        <option value="256">Seringal</option>
        <option value="257">Serra</option>
        <option value="258">Sertão</option>
        <option value="259">Servidão</option>
        <option value="260">Setor</option>
        <option value="261">Seta</option>
        <option value="262">Sítio</option>
        <option value="263">Sopé</option>
        <option value="264">Subida</option>
        <option value="265">Superquadra</option>
        <option value="266">Tapera</option>
        <option value="267">Terminal</option>
        <option value="268">Terra</option>
        <option value="269">Terreno</option>
        <option value="270">Terrenos</option>
        <option value="271">Transversal</option>
        <option value="272">Travessa</option>
        <option value="273">Travessão</option>
        <option value="274">Travessia</option>
        <option value="275">Trecho</option>
        <option value="276">Trevo</option>
        <option value="277">Trilha</option>
        <option value="278">Trilho</option>
        <option value="279">Trilhos</option>
        <option value="280">Trincheira</option>
        <option value="281">Túnel</option>
        <option value="282">Unidade</option>
        <option value="283">Usina</option>
        <option value="284">Vala</option>
        <option value="285">Valão</option>
        <option value="286">Vale</option>
        <option value="287">Vargem</option>
        <option value="288">Variante</option>
        <option value="289">Várzea</option>
        <option value="290">Velódromo</option>
        <option value="291">Vereda</option>
        <option value="292">Vertente</option>
        <option value="293">Via</option>
        <option value="294">Viaduto</option>
        <option value="295">Vicinal</option>
        <option value="296">Viela</option>
        <option value="297">Vila</option>
        <option value="298">Vilarejo</option>
        <option value="299">Volta</option>
        <option value="300">Zona</option>
        <option value="301">1a Travessa da Avenida</option>
        <option value="302">1a Travessa da Rua</option>
        <option value="303">2a Travessa da Avenida</option>
        <option value="304">2a Travessa da Rua</option>
        <option value="305">3a Travessa da Avenida</option>
        <option value="306">3a Travessa da Rua</option>
        <option value="307">4a Travessa da Avenida</option>
        <option value="308">4a Travessa da Rua</option>
        <option value="309">5a Travessa da Avenida</option>
        <option value="310">5a Travessa da Rua</option>
    </select>
    
    <label for="nome_logradouro">Endereço (*)</label>
    <input type="text" id="nome_logradouro" name="nome_logradouro" placeholder="Ex.: Rua São Francisco..." required>
    
    <label for="numero_logradouro">Número (*)</label>
    <input type="text" id="numero_logradouro" name="numero_logradouro" placeholder="Ex.: 123..." required>
    
    <label for="bairro">Bairro (*)</label>
    <input type="text" id="bairro" name="bairro" placeholder="Ex.: Centro" required>

    <label for="cep">CEP (*)</label>
    <input type="text" id="cep" name="cep" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="8" placeholder="Ex.: 65248000"d> 

    <input type="submit" class="btn2 first" style="margin-right: -110%!important; margin-left: 50px!important;margin-top: -13px!important;padding: 0.3em 0.0em!important;" value="Salvar">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Carrega o conteúdo do arquivo CNS JSON
    $cns_file = "../carimbo-digital/cns.json";
    $cns_data = file_get_contents($cns_file);
    $cns_json = json_decode($cns_data, true);

    // Verifica se o CNS foi carregado corretamente
    if ($cns_json && isset($cns_json['cns'])) {
        $cns_numero = $cns_json['cns'];

        // Dados do formulário
        $numero_registro = $_POST['numero_registro'];
        $registro_tipo = (int)$_POST['registro_tipo'];
        $tipo_imovel = (int)$_POST['tipo_imovel'];
        $localizacao = (int)$_POST['localizacao'];
        $uf = (int)$_POST['uf'];
        $cidade = (int)$_POST['cidade'];
        $tipo_logradouro = (int)$_POST['tipo_logradouro'];
        $nome_logradouro = $_POST['nome_logradouro'];
        $numero_logradouro = $_POST['numero_logradouro'];
        $bairro = $_POST['bairro'];
        $cep = (int)$_POST['cep'];

        // Monta o array com os dados do formulário
        $data = array(
            "INDICADOR_REAL" => array(
                "CNS" => $cns_numero,
                "REAL" => array(
                    array(
                        "TIPOENVIO" => 0,
                        "NUMERO_REGISTRO" => $numero_registro,
                        "REGISTRO_TIPO" => $registro_tipo,
                        "TIPO_DE_IMOVEL" => $tipo_imovel,
                        "LOCALIZACAO" => $localizacao,
                        "TIPO_LOGRADOURO" => $tipo_logradouro,
                        "NOME_LOGRADOURO" => $nome_logradouro,
                        "NUMERO_LOGRADOURO" => $numero_logradouro,
                        "UF" => $uf,
                        "CIDADE" => $cidade,
                        "BAIRRO" => $bairro,
                        "CEP" => "$cep",
                        "COMPLEMENTO" => "",
                        "QUADRA" => "",
                        "CONJUNTO" => "",
                        "SETOR" => "",
                        "LOTE" => "",
                        "LOTEAMENTO" => "",
                        "CONTRIBUINTE" => array(""),
                        "RURAL" => array(
                            "CAR" => "",
                            "NIRF" => "",
                            "CCIR" => "",
                            "NUMERO_INCRA" => "",
                            "SIGEF" => "",
                            "DENOMINACAORURAL" => "",
                            "ACIDENTEGEOGRAFICO" => ""
                        ),
                        "CONDOMINIO" => array(
                            "NOME_CONDOMINIO" => "",
                            "BLOCO" => "",
                            "CONJUNTO" => "",
                            "TORRE" => "",
                            "APTO" => "",
                            "VAGA" => ""
                        )
                    )
                )
            )
        );

        // Converte o array para JSON com indentação
        $json_data = json_encode($data, JSON_PRETTY_PRINT);

        // Nome do arquivo
        $nome_arquivo = $registro_tipo . "_" . $numero_registro . ".json";
        
        // Caminho completo do arquivo
        $caminho_arquivo = "indicador/" . $nome_arquivo;

        // Salvar o arquivo no diretório
        file_put_contents($caminho_arquivo, $json_data);

        // Exibe uma mensagem de sucesso
        echo "Indicador salvo com sucesso";
    } else {
        // Exibe uma mensagem de erro se não conseguir carregar o CNS
        echo "Erro ao carregar o CNS";
    }
}
?>

</div>
    

<div class="container">
       <h2>Indicador Real</h2>
        <table id="matriculasTable" class="display">
    <thead>
        <tr>
            <th>Número de Registro</th>
            <th>Tipo de Registro</th>
            <th>Tipo de Imóvel</th>
            <th>Localização</th>
            <th>Endereço</th>
            <th>Número</th>
            <th>Bairro</th>
            <th>Cidade</th>
            <th>Download</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Caminho para os arquivos JSON salvos
        $directory = 'indicador/';

        // Lista todos os arquivos JSON no diretório
        $files = glob($directory . '*.json');

        foreach ($files as $file) {
            $content = json_decode(file_get_contents($file), true);
            foreach ($content['INDICADOR_REAL']['REAL'] as $item) {
                // Mapear REGISTRO_TIPO para texto correspondente
                $tipo_registro = '';
                switch ($item['REGISTRO_TIPO']) {
                    case '1':
                        $tipo_registro = 'Matrícula';
                        break;
                    case '2':
                        $tipo_registro = 'Matrícula MÃE';
                        break;
                    case '3':
                        $tipo_registro = 'Livro 3-reg-aux';
                        break;
                    case '4':
                        $tipo_registro = 'Transcrição';
                        break;
                    default:
                        $tipo_registro = 'Desconhecido';
                        break;
                }

                // Mapear LOCALIZACAO para texto correspondente
                $localizacao = '';
                switch ($item['LOCALIZACAO']) {
                    case '0':
                        $localizacao = 'Urbano';
                        break;
                    case '1':
                        $localizacao = 'Rural';
                        break;
                    default:
                        $localizacao = 'Desconhecida';
                        break;
                }

                // Mapear TIPO_DE_IMOVEL para texto correspondente
                $tipo_imovel = '';
                switch ($item['TIPO_DE_IMOVEL']) {
                    case '1':
                        $tipo_imovel = 'Casa';
                        break;
                    case '2':
                        $tipo_imovel = 'Apartamento';
                        break;
                    case '3':
                        $tipo_imovel = 'Loja';
                        break;
                    case '4':
                        $tipo_imovel = 'Sala/Conjunto';
                        break;
                    case '5':
                        $tipo_imovel = 'Terreno/Fração';
                        break;
                    case '6':
                        $tipo_imovel = 'Galpão';
                        break;
                    case '7':
                        $tipo_imovel = 'Prédio Comercial';
                        break;
                    case '8':
                        $tipo_imovel = 'Prédio Residencial';
                        break;
                    case '9':
                        $tipo_imovel = 'Fazenda/Sítio/Chácara';
                        break;
                    case '10':
                        $tipo_imovel = 'Vaga';
                        break;
                    case '11':
                        $tipo_imovel = 'Depósito';
                        break;
                    default:
                        $tipo_imovel = 'Desconhecido';
                        break;
                }
        ?>

                    <?php
                    // Array associativo mapeando códigos de cidade para descrições
                    $cidades = array(
                        "2100055" => "Açailândia",
                        "2100105" => "Afonso Cunha",
                        "2100154" => "Água Doce do Maranhão",
                        "2100204" => "Alcântara",
                        "2100303" => "Aldeias Altas",
                        "2100402" => "Altamira do Maranhão",
                        "2100436" => "Alto Alegre do Maranhão",
                        "2100477" => "Alto Alegre do Pindaré",
                        "2100501" => "Alto Parnaíba",
                        "2100550" => "Amapá do Maranhão",
                        "2100600" => "Amarante do Maranhão",
                        "2100709" => "Anajatuba",
                        "2100808" => "Anapurus",
                        "2100832" => "Apicum-Açu",
                        "2100873" => "Araguanã",
                        "2100907" => "Araioses",
                        "2100956" => "Arame",
                        "2101004" => "Arari",
                        "2101103" => "Axixá",
                        "2101202" => "Bacabal",
                        "2101251" => "Bacabeira",
                        "2101301" => "Bacuri",
                        "2101350" => "Bacurituba",
                        "2101400" => "Balsas",
                        "2101509" => "Barão de Grajaú",
                        "2101608" => "Barra do Corda",
                        "2101707" => "Barreirinhas",
                        "2101772" => "Bela Vista do Maranhão",
                        "2101731" => "Belágua",
                        "2101806" => "Benedito Leite",
                        "2101905" => "Bequimão",
                        "2101939" => "Bernardo do Mearim",
                        "2101970" => "Boa Vista do Gurupi",
                        "2102002" => "Bom Jardim",
                        "2102036" => "Bom Jesus das Selvas",
                        "2102077" => "Bom Lugar",
                        "2102101" => "Brejo",
                        "2102150" => "Brejo de Areia",
                        "2102200" => "Buriti",
                        "2102309" => "Buriti Bravo",
                        "2102325" => "Buriticupu",
                        "2102358" => "Buritirana",
                        "2102374" => "Cachoeira Grande",
                        "2102408" => "Cajapió",
                        "2102507" => "Cajari",
                        "2102556" => "Campestre do Maranhão",
                        "2102606" => "Cândido Mendes",
                        "2102705" => "Cantanhede",
                        "2102754" => "Capinzal do Norte",
                        "2102804" => "Carolina",
                        "2102903" => "Carutapera",
                        "2103000" => "Caxias",
                        "2103109" => "Cedral",
                        "2103125" => "Central do Maranhão",
                        "2103158" => "Centro do Guilherme",
                        "2103174" => "Centro Novo do Maranhão",
                        "2103208" => "Chapadinha",
                        "2103257" => "Cidelândia",
                        "2103307" => "Codó",
                        "2103406" => "Coelho Neto",
                        "2103505" => "Colinas",
                        "2103554" => "Conceição do Lago-Açu",
                        "2103604" => "Coroatá",
                        "2103703" => "Cururupu",
                        "2103752" => "Davinópolis",
                        "2103802" => "Dom Pedro",
                        "2103901" => "Duque Bacelar",
                        "2104008" => "Esperantinópolis",
                        "2104057" => "Estreito",
                        "2104073" => "Feira Nova do Maranhão",
                        "2104081" => "Fernando Falcão",
                        "2104099" => "Formosa da Serra Negra",
                        "2104107" => "Fortaleza dos Nogueiras",
                        "2104206" => "Fortuna",
                        "2104305" => "Godofredo Viana",
                        "2104404" => "Gonçalves Dias",
                        "2104503" => "Governador Archer",
                        "2104552" => "Governador Edison Lobão",
                        "2104602" => "Governador Eugênio Barros",
                        "2104628" => "Governador Luiz Rocha",
                        "2104651" => "Governador Newton Bello",
                        "2104677" => "Governador Nunes Freire",
                        "2104701" => "Graça Aranha",
                        "2104800" => "Grajaú",
                        "2104909" => "Guimarães",
                        "2105005" => "Humberto de Campos",
                        "2105104" => "Icatu",
                        "2105153" => "Igarapé do Meio",
                        "2105203" => "Igarapé Grande",
                        "2105302" => "Imperatriz",
                        "2105351" => "Itaipava do Grajaú",
                        "2105401" => "Itapecuru Mirim",
                        "2105427" => "Itinga do Maranhão",
                        "2105450" => "Jatobá",
                        "2105476" => "Jenipapo dos Vieiras",
                        "2105500" => "João Lisboa",
                        "2105609" => "Joselândia",
                        "2105658" => "Junco do Maranhão",
                        "2105708" => "Lago da Pedra",
                        "2105807" => "Lago do Junco",
                        "2105948" => "Lago dos Rodrigues",
                        "2105906" => "Lago Verde",
                        "2105922" => "Lagoa do Mato",
                        "2105963" => "Lagoa Grande do Maranhão",
                        "2105989" => "Lajeado Novo",
                        "2106003" => "Lima Campos",
                        "2106102" => "Loreto",
                        "2106201" => "Luís Domingues",
                        "2106300" => "Magalhães de Almeida",
                        "2106326" => "Maracaçumé",
                        "2106359" => "Marajá do Sena",
                        "2106375" => "Maranhãozinho",
                        "2106409" => "Mata Roma",
                        "2106508" => "Matinha",
                        "2106607" => "Matões",
                        "2106631" => "Matões do Norte",
                        "2106672" => "Milagres do Maranhão",
                        "2106706" => "Mirador",
                        "2106755" => "Miranda do Norte",
                        "2106805" => "Mirinzal",
                        "2106904" => "Monção",
                        "2107001" => "Montes Altos",
                        "2107100" => "Morros",
                        "2107209" => "Nina Rodrigues",
                        "2107258" => "Nova Colinas",
                        "2107308" => "Nova Iorque",
                        "2107357" => "Nova Olinda do Maranhão",
                        "2107407" => "Olho d'Água das Cunhãs",
                        "2107456" => "Olinda Nova do Maranhão",
                        "2107506" => "Paço do Lumiar",
                        "2107605" => "Palmeirândia",
                        "2107704" => "Paraibano",
                        "2107803" => "Parnarama",
                        "2107902" => "Passagem Franca",
                        "2108009" => "Pastos Bons",
                        "2108058" => "Paulino Neves",
                        "2108108" => "Paulo Ramos",
                        "2108207" => "Pedreiras",
                        "2108256" => "Pedro do Rosário",
                        "2108306" => "Penalva",
                        "2108405" => "Peri Mirim",
                        "2108454" => "Peritoró",
                        "2108504" => "Pindaré-Mirim",
                        "2108603" => "Pinheiro",
                        "2108702" => "Pio XII",
                        "2108801" => "Pirapemas",
                        "2108900" => "Poção de Pedras",
                        "2109007" => "Porto Franco",
                        "2109056" => "Porto Rico do Maranhão",
                        "2109106" => "Presidente Dutra",
                        "2109205" => "Presidente Juscelino",
                        "2109239" => "Presidente Médici",
                        "2109270" => "Presidente Sarney",
                        "2109304" => "Presidente Vargas",
                        "2109403" => "Primeira Cruz",
                        "2109452" => "Raposa",
                        "2109502" => "Riachão",
                        "2109551" => "Ribamar Fiquene",
                        "2109601" => "Rosário",
                        "2109700" => "Sambaíba",
                        "2109759" => "Santa Filomena do Maranhão",
                        "2109809" => "Santa Helena",
                        "2109908" => "Santa Inês",
                        "2110005" => "Santa Luzia",
                        "2110039" => "Santa Luzia do Paruá",
                        "2110104" => "Santa Quitéria do Maranhão",
                        "2110203" => "Santa Rita",
                        "2110237" => "Santana do Maranhão",
                        "2110278" => "Santo Amaro do Maranhão",
                        "2110302" => "Santo Antônio dos Lopes",
                        "2110401" => "São Benedito do Rio Preto",
                        "2110500" => "São Bento",
                        "2110609" => "São Bernardo",
                        "2110658" => "São Domingos do Azeitão",
                        "2110708" => "São Domingos do Maranhão",
                        "2110807" => "São Félix de Balsas",
                        "2110856" => "São Francisco do Brejão",
                        "2110906" => "São Francisco do Maranhão",
                        "2111003" => "São João Batista",
                        "2111029" => "São João do Carú",
                        "2111052" => "São João do Paraíso",
                        "2111078" => "São João do Soter",
                        "2111102" => "São João dos Patos",
                        "2111201" => "São José de Ribamar",
                        "2111250" => "São José dos Basílios",
                        "2111300" => "São Luís",
                        "2111409" => "São Luís Gonzaga do Maranhão",
                        "2111508" => "São Mateus do Maranhão",
                        "2111532" => "São Pedro da Água Branca",
                        "2111573" => "São Pedro dos Crentes",
                        "2111607" => "São Raimundo das Mangabeiras",
                        "2111631" => "São Raimundo do Doca Bezerra",
                        "2111672" => "São Roberto",
                        "2111706" => "São Vicente Ferrer",
                        "2111722" => "Satubinha",
                        "2111748" => "Senador Alexandre Costa",
                        "2111763" => "Senador La Rocque",
                        "2111789" => "Serrano do Maranhão",
                        "2111805" => "Sítio Novo",
                        "2111904" => "Sucupira do Norte",
                        "2111953" => "Sucupira do Riachão",
                        "2112001" => "Tasso Fragoso",
                        "2112100" => "Timbiras",
                        "2112209" => "Timon",
                        "2112233" => "Trizidela do Vale",
                        "2112274" => "Tufilândia",
                        "2112308" => "Tuntum",
                        "2112407" => "Turiaçu",
                        "2112456" => "Turilândia",
                        "2112506" => "Tutóia",
                        "2112605" => "Urbano Santos",
                        "2112704" => "Vargem Grande",
                        "2112803" => "Viana",
                        "2112852" => "Vila Nova dos Martírios",
                        "2112902" => "Vitória do Mearim",
                        "2113009" => "Vitorino Freire",
                        "2114007" => "Zé Doca"
                    );

                    // Supondo que $item['CIDADE'] contenha o código da cidade
                    $codigoCidade = $item['CIDADE'];
                    $descricaoCidade = isset($cidades[$codigoCidade]) ? $cidades[$codigoCidade] : "Cidade não encontrada";

                    ?>

                <tr>
                    <td><?php echo str_pad($item['NUMERO_REGISTRO'], 8, '0', STR_PAD_LEFT); ?></td>
                    <td><?php echo $tipo_registro; ?></td>
                    <td><?php echo $tipo_imovel; ?></td>
                    <td><?php echo $localizacao; ?></td>
                    <td><?php echo $item['NOME_LOGRADOURO']; ?></td>
                    <td><?php echo $item['NUMERO_LOGRADOURO']; ?></td>
                    <td><?php echo $item['BAIRRO']; ?></td>
                    <td><?php echo $descricaoCidade; ?></td>
                    <td><a class="btn first" href='indicador/<?php echo $item['REGISTRO_TIPO'] . "_" . $item['NUMERO_REGISTRO']; ?>.json' download>Download</a></td>
                    <td><button class="btn2-gradient delete-link" style="background: rgb(255 99 132 / 53%)"
                            onclick="confirmDelete('<?php echo $item['REGISTRO_TIPO'] . '_' . $item['NUMERO_REGISTRO']; ?>')">
                            <i class="fa fa-trash-o fa-1x" style="color: #fff" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<script>
    function confirmDelete(matricula) {
        // Confirmar exclusão com o usuário
        var confirmacao = confirm("Tem certeza que deseja deletar o arquivo?");
        if (confirmacao) {
            // Requisição AJAX para excluir o arquivo
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Atualizar a página após a exclusão
                    location.reload();
                }
            };
            xhr.open('GET', 'delete.php?matricula=' + matricula, true);
            xhr.send();
        }
    }
</script>

<div id="sincronizar3">
                <button class="btn2 first" id="openModalBtn">Exportar Carga ONR</button>
    </div>

<script>
    $(document).ready(function() {
        $('#matriculasTable').DataTable();
    });
</script>


<style>
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #00000066;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 30%;
    display: grid;
    grid-template-columns: repeat(1, 1fr);
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #fff;
  text-decoration: none;
  cursor: pointer;
}

    </style>

<!-- Modal de Busca -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Selecione as datas:</h2>
    <label for="dataInicial">Data Inicial:</label>
    <input type="date" id="dataInicial" name="dataInicial">

    <label for="dataFinal">Data Final:</label>
    <input type="date" id="dataFinal" name="dataFinal">
<br>
    <button class="btn2 first" style="background: rgb(255 255 255 / 87%);" id="searchBtn">Gerar arquivo</button>
  </div>
</div>

<script>
// Abrir o modal ao clicar no botão
document.getElementById('openModalBtn').addEventListener('click', function() {
  document.getElementById('myModal').style.display = 'block';
});

// Fechar o modal ao clicar no "x"
document.getElementsByClassName('close')[0].addEventListener('click', function() {
  document.getElementById('myModal').style.display = 'none';
});

// Fechar o modal ao clicar fora dele
window.onclick = function(event) {
  if (event.target == document.getElementById('myModal')) {
    document.getElementById('myModal').style.display = 'none';
  }
}

// Localizar arquivos e gerar arquivo único
document.getElementById('searchBtn').addEventListener('click', function() {
  var dataInicial = document.getElementById('dataInicial').value;
  var dataFinal = document.getElementById('dataFinal').value;

  // Iniciar requisição AJAX para buscar arquivos JSON
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'buscar_arquivos.php?dataInicial=' + dataInicial + '&dataFinal=' + dataFinal, true);
  xhr.responseType = 'blob';

  xhr.onload = function() {
    if (xhr.status === 200) {
      // Criar um link de download
      var link = document.createElement('a');
      link.href = URL.createObjectURL(xhr.response);
      link.download = 'indicador-real-' + dataInicial + '-' + dataFinal + '.json';

      // Clicar no link para iniciar o download
      link.click();

      // Fechar o modal após o download
      document.getElementById('myModal').style.display = 'none';
    }
  };

  xhr.send();
});

</script>
</div>


<div class="container">
    <h3 style="margin: 0px 0;">Lista de Indicadores Faltantes</h3>
    
    <?php if (!empty($numerosFaltantes)): ?>
        <table id="tabela-historico2" class="display">
            <thead>
                <tr>
                    <th>Matrícula Nº</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($numerosFaltantes as $numeroFaltante): ?>
                    <tr>
                        <td><?php echo $numeroFaltante; ?></td>
                    </tr>
                <?php endforeach; ?>
            
            </tbody>
            
        </table>

        <script>
        $(document).ready(function() {
            $('#tabela-historico2').DataTable({
                "order": [[ 0, "asc" ]]
            });
        });
        </script>
    <?php else: ?>
        <p style="color: #ffffff;">Nenhum indicador faltante encontrado.</p>
    <?php endif; ?>
</div>

<?php include_once("../rodape.php");?>

</body>
</html>
