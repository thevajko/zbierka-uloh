<?php

include "Blog.php";
include "storage/IStorage.php";
include "storage/FileStorage.php";
include "storage/DbStorage.php";

$storage = new DbStorage();
//$storage = new FileStorage();

$action = isset($_GET['action']) ? $_GET['action'] : "list";

// store logic
if (isset($_POST['title'])) {

    $newBlog= new Blog();

    if (isset($_POST['id'])) {
        $newBlog->setId($_POST['id']);
    }

    $newBlog->setTitle($_POST['title']);
    $newBlog->setBody($_POST['body']);
    $storage->Store($newBlog);
    // redirect to home
    header('Location: /');
}

if (isset($_GET['delete'])){
    $storage->Remove($_GET['delete']);
    header('Location: /');
}

?><html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1>Super BLOG <a href="?action=form" class="btn btn-success">Pridať</a></h1>
                </div>
            </div>
            <?php include "pages/".$action.".php" ?>
        </div>
    </body>
</html>