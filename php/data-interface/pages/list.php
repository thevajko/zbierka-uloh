<?php
/** @var Blog[] $blogs */
/** @var IStorage $storage */
$blogs = $storage->GetAll();

foreach ($blogs as $blog) { ?>
    <div class="row">
        <div class="col">
            <hr>
            <h2><?php echo $blog->getTitle() ?>
                <a href="?action=form&edit=<?php echo $blog->getId() ?>" class="btn btn-warning">Edit</a>
                <a href="?delete=<?php echo $blog->getId() ?>" class="btn btn-danger">Delete</a>
            </h2>
            <p>
                <?php echo $blog->getBody() ?>
            </p>
        </div>
    </div>
<?php } ?>