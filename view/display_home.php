<?php ob_start(); ?>

<div class="container">

        <h1>Site de Recettes !</h1>
        
            <?php foreach(get_recipe_list() as $recipe) : ?>
                <article>
                    <h3><a href="./index.php?action=read&id=<?php echo($recipe['recipe_id']); ?>"><?php echo($recipe['title']); ?></a></h3>
                    <div><?php echo($recipe['recipe']); ?></div>
                    <i><?php echo(display_author($recipe['author'], get_users())); ?></i>
                    
                    <?php if(!empty($_SESSION['LOGGED_USER']) && $_SESSION['LOGGED_USER'] == $recipe['author']): ?>
                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item"><a class="link-warning" href="index.php?action=update&id=<?php echo($recipe['recipe_id']); ?>">Editer l'article</a></li>
                            <li class="list-group-item"><a class="link-danger" href="index.php?action=delete&id=<?php echo($recipe['recipe_id']); ?>">Supprimer l'article</a></li>    
                        </ul>
                    <?php endif; ?>
                    
                </article>
            <?php endforeach ?>
    </div>

<?php $content = ob_get_clean(); ?>