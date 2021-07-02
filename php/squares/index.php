<?php
function randPosition()
{
    return rand(0, 100) . "%";
}

function randColor()
{
    //$colors = ["red", "green", "blue", "yellow", "pink", "cyan", "purple", "black", "grey", "violet"];
    //return $colors[rand(0, count($colors))];
    return sprintf('#%06X', rand(0, 0xFFFFFF));
}

?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Generovanie štvorčekov</title>
    <style>
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            padding: 0;
            margin: 0;
        }

        div {
            position: absolute;
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>
<?php for ($i = 0; $i < 2000; $i++) { ?>
    <div style="top: <?= randPosition() ?>; left: <?= randPosition() ?>; background: <?= randColor() ?>"></div>
<?php } ?>
</body>
</html>
