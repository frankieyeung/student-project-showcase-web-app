<?php

require_once("includes/session.php");
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
require_once("includes/header.php");

//get the value from previous PHP and then query and show
if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
    $query = "SELECT id, project_name, project_year, category, project_student, abstract, video_embed_link, photo, attached_file FROM projects WHERE id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param('d', $project_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($project_id, $project_name, $project_year, $category, $project_student, $abstract, $video_embed_link, $photo, $attached_file);
    $stmt->fetch();


    echo "<h1>$project_name</h1>";
    echo "<h2>Project Year: $project_year</h2>";
    echo "<h2>Category: $category</h2>";
    echo "<h3>Student(s): $project_student</h3>";
    echo "<p>Abstract: $abstract</p>";
    echo "<p>$video_embed_link</p>";
    if ($photo) {
        echo "<p><img src=$photo alt=$project_name></p>";
    }
    if ($attached_file) {
        echo "Download Project File: <a href=$attached_file>" . basename($attached_file) . "</a>";
    }

    $stmt->close();

}