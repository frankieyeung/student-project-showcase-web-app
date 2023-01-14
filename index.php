<?php
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
require_once("includes/header.php");
?>
<h1>Project Showcase</h1>
<script src='jquery-3.6.1.min.js'></script>
<form method="get" action="index.php">
    Project Year:
    <select name="project_year">
        <option value="all">All</option>
        <option value="2020">2020</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
    </select><br>

    Category:
    <select name="category">
        <option value="all">All</option>
        <option value="art_and_design">Art and Design</option>
        <option value="built_environment">Built Environment</option>
        <option value="computing">Computing</option>
        <option value="creative_media_technology">Creative Media Technology</option>
        <option value="engineering">Engineering</option>
        <option value="humanities">Humanities</option>
        <option value="performing">Performing Arts</option>
        <option value="research">Research</option>
        <option value="science">Science</option>
    </select><br>
    <input type="submit" name="submit" value="Search">
</form>

<?php

if (isset($_GET["submit"])) {
    extract($_GET, EXTR_PREFIX_ALL, "get");
    $sql = "SELECT id, project_name, project_year, category, video_embed_link, photo FROM projects";
    $params = "";
    $matches = array();
    if($get_project_year != "all") {
        $matches[] = "project_year = $get_project_year";
    }
    if($get_category != "all") {
        $matches[] = "category = '$get_category'";
    }
    $matches = implode(" and ", $matches);

    $sql .= $matches ? " where $matches" : "";

    //echo $sql; 
    $stmt = $link->prepare($sql);
    //$stmt->bind_param('is', $post_project_year, $post_category);

    $stmt->execute();
    $stmt->bind_result($project_id, $project_name, $project_year, $category, $video_embed_link, $photo);
    while ($stmt->fetch()) {
        echo "<h2><a href=content.php?project_id=$project_id>$project_name<a></h2>
        <h2>$project_year</h2><h2>$category</h2>$video_embed_link<br><hr>";
    }
    $stmt->close();

} else {
    

    $sql = "SELECT id, project_name, project_year, category, video_embed_link, photo FROM projects";

    $stmt = $link->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($project_id, $project_name, $project_year, $category, $video_embed_link, $photo);
    while ($stmt->fetch()) {
        echo "<h2><a href=content.php?project_id=$project_id>$project_name<a></h2>
        <h2>$project_year</h2><h2>$category</h2>$video_embed_link<br><hr>";
    }
    $stmt->close();

}

?>