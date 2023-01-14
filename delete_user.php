<?php
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
include("includes/header.php");
confirm_is_admin();

if (isset($_POST['submit'])) {
    $username = $_POST['user'];
    $query = "DELETE FROM users_in_roles where user_id = (SELECT id FROM users WHERE username = ?);";
    $query2 = "DELETE FROM users WHERE username = ?";

    $stmt = $link->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    $stmt2 = $link->prepare($query2);
    $stmt2->bind_param('s', $username);
    $stmt2->execute();
    $stmt2->store_result();

    if ($stmt->error) {
        die('Database query failed: ' . $stmt->error);
    }

    $deletionWasSuccessful = $stmt->affected_rows > 0 ? true : false;
    if ($deletionWasSuccessful) {
        echo "User deleted.";
    } else {
        echo "Delete user failed";
    }
}
?>

<form action="delete_user.php" method="post">
    <select id="User" name="user">
        <option value="0">--Choose a user to delete--</option>
        <?php
        $stmt = $link->prepare("SELECT username FROM users");
        $stmt->execute();

        if ($stmt->error) {
            die("Database query failed: " . $stmt->error);
        }

        $stmt->bind_result($username);
        while ($stmt->fetch()) {
            echo "<option value=\"$username\">$username</option>\n";
        }
        ?>
    </select>
    </li>
    </ol>
    <input type="submit" name="submit" value="Delete" />
    <p>
        <a href="index.php">Return to index</a>
    </p>
    </fieldset>
</form>
</div>
</div>