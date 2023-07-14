<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Configuração</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../styles.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> -->
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            // Preencher o campo de texto com o CNS salvo no JSON
            fetch('cns.json')
                .then(response => response.json())
                .then(data => {
                    if (data.cns) {
                        document.getElementById('cns').value = data.cns;
                    }
                });

            // Exibir a mensagem em forma de pop-up
            const form = document.getElementById('config-form');
            const successMessage = document.getElementById('success-message');
            const successButton = successMessage.querySelector('button');

            form.addEventListener('submit', (event) => {
                event.preventDefault();

                const cns = document.getElementById('cns').value;

                if (cns.length !== 6 || !/^\d+$/.test(cns)) {
                    alert('O CNS deve ter exatamente 6 caracteres numéricos.');
                    return;
                }

                const jsonData = {
                    cns: cns
                };

                // Salvar o CNS no JSON
                fetch('salvar_cns.php', {
                    method: 'POST',
                    body: JSON.stringify(jsonData)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            successMessage.style.display = 'block';
                        }
                    });
            });

            successButton.addEventListener('click', () => {
                successMessage.style.display = 'none';
            });
        });
    </script>
</head>
<body>
    <header class="header">
        <div class="logo">
            <br>
            <img src="../img/logo.png" alt="Logo">
            <h1>DocMark</h1>
        </div>
        <nav class="menu">
        <ul>
                <li><a href="index.php">Carimbo Digital</a></li>
                <li><a href="../pdf-para-tiff/index.php">Converter PDF para TIFF</a></li>
                <li><a href="../single-tif-para-pdf/index.php">Converter TIFF para PDF</a></li>
                <!-- <li><a href="../contato.php">Contato</a></li>
                <li><a href="../sobre.php">Sobre</a></li> -->
                <li><a href="configuracao.php">Configuração</a></li>
             </ul>
        </nav>
    </header>
    <div class="container" style="margin-bottom: 20%;">
        <h1>Configuração</h1>
        <form id="config-form">
            <label for="cns">CNS:</label>
            <input type="text" name="cns" id="cns" required pattern="[0-9]{6}">
            <br>
            <input type="submit" name="submit" value="Salvar">
        </form>
        <div id="success-message" class="success-message">
            <p>CNS salvo com sucesso!</p>
            <button>OK</button>
        </div>
    </div>
    <footer>
    
    <p style="color: #fff;text-decoration: none"> <p><a style="color: #fff;text-decoration: none"  href="https://backupcloud.site/" target="_blank">&copy; <span id="year"></span> DocMark | By Backup Cloud. Todos os direitos reservados.</a></p></p>
    
  </footer>
  
  <script>
    // Obtém o ano atual e insere no elemento de ID "year"
    document.getElementById("year").textContent = new Date().getFullYear();
  </script>
</body>
</html>
