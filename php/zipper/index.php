<?php
require "Uploader.php";
$uploader = new Uploader("uploads");
$result = "";
if (isset($_FILES['userfile'])) {
    $result = $uploader->saveUploadedFile();
}
if (isset($_POST['zip'])) {
    $uploader->zipAndDownload();
    die();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zipovač súborov</title>
    <script>
        function deleteFiles() {
            document.querySelectorAll('#files > *').forEach( (item) => item.style.display = 'none');
            let p = document.createElement("p")
            document.getElementById('files').append(p);
            p.innerHTML = 'Súbory boli stiahnuté.';
        }
    </script>
</head>
<body>
<h1>Zipovač súborov</h1>
<?php
    if ($result != '') {
?>
    <p style="color: red"><?= $result ?></p>
<?php
    }
?>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
    Súbor: <input type="file" name="userfile">
    <input type="submit" value="Poslať súbor">
</form>
<br>

<?php
$fileList = $uploader->getFilesList();
?>
<p><b>Zoznam súborov:</b></p>
<div id="files">
    <?php
    if (!empty($fileList)) {
        ?>
        <p>
            <?php
            foreach ($fileList as $fileName) {
                echo $fileName . '<br>';
            }
            ?>
        </p>
        <form method="post" onsubmit="deleteFiles()">
            <input type="submit" name="zip" value="Zipovať!">
        </form>
        <?php
    } else {
        ?>
        <p>Zatiaľ neboli nahraté žiadne súbory.</p>
        <?php
    }
    ?>
</div>
</body>
</html>

