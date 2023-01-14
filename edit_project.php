<?php
require_once("includes/session.php");
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
include("includes/header.php");
confirm_is_logged_in();

$project_id = null;
$project_name = null;
$project_year = null;
$category = null;
$project_student = null;
$abstract = null;
$video_link = null;

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
    $query = "SELECT project_name, project_year, category, project_student, abstract, video_link FROM projects WHERE id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param('d', $project_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->error) {
        die('Error: ' . $stmt->error);
    }

    $pageExists = $stmt->num_rows == 1;
    if ($pageExists) {
        $stmt->bind_result($project_name, $project_year, $project_category, $project_student, $abstract, $video_link);
        $stmt->fetch();
    } else {
        header("Location: index.php");
    }
} else if (isset($_POST["submit"])) {

    $project_id = $_POST['project_id'];
    $project_name = $_POST["project_name"];
    $project_year = $_POST["project_year"];
    $category = $_POST["category"];
    $project_student = $_POST["project_student"];
    $abstract = $_POST["abstract"];
    $video_link = $_POST["video_link"];

    $video_link = $_POST["video_link"];
    if (str_contains($_POST["video_link"], "watch?v=")) {
        $video_embed_link = str_replace("watch?v=", "embed/", $_POST["video_link"]);
        $video_embed_link = '<iframe width="560" height="315" src="' . $video_embed_link . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    } else {
        $video_embed_link = '<iframe width="560" height="315" src="' . $_POST["video_link"] . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    }

    $target_dir = "uploads/";

    //Upload photo
    if (file_exists($_FILES["photo"]["tmp_name"])) {
        $photo = trim(addslashes(basename($_FILES["photo"]["name"])));
        $photo = str_replace(' ', '_', $photo);
        $photo_path = $target_dir . $photo;
        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_path)) {
            echo "Upload photo failed.<br>";
        }
    }

    //Uplaod attached file
    if (file_exists($_FILES["attached_file"]["tmp_name"])) {
        $attached_file = trim(addslashes(basename($_FILES["attached_file"]["name"])));
        $attached_file = str_replace(' ', '_', $attached_file);
        $attached_file_path = $target_dir . $attached_file;
        if (!move_uploaded_file($_FILES["attached_file"]["tmp_name"], $attached_file_path)) {
            echo "Upload file failed.<br>";
        }
    }

    $sql = "UPDATE projects SET project_name = ?, project_year = ?, category = ?, project_student = ?, abstract = ?, video_link = ?, video_embed_link =?, photo = ?, attached_file = ? WHERE id = ?";

    $stmt = $link->prepare($sql);
    $stmt->bind_param('sisssssssi', $project_name, $project_year, $category, $project_student, $abstract, $video_link, $video_embed_link, $photo_path, $attached_file_path, $project_id);
    $stmt->execute();
    $stmt->store_result();

    $creation_was_successful = $stmt->affected_rows == 1 ? true : false;
    if ($creation_was_successful) {
        echo "Project edited.";
        $stmt->close();
    } else {
        echo "Edit project failed.";
        mysqli_close($link);
    }
}
?>

<form method="post" action="edit_project.php" enctype="multipart/form-data">
    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
    Project Name:<input type="text" name="project_name" value="<?php echo $project_name; ?>" equired/><br>
    Project Year:<input type="text" name="project_year" value="<?php echo $project_year; ?>" pattern="\d{4}" maxlength="4" title="Please enter correct year format, ie: 2022" required/><br>
    Category:
    <select name="category">
    <option value="art_and_design">Art and Design</option>
        <option value="built_environment">Built Environment</option>
        <option value="computing">Computing</option>
        <option value="creative_media_technology">Creative Media Technology</option>
        <option value="engineering">Engineering</option>
        <option value="humanities">Humanities</option>
        <option value="performing_arts">Performing Arts</option>
        <option value="research">Research</option>
        <option value="science">Science</option>
    </select><br>
    Student(s):<input type="text" name="project_student" value="<?php echo $project_student; ?>" required/><br>
    Abstract: <br /><textarea name="abstract" required rows="10" cols="50"><?php echo $abstract; ?></textarea><br>
    Youtube Video Link:<input type="text" name="video_link" value="<?php echo $video_link; ?>" /><br>
    Project Photo:<input type="file" name="photo" /><br>
    Project File:<input type="file" name="attached_file" /><br>
    <input type="submit" name="submit" value="Update">
</form>