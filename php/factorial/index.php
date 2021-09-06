<?php
function factorial($number)
{
    $result = 1;
    while (--$number > 0) {
        $result *= $number + 1;
    }
    return $result;
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
        <li><?php echo $i . "! = " . factorial($i) ?></li>
    <?php } ?>
</ul>
</body>
</html>
