<?php ob_start(); ?>

    <h1>Mettre à jour <?php echo($recipe['title']); ?></h1>
        <form action="index.php?request=update" method="POST">
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de la recette</label>
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo($_GET['id']); ?>">
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Titre de la recette</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="title-help" value="<?php echo($recipe['title']); ?>">
                <div id="title-help" class="form-text">Choisissez un titre percutant !</div>
            </div>
            <div class="mb-3">
                <label for="recipe" class="form-label">Description de la recette</label>
                <textarea class="form-control" placeholder="Seulement du contenu vous appartenant ou libre de droits." id="recette_va" name="recipe">
                <?php echo strip_tags($recipe['recipe']); ?>
                </textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
        <br />
    </div>

<?php $content = ob_get_clean(); ?>