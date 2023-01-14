<?php
require_once("includes/session.php");
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
require_once("includes/header.php");

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username FROM users WHERE username = ? AND password = SHA(?) LIMIT 1";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($_SESSION['userid'], $_SESSION['username']);
        $stmt->fetch();
        header("Location: index.php");
    } else {
        echo "Username/password incorrect";
    }
}


?>
<div id="main">
    <form action="login.php" method="post">
        <fieldset>
            <legend>Log in</legend>
            <ol>
                <li>
                    <label for="username">Username</label>
                    <input type="text" name="username" value="" id="username" />
                </li>
                <li>
                    <label for="password">Password</label>
                    <input type="password" name="password" value="" id="password" />
                </li>
            </ol>
            <input type="submit" name="submit" value="Log In" /><br>
        </fieldset>
    </form>