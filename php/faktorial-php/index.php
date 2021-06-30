<?php
function fakt($cislo)
{
    $vysledok = 1;
    while (--$cislo > 0) {
        $vysledok *= $cislo + 1;
    }
    return $vysledok;
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Faktoriál</title>
</head>
<body>
<ul>
    <?php for ($i = 0; $i < 10; $i++) { ?>
        <li><?php echo $i . "! = " . fakt($i) ?></li>
    <?php } ?>
</ul>
</body>
</html>
