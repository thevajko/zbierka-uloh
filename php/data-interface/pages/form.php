<?php
/** @var Blog $blog */
/** @var IStorage $storage */

if (isset($_GET['edit'])) {
    $blog = $storage->GetById($_GET['edit']);
}

$title = isset($blog) ? $blog->getTitle() : "";
$body = isset($blog) ? $blog->getBody() : "";

?>
<div class="row">
    <div class="col">
        <form method="post" action="" enctype="application/x-www-form-urlencoded">
            <div class="mb-3">
                <label for="title-input" class="form-label">Title</label>
                <input type="text" class="form-control" value="<?php echo $title ?>" name="title" id="title-input">
            </div>
            <div class="mb-3">
                <label for="body-input" class="form-label">Example textarea</label>
                <textarea class="form-control" name="body"  id="body-input" rows="5"><?php echo $body?></textarea>
            </div>
            <?php if(isset($blog)) { ?>
                <input type="hidden" value="<?php echo $blog->getId() ?>" name="id">
            <?php } ?>
            <div class="text-end">
                <a href="/" class="btn btn-warning" >Späť</a>
                <input type="submit" class="btn btn-success" value="Odoslat">
            </div>
        </form>
    </div>
</div>