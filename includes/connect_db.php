<?php  
    require_once ("includes/project_showcase_config.php");
    require_once ("includes/create_database.php");
    
    //create database connection object
    $link = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($link->connect_error)
    {
        die("Connect database failed: " . $link->connect_error);
    }

    create_db_content();
?>