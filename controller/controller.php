<?php

function display_home()
{
    if (!empty($_SESSION['LOGGED_USER'])) {
        require('view/display_home.php');        
    }
    else {
        require('view/display_login.php');
    }

    require('view/template.php');
}

function display_visit_page()
{
    require('view/display_home.php');
    require('view/template.php');
}

function add_controller()
{
    check_user();
    require('view/new_recipe_form.php');
    require('view/template.php');
}

function read_recipe()
{
    $recipe = get_recipe_comment();
    $users = get_users();
    require('view/display_recipe.php');
    require('view/template.php');
}

function update_controller()
{
    check_user();
    $recipe = get_recipe();
    require('view/update_recipe_form.php');
    require('view/template.php');
}

function delete_controller()
{
    check_user();
    require('view/delete_confirm.php');
    require('view/template.php');
}

function display_contact()
{
    require('view/contact.php');
    require('view/template.php');
}