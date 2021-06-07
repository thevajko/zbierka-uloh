<?php
require 'Game.php';
$game = new Game();
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Hra Obesenec</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Hra Obesenec</h1>
<h2>Hracie pole</h2>
<div class="play_ground">
    <?= $game->play(); ?>
</div>
<div class="attempts">
    Počet neúspešných pokusov: <?= $game->getFailedAttempts() ?>
</div>
<div class="hangman_picture">
    <img src="img/<?= $game->getFailedAttempts() ?>.png" alt="Obesenec">
</div>
<div class="results">
    <?= $game->getGameResult() ?>
</div>
<?php
if ($game->getGameResult() == '') {
?>
    <h2>Klávesnica</h2>
    <div class="keyboard_container">
        <?= $game->getKeyboard("7")->getKeyboardLayout(); ?>
    </div>
<?php
}
?>
<div>
    <br><a href="?">Začať znovu</a>
</div>
</body>
</html>
