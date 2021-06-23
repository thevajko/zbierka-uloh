<style>
    label {
        display: block;
    }

    .form-errors {
        display: block;
        color: red;
        font-size: small;
    }
</style>

<?php

require_once 'Form/Form.php';

$form = new Form(["meno" => "test"]);

$form->addText("meno", "Meno")
    ->required();
$form->addText("priezvisko", "Priezvisko")
    ->required();
$form->addText("mail", "Emailová adresa", "email")
    ->required();
$form->addNumber("vek", "Vek");
$form->addPassword("heslo", "Heslo");
$form->addTextArea("poznamka", "Poznámka");

$form->addSelect("oci", "Farba očí", ["g" => "Zelená", "r" => "Červená", "b" => "Modrá"]);

$form->addSubmit("Odošli");

if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData();
    var_dump($data);
}

$form->render();


$connection = ssh2_connect('co5storage.uniza.sk', 22);
ssh2_auth_password($connection, 'mamoscreen', 'b5RuBqSpL');

$sftp = ssh2_sftp($connection);

$handle = opendir("ssh2.sftp://$connection");
echo "Directory handle: $handle\n";
echo "Entries:\n";
while (false != ($entry = readdir($handle))){
    echo "$entry\n";
}

