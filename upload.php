<?php

require_once("includes/session.php");
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
require_once("includes/header.php");
confirm_is_logged_in();

if (isset($_POST["submit"])) {

    $project_name = $_POST["project_name"];
    $project_year = $_POST["project_year"];
    $category = $_POST["category"];
    $project_student = $_POST["project_student"];
    $abstract = $_POST["abstract"];
    $creator_id = $_SESSION['userid'];

    //upload video link and create embed video link
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

    $sql = "INSERT INTO projects (project_name, project_year, category, project_student, abstract, video_link, video_embed_link, photo, attached_file, creator_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $link->prepare($sql);
    $stmt->bind_param('sisssssssi', $project_name, $project_year, $category, $project_student, $abstract, $video_link, $video_embed_link, $photo_path, $attached_file_path, $creator_id);
    $stmt->execute();
    $stmt->store_result();

    $creation_was_successful = $stmt->affected_rows == 1 ? true : false;
    if ($creation_was_successful) {
        echo "Project uploaded.";
        $stmt->close();
    } else {
        echo "Upload project failed.";
        mysqli_close($link);
    }
}

?>


<form method="post" action="upload.php" enctype="multipart/form-data">
    Project Name:<input type="text" name="project_name" required /><br>
    Project Year:<input type="text" name="project_year" pattern="\d{4}" maxlength="4" title="Please enter correct year format, ie: 2022" required /><br>
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
    Student(s):<input type="text" name="project_student" required /><br>
    Abstract: <br /><textarea name="abstract" required rows="10" cols="50"></textarea><br>
    Youtube Video Link:<input type="text" name="video_link" /><br>
    Project Photo:<input type="file" name="photo" /><br>
    Project File:<input type="file" name="attached_file" /><br>
    <input type="submit" name="submit" value="Submit">
</form>