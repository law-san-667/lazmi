<?php

function db_connect()
{
    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=we_love_food;port=3306','root','');
        $mysqlClient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
}

return $mysqlClient;
}    
       

function check_user()
{
    if (empty($_SESSION['LOGGED_USER'])) {
        throw new Exception('Il faut être authentifié pour ajouter des recettes');
    } 
}

function display_views()
{
    $monfichier = fopen($rootPath.'/P4C5/compteur.txt', 'r+');
    
    $pages_vues = fgets($monfichier); // On  lit la première ligne (nombre de pages vues)
    $pages_vues += 1; // On augmente de 1 ce nombre de pages vues
    fseek($monfichier, 0); // On remet le curseur au début du fichier
    fputs($monfichier, $pages_vues); // On écrit le nouveau nombre de pages vues
    
    fclose($monfichier);
    
    echo('<p class="d-flex justify-content-center">Cette page a été vue ' . $pages_vues . ' fois !</p>');
}

function submit_contact()
{
    $postData = $_POST;

    if (empty($postData['email']) || empty($postData['message']))
    {
        echo('Il faut un email et un message pour soumettre le formulaire.');
        header('location: index.php');
    }	
    
    $email = htmlspecialchars($postData['email']);
    $message = htmlspecialchars($postData['message']);
    
    header('location: index.php');
}



function display_recipe(array $recipe) : string
{
    $recipe_content = '';
    
    if ($recipe['is_enabled']) {
        $recipe_content = '<article>';
        $recipe_content .= '<h3>' . $recipe['title'] . '</h3>';
        $recipe_content .= '<div>' . $recipe['recipe'] . '</div>';
        $recipe_content .= '<i>' . $recipe['author'] . '</i>';
        $recipe_content .= '</article>';
    }
    
    return $recipe_content;
}

function display_author(string $authorEmail, array $users) : string
{
    for ($i = 0; $i < count($users); $i++) {
        $author = $users[$i];
        if ($authorEmail === $author['email']) {
            return $author['full_name'] . '(' . $author['age'] . ' ans)';
        }
    }

    return 'Non trouvé.';
}

function display_user(int $userId, array $users) : string
{
    for ($i = 0; $i < count($users); $i++) {
        $user = $users[$i];
        if ($userId === (int) $user['user_id']) {
            return $user['full_name'] . '(' . $user['age'] . ' ans)';
        }
    }
    
    return 'Non trouvé.';
}

function retrieve_id_from_user_mail(string $userEmail, array $users) : int
{
    foreach ($users as $user) {
        if ($userEmail === $user['email']) {
            return $user['user_id'];
        }
    }
    return 0;
}

function get_recipes(array $recipes, int $limit) : array
{
    $valid_recipes = [];
    $counter = 0;

    foreach($recipes as $recipe) {
        if ($counter == $limit) {
            return $valid_recipes;
        }
        
        $valid_recipes[] = $recipe;
        $counter++;
    }

    return $valid_recipes;
}

function create_comment()
{
    $mysqlClient = db_connect();
    $users = get_users();

    if ( !empty($_POST['comment']) && !empty($_POST['recipe_id']) && is_numeric($_POST['recipe_id'] ))
    {
        if (empty($_SESSION['LOGGED_USER'])) {
            header('location: index.php');
       }
       
       $comment = htmlspecialchars($_POST['comment']);
       $recipeId = htmlspecialchars($_POST['recipe_id']);
       
       $insertRecipe = $mysqlClient->prepare('INSERT INTO comments(comment, recipe_id, user_id) VALUES (:comment, :recipe_id, :user_id)');
       $insertRecipe->execute([
           'comment' => $comment,
           'recipe_id' => $recipeId,
           'user_id' => retrieve_id_from_user_mail($_SESSION['LOGGED_USER'], $users),
       ]);
       header('location:index.php?action=read&id='.$_POST['recipe_id']);
    }else header('location: index.php?action=read&id='.$_POST['recipe_id']);

    
}
function delete_recipe()
{
    $mysqlClient = db_connect();

    if (!empty($_POST['id']))
    {
        $id = $_POST['id'];

        $deleteRecipeStatement = $mysqlClient->prepare('DELETE FROM recipes WHERE recipe_id = :id');
        $deleteRecipeStatement->execute([
            'id' => $id,
        ]);

        header('location: index.php');
    }else echo 'something went wrong'; 

}

