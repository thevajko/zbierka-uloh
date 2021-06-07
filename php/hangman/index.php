<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Hra Obesenec</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Hra Obesenec</h1>
<?php
require 'Game.php';
$game = new Game();
?>
<h2>Hracie pole</h2>
<div class="playGround">
    <?= $game->play(); ?>
</div>
<div>
    Počet neúspešných pokusov: <?= $game->getFailedAttempts() ?>.
</div>
<div>
    <img src="img/<?=$game->getFailedAttempts()?>.png" alt="Obesenec">
</div>
<div>
    <?=$game->getGameResult()?>
</div>
<h2>Klávesnica</h2>
<div>
    <?= $game->getKeyboard('7')->getKeyboardLayout(); ?>
</div>
<div>
    <br><a href="#">Začať znovu</a>
</div>
</body>
</html>
