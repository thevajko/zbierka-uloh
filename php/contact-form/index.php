<?php
function getParam($name) : string|null {
    return isset($_POST[$name]) ? htmlspecialchars(trim($_POST[$name]), ENT_QUOTES) : null;
}

function printErrorMessage($errors, $key) : string {
    if (isset($errors[$key])) {
        return "<span class='form-error'>{$errors[$key]}</span>";
    }
    return "";
}

$errors = [];
$isPost = $_SERVER['REQUEST_METHOD'] == "POST";
if ($isPost) {
    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors['name'] = "Meno musí byť zadané.";
    }
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $errors['email'] = "Emailová adresa nieje platná.";
    }
    $content = trim($_POST['content']);
    if (empty($content)) {
        $errors['content'] = "Musíte vyplniť správu.";
    }

    if (empty($errors)) {
        mail(
            "kontaktna.adresa@mojmail.sk", 
            "Sprava z kontaktneho formulara", 
            "Odosielateľ: $name<$email>\n$content", 
            "From: my@myserver.sk\r\nReply-To: $name<$email>");
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Kontaktný formulár</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style type="text/css">
        .contact-form input, .contact-form label, .contact-form textarea {
            display: block;
        }

        .contact-form label {
            margin-top: 10px;
        }

        .contact-form textarea {
            height: 200px;
        }

        .form-error {
            color: red;
            font-size: small;
        }
    </style>
</head>
<body>
    
<h1>Napíšte nám</h1>

<?php if ($isPost && empty($errors)) {
?>
    Ďakujeme za vašu správu.
<?php
}
else { ?>
<div class="contact-form">
    <form method="POST">

        <label for="name">Meno</label>
        <input type="text" id="name" name="name" placeholder="Vaše meno" value="<?=getParam("name")?>">
        <?=printErrorMessage($errors, "name")?>

        <label for="email">Emailová adresa</label>
        <input type="email" id="email" name="email" placeholder="some@mail.ccc" value="<?=getParam("email")?>">
        <?=printErrorMessage($errors, "email")?>

        <label for="content">Správa</label>
        <textarea id="content" name="content" placeholder="Text správy..."><?=getParam("content")?></textarea>
        <?=printErrorMessage($errors, "content")?>

        <input type="submit" value="Odošli">

    </form>
</div>
<?php } ?>
</body>
</html>