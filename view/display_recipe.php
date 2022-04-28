<?php ob_start(); ?>

<div class="container">

        <h1><?php echo($recipe['title']); ?></h1>
        <div class="row">
            <article class="col">
                <?php echo($recipe['recipe']); ?>
            </article>
            <aside class="col">
                <p><i>Contribuée par <?php echo($recipe['author']); ?></i></p>
            </aside>
        </div>

        <?php if(count($recipe['comments']) > 0): ?>
        <hr />
        <h2>Commentaires</h2>
        <div class="row">
            <?php foreach($recipe['comments'] as $comment): ?>
                <div class="comment">
                    <p><?php echo($comment['comment']); ?></p>
                    <i>(<?php echo(display_user($comment['user_id'], $users)); ?>)</i>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <hr />
        <?php if (!empty($_SESSION['LOGGED_USER'])) : ?>
            <?php include_once('view/display_comment_form.php'); ?>
        <?php endif; ?>
    </div>
<?php $content = ob_get_clean(); ?>
