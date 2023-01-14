<?php
require_once("includes/project_showcase_config.php");
require_once("includes/connect_db.php");
include("includes/header.php");
confirm_is_admin();

//insert HTML forms values into sql
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $student_name = $_POST['student_name'];
    $programme = $_POST['programme'];
    $grad_year = $_POST['grad_year'];
    $email_address = $_POST['email_address'];

    $query = "INSERT INTO users (username, password, student_name, programme, grad_year, email_address) VALUES (?, SHA(?), ?, ?, ?, ?)";

    $stmt = $link->prepare($query);
    $stmt->bind_param('ssssis', $username, $password, $student_name, $programme, $grad_year, $email_address);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->error) {
        die('Username was used.' . $stmt->error);
    }

    //insert role
    $creation_was_successful = $stmt->affected_rows == 1 ? true : false;
    if ($creation_was_successful) {
        $user_id = $stmt->insert_id;

        $add_to_user_role = "INSERT INTO users_in_roles (user_id, role_id) VALUES (?, ?)";
        $add_user_to_user_role_statement = $link->prepare($add_to_user_role);

        $user_role_id = 2;
        $add_user_to_user_role_statement->bind_param('dd', $user_id, $user_role_id);
        $add_user_to_user_role_statement->execute();
        $add_user_to_user_role_statement->close();

        echo "User created.";
    } else {
        echo "Create user failed.";
    }
}
?>

<h2>Create a user</h2>
<form action="create_user.php" method="post"><br>
    Username:<input type="text" name="username" required/><br>
    Password:<input type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required><br>
    Student Name:<input type="text" name="student_name" required/><br>
    Programme:<input type="text" name="programme" required/><br>
    Grad Year:<input type="text" name="grad_year" pattern="\d{4}" maxlength="4" title="Please enter correct year format, ie: 2022" required/><br>
    Email Address:<input type="text" name="email_address" required/><br>
    <input type="submit" name="submit" value="Create" />
</form>