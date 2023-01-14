<?php require_once("includes/session.php"); ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>School Project Showcase</title>
    <link href="styles/styles.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<div class="navbar">
<?php
if (logged_in()) {


    if (is_admin()) {
        echo '<a class="active" href="index.php">Index</a>';
        echo '<a href="upload.php">Upload project</a>';
        echo '<a href="select_project_to_edit.php">Edit project</a>';
        echo '<a href="delete.php">Delete project</a>';
        echo '<a href="logout.php">Log out</a>';
        echo '<a href=create_user.php>Create user</a>';
        echo '<a href=delete_user.php>Delete user</a>';
        echo '<a href=summary.php>Summary</a>';
    } else {
        echo '<a class="active" href="index.php">Index</a>';
        echo '<a href="upload.php">Upload project</a>';
        echo '<a href="delete.php">Delete project</a>';
        echo '<a href="select_project_to_edit.php">Edit project</a>';
        echo '<a href="logout.php">Log out</a>';
    }
} else {
    echo '<a class="active" href="index.php">Index</a>';
    echo '<a href="login.php">Log In</a>';
}
?>

</div>