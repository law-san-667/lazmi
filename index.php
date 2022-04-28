<?php
    session_start();

    $rootPath = $_SERVER['DOCUMENT_ROOT'];
    $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    

    require('model/model.php');
    require('controller/controller.php');
    
    if (!empty($_GET['action'])) {
        $action = htmlspecialchars($_GET['action']);

        if($action == 'home')
        {
            display_home();
        }
        elseif ($action == 'contact') {
            display_contact();
        }
        elseif($action == 'add')
        {
            add_controller();
        }
        elseif ($action == 'update') {
            update_controller();
        }
        elseif ($action == 'read') {
            read_recipe();
        }
        elseif($action == 'visit'){
            display_visit_page();
        }
        elseif ($action == 'delete') {
            delete_controller();
        }
        else {
            display_home();
        }
    }
    elseif (!empty($_GET['request'])) {
        $request = htmlspecialchars($_GET['request']);

        if ($request == 'delete') {
            delete_recipe();
        }
        elseif ($request == 'update') {
            update_recipe();
        }
        elseif ($request == 'add') {
            create_recipe();
        }
        elseif ($request == 'contact') {
            submit_contact();
        }
        elseif ($request == 'comment') {
            create_comment();
        }
        elseif ($request == 'login') {
            check_login();
        }
        else {
            display_home();
        }
    }
    else {
        display_home();
    }

    require('view/display_home.php');