<?php
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
include("includes/header.php");
confirm_is_logged_in();


if (isset($_POST['submit'])) {
    $project_id = $_POST['project_id'];
    if (is_admin()) {
        $query = "DELETE FROM projects WHERE id = ?";
    } else {
        $query = "DELETE FROM projects WHERE id = ? AND creator_id = $_SESSION[userid]";
    }
    $stmt = $link->prepare($query);
    $stmt->bind_param('d', $project_id );
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->error) {
        die('Database query failed: ' . $stmt->error);
    }
    $deletionWasSuccessful = $stmt->affected_rows > 0 ? true : false;
    if ($deletionWasSuccessful) {
        echo "Project deleted.";
        $stmt->close();
    } else {
        echo "Error";
        
    }
}
?>

<form action="delete.php" method="post">
    <label for="Project Name">Project Name:</label>
    <select id="project_id" name="project_id">
        <option value="0">--Choose project to delete--</option>
        <?php
        if (is_admin()) {
            $stmt = $link->prepare("SELECT id, project_name FROM projects");
        } else {
            $stmt = $link->prepare("SELECT id, project_name FROM projects WHERE creator_id = $_SESSION[userid]");
        }
        $stmt->execute();

        if ($stmt->error) {
            die("Database query failed: " . $stmt->error);
        }
        $stmt->bind_result($id, $project_name);
        while ($stmt->fetch()) {
            echo "<option value=\"$id\">$project_name</option>\n";
        }
        ?>
    </select>
    </li>
    </ol>
    <input type="submit" name="submit" value="Delete" />

    </fieldset>
</form>
</div>
</div>