function create_recipe()
{

    $mysqlClient = db_connect();

    if ( !isset($_POST['title']) || !isset($_POST['recipe']) )
    {
        echo('Il faut un titre et une recette pour soumettre le formulaire.');
        header('Location: '.$rootUrl.'/P4C5/index.php?action=add');
    }	

    $title = htmlspecialchars($_POST['title']);
    $recipe = htmlspecialchars($_POST['recipe']);

    $insertRecipe = $mysqlClient->prepare('INSERT INTO recipes(title, recipe, author, is_enabled) VALUES (:title, :recipe, :author, :is_enabled)');
    $insertRecipe->execute([
        'title' => $title,
        'recipe' => $recipe,
        'author' => $_SESSION['LOGGED_USER'],
        'is_enabled' => 1,
    ]);
    header('Location: '.$rootUrl.'/P4C5/index.php');
    
}

function update_recipe()
{
    $mysqlClient = db_connect();

    if ( !empty($_POST['id']) && !empty($_POST['title']) && !empty($_POST['recipe']) )
        {
            $id = htmlspecialchars($_POST['id']);
            $title = htmlspecialchars($_POST['title']);
            $recipe = htmlspecialchars($_POST['recipe']);
            
            $insertRecipeStatement = $mysqlClient->prepare('UPDATE recipes SET title = :title, recipe = :recipe WHERE recipe_id = :id');
            $insertRecipeStatement->execute([
                'title' => $title,
                'recipe' => $recipe,
                'id' => $id,
            ]);
            header('Location: index.php');
        }else header('location: index.php');	
    
}

function get_recipe_comment(): array
{
    $mysqlClient = db_connect();

    if (empty($_GET['id']) && is_numeric($_GET['id']))
    {
        echo('La recette n\'existe pas');
        header('Location: '.$rootUrl.'/P4C5/index.php');
    }	

    $recipeId = $_GET['id'];

    $retrieveRecipeWithCommentsStatement = $mysqlClient->prepare('SELECT * FROM recipes r LEFT JOIN comments c on r.recipe_id = c.recipe_id WHERE r.recipe_id = :id ');
    $retrieveRecipeWithCommentsStatement->execute([
        'id' => $recipeId,
    ]);

    $recipeWithComments = $retrieveRecipeWithCommentsStatement->fetchAll(PDO::FETCH_ASSOC);

    $recipe = [
        'recipe_id' => $recipeWithComments[0]['recipe_id'],
        'title' => $recipeWithComments[0]['title'],
        'recipe' => $recipeWithComments[0]['recipe'],
        'author' => $recipeWithComments[0]['author'],
        'comments' => [],
    ];
    
    foreach($recipeWithComments as $comment) {
        if (!is_null($comment['comment_id'])) {
            $recipe['comments'][] = [
                'comment_id' => $comment['comment_id'],
                'comment' => $comment['comment'],
                'user_id' => (int) $comment['user_id'],
            ];
        }
    }

    
    return $recipe;
}

function get_recipe(): array
{
    $mysqlClient = db_connect();

    if (!isset($_GET['id']) && is_numeric($_GET['id']))
    {
        echo('Il faut un identifiant de recette pour le modifier.');
        header('Location: '.$rootUrl.'/P4C5/index.php');
    }	

    $id = htmlspecialchars($_GET['id']);
    
    $retrieveRecipeStatement = $mysqlClient->prepare('SELECT * FROM recipes WHERE recipe_id = :id');
    $retrieveRecipeStatement->execute([
        'id' => $id,
    ]);

    $recipe = $retrieveRecipeStatement->fetch(PDO::FETCH_ASSOC);

    return $recipe;
}

    function get_users(): array
    {
        $mysqlClient = db_connect();

        $usersStatement = $mysqlClient->prepare('SELECT * FROM users');
        $usersStatement->execute();
        $users = $usersStatement->fetchAll();
    
        return $users;
    }
    
function get_recipe_list()
{
    
        $mysqlClient = db_connect();

        $recipesStatement = $mysqlClient->prepare('SELECT * FROM recipes WHERE is_enabled is TRUE');
        $recipesStatement->execute();
        $recipes = $recipesStatement->fetchAll();
    
        return $recipes;
    }

function check_login()
{
    $users = get_users();

    if (!empty($_POST['email']) &&  !empty($_POST['password'])) {
        foreach ($users as $user) {
            if ( $user['email'] == $_POST['email'] && $user['password'] == $_POST['password']) {
                $_SESSION['LOGGED_USER'] = $_POST['email'];
                header('location: index.php?ok=ok');
            }
        }
        header('location: index.php?ah=lala');
    }
    else
        header('location: index.php?action:visit');
}
