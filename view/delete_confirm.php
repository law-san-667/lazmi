<?php ob_start(); ?>

<div class="container">

        <h1>Supprimer la recette ?</h1>
        <form action="<?php echo('index.php?request=delete'); ?>" method="POST">
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de la recette</label>
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo($_GET['id']); ?>">
            </div>
            
            <button type="submit" class="btn btn-danger">La suppression est définitive</button>
        </form>
        <br />
    </div>
<?php $content = ob_get_clean(); ?>