<?php
function nahodnaPozicia() {
    return rand(0, 100)."%";
}

function nahodnaFarba() {
    //$farby = ["red", "green", "blue", "yellow", "pink", "cyan", "purple", "black", "grey", "violet"];
    //return $farby[rand(0, count($farby))];
    return sprintf('#%06X', rand(0, 0xFFFFFF));
}
?>

<html>
    <head>
        <style type="text/css">
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
        <div style="top: <?=nahodnaPozicia()?>; left: <?=nahodnaPozicia()?>; background: <?=nahodnaFarba()?>"></div>
        <?php } ?>
    </body>
</html>
