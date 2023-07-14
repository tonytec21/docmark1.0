<!DOCTYPE html>
<html>
<head>
    <title>DocMark - Configuração</title>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="stylesheet" href="../styles.css">
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            fetch('cns.json')
                .then(response => response.json())
                .then(data => {
                    if (data.cns) {
                        document.getElementById('cns').value = data.cns;
                    }
                });

            const form = document.getElementById('config-form');
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            const successButton = successMessage.querySelector('button');
            const errorButton = errorMessage.querySelector('button');

            form.addEventListener('submit', (event) => {
                event.preventDefault();

                const cns = document.getElementById('cns').value;

                if (cns.length !== 6 || !/^\d+$/.test(cns)) {
                    errorMessage.querySelector('p').textContent = 'O CNS deve ter exatamente 6 caracteres numéricos.';
                    errorMessage.style.display = 'block';
                    return;
                }

                const jsonData = {
                    cns: cns
                };

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

            errorButton.addEventListener('click', () => {
                errorMessage.style.display = 'none';
            });

            const uploadForm1 = document.getElementById('upload-form1');
            const uploadForm2 = document.getElementById('upload-form2');
            const uploadSuccessMessage = document.getElementById('upload-success-message');
            const uploadErrorMessage = document.getElementById('upload-error-message');
            const uploadSuccessButton = uploadSuccessMessage.querySelector('button');
            const uploadErrorButton = uploadErrorMessage.querySelector('button');

            uploadForm1.addEventListener('submit', function (event) {
                event.preventDefault();
                sendFile(uploadForm1);
            });

            uploadForm2.addEventListener('submit', function (event) {
                event.preventDefault();
                sendFile(uploadForm2);
            });

            uploadSuccessButton.addEventListener('click', function () {
                uploadSuccessMessage.style.display = 'none';
            });

            uploadErrorButton.addEventListener('click', function () {
                uploadErrorMessage.style.display = 'none';
            });

            function sendFile(form) {
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        if (data.includes("foi carregado")) {
                            uploadSuccessMessage.style.display = 'block';
                        } else {
                            uploadErrorMessage.querySelector('p').textContent = data;
                            uploadErrorMessage.style.display = 'block';
                        }
                    });
            }
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
                <li><a href="../chancela/index.php">Chancela Mecânica</a></li>
                <li><a href="configuracao.php">Configuração</a></li>
             </ul>
        </nav>
    </header>
    <div class="container" style="margin-bottom: 20%;">
        <h1>Configuração</h1>
        <form id="config-form">
            <label for="cns">CNS:</label>
            <input type="text" name="cns" id="cns" required pattern="[0-9]{6}">
            <input type="submit" name="submit" value="Salvar">
        </form>
        <br><br>
        <form id="upload-form1" action="upload1.php" method="post" enctype="multipart/form-data">
            <label for="image1">Upload da imagem 1 (chancela.png):</label>
            <input type="file" id="image1" name="image1" accept="image/png">
            <input type="submit" name="submit1" value="Enviar Imagem 1">
        </form>
        <br><br>
        <form id="upload-form2" action="upload2.php" method="post" enctype="multipart/form-data">
            <label for="image2">Upload da imagem 2 (chancela-2.png):</label>
            <input type="file" id="image2" name="image2" accept="image/png">
            <input type="submit" name="submit2" value="Enviar Imagem 2">
        </form>
        <div id="success-message" class="success-message" style="display: none;">
            <p>CNS salvo com sucesso!</p>
            <button>OK</button>
        </div>
        <div id="error-message" class="error-message" style="display: none;">
            <p></p>
            <button>OK</button>
        </div>
        <div id="upload-success-message" class="success-message" style="display: none;">
            <p>Imagem carregada com sucesso!</p>
            <button>OK</button>
        </div>
        <div id="upload-error-message" class="error-message" style="display: none;">
            <p></p>
            <button>OK</button>
        </div>
    </div>
    <footer>
    
    <p style="color: #fff;text-decoration: none"> <p><a style="color: #fff;text-decoration: none"  href="https://backupcloud.site/" target="_blank">&copy; <span id="year"></span> DocMark | By Backup Cloud. Todos os direitos reservados.</a></p></p>
    
  </footer>
  
  <script>
    document.getElementById("year").textContent = new Date().getFullYear();
  </script>
</body>
</html>
