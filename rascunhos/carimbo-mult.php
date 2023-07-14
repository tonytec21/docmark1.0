<?php
if (isset($_POST['submit'])) {
    $uploadDir = 'upload/';
    $newImageDir = 'destino/';

    $carimbadas = array();

    foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
        $fileName = $_FILES['image']['name'][$key];
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmp_name, $filePath)) {
            $image = imagecreatefromjpeg($filePath);

            $textColor = imagecolorallocate($image, 0, 0, 0);

            $text = $_POST['text'];

            $text = "CNM: " . $text;

            $font = 'fonte/arial.ttf';
            $fontSize = 14;

            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);

            $textWidth = strlen($text) * imagefontwidth($fontSize);
            $x = $imageWidth - $textWidth - 110;
            $y = 20 + imagefontheight($fontSize);

            imagettftext($image, $fontSize, 0, $x, $y, $textColor, $font, $text);

            $newFileName = '' . $fileName;
            $newFilePath = $newImageDir . $newFileName;

            imagejpeg($image, $newFilePath);

            imagedestroy($image);

            $carimbadas[] = $newFileName;

            unlink($filePath);
        }
    }

    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="carimbadas.zip"');
    header('Pragma: no-cache');

    $zip = new ZipArchive;
    $zip->open('carimbadas.zip', ZipArchive::CREATE);

    foreach ($carimbadas as $carimbada) {
        $zip->addFile($newImageDir . $carimbada, $carimbada);
    }

    $zip->close();

    readfile('carimbadas.zip');

    unlink('carimbadas.zip');
}
?>

<style>
    h1 {
        color: #333;
        text-align: center;
        padding: 20px 0;
        font-family: Arial, Helvetica, sans-serif;
    }

    form {
        background-color: #fff;
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form input[type="file"],
    form input[type="text"],
    form input[type="submit"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        color: #555;
    }

    form input[type="submit"] {
        background-color: #4caf50;
        color: #fff;
        cursor: pointer;
    }

    form input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>

<!DOCTYPE html>
<html>
<head>
    <title>Carimbo Digital</title>
</head>
<body>
    <h1>ADICIONAR CARIMBO DIGITAL</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="image[]" accept="image/jpeg" multiple>
        <input type="text" name="text" placeholder="Digite o texto do carimbo digital">
        <input type="submit" name="submit" value="Carimbar">
    </form>
</body>
</html>
