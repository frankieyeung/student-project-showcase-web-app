<?php
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
include("includes/header.php");
confirm_is_logged_in();

if (isset($_POST['submit'])) {
    $project_id = $_POST['project_id'];
    if (is_admin()) {
        $query = "SELECT id FROM projects WHERE id = ?";
    } else {
        $query = "SELECT id FROM projects WHERE id = ? AND creator_id = $_SESSION[userid]";
    }
    $stmt = $link->prepare($query);
    $stmt->bind_param('d', $project_id );
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->error) {
        die('Database query failed: ' . $stmt->error);
    }

    $page_exists = $stmt->num_rows == 1;
    if ($page_exists) {
        header("Location: edit_project.php?project_id=$project_id");
    } else {
        echo "Error";
    }
}
?>


<form action="select_project_to_edit.php" method="post">
    <label for="Project Name">Project Name:</label>
    <select id="project_id" name="project_id">
        <option value="0">--Choose project to edit--</option>
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
        $stmt->bind_result($project_id, $project_name);
        while ($stmt->fetch()) {
            echo "<option value=\"$project_id\">$project_name</option>\n";
        }
        ?>
    </select>
    </li>
    </ol>
    <input type="submit" name="submit" value="Edit" />

    </fieldset>
</form>