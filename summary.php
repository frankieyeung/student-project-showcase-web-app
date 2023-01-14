<?php
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
require_once("includes/header.php");
confirm_is_admin();

//query projects
$sql = "SELECT id, project_name, project_year, category, project_student, creator_id FROM projects";
$result = $link->query($sql);
$row_count = $result->num_rows;
$field_count = $result->field_count;
echo "Total Numbers of projects: $row_count";
echo "<table border=1><tr>";
while ($field_info = $result->fetch_field()) {
    echo "<th>$field_info->name</th>";
}
echo "</tr>";
while ($rows = $result->fetch_row()) {
    echo "<tr>";
    for ($i = 0; $i < $field_count; $i++) {
        echo "<td>" . $rows[$i] . "</td>";
    }
}
echo "</table><br>";

//query users
$sql = "SELECT id, username, student_name, programme, grad_year, email_address FROM users";
$result = $link->query($sql);
$row_count = $result->num_rows;
$field_count = $result->field_count;
echo "Total Numbers of users: $row_count";
echo "<table border=1><tr>";
while ($field_info = $result->fetch_field()) {
    echo "<th>$field_info->name</th>";
}
echo "</tr>";
while ($rows = $result->fetch_row()) {
    echo "<tr>";
    for ($i = 0; $i < $field_count; $i++) {
        echo "<td>" . $rows[$i] . "</td>";
    }
}
echo "</table><br>";

$result->close();

