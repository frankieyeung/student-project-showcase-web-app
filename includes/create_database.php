<?php
require_once("includes/project_showcase_config.php");

function create_db_content()
{
    global $link;
    $admin_role_id = 1;

    create_tables($link);
    create_roles($link, $admin_role_id);
    create_admin($link, $admin_role_id);
}

//create users, roles, users_in_roles, projects tables if not exists
function create_tables($link)
{
    $query_users = "CREATE TABLE IF NOT EXISTS users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255), password CHAR(40), student_name VARCHAR(255), programme VARCHAR(255), grad_year YEAR(4), email_address VARCHAR(255), PRIMARY KEY (id))";
    $link->query($query_users);

    $query_roles = "CREATE TABLE IF NOT EXISTS roles (id INT NOT NULL, name VARCHAR(50), PRIMARY KEY (id))";
    $link->query($query_roles);

    $query_users_in_roles = "CREATE TABLE IF NOT EXISTS users_in_roles (id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, role_id INT NOT NULL, ";
    $query_users_in_roles .= " PRIMARY KEY (id), FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (role_id) REFERENCES roles(id))";
    $link->query($query_users_in_roles);

    $query_projects = "CREATE TABLE IF NOT EXISTS projects (id INT NOT NULL AUTO_INCREMENT, project_name VARCHAR(255), project_year YEAR(4), category VARCHAR(255), project_student VARCHAR(255), abstract TEXT, video_link VARCHAR(255), video_embed_link VARCHAR(512), photo VARCHAR(255), attached_file VARCHAR(255), creator_id INT(11), PRIMARY KEY (id))";
    $link->query($query_projects);
}

function create_roles($link, $admin_role_id)
{
    $query_check_roles_exist = "SELECT id FROM roles WHERE id <= 2";
    $statement_check_roles_exist = $link->prepare($query_check_roles_exist);
    $statement_check_roles_exist->execute();
    $statement_check_roles_exist->store_result();
    if ($statement_check_roles_exist->num_rows == 0) {
        $query_insert_roles = "INSERT INTO roles (id, name) VALUES ($admin_role_id, 'admin'), (2, 'user')";
        $statement_inser_roles = $link->prepare($query_insert_roles);
        $statement_inser_roles->execute();
    }
}

function create_admin($link, $admin_role_id)
{

    $default_admin_username = DEFAULT_ADMIN_USERNAME;
    $default_admin_password = DEFAULT_ADMIN_PASSWORD;

    $query_check_admin_exists = "SELECT id FROM users WHERE username = ? LIMIT 1";
    $statement_check_admin_exists = $link->prepare($query_check_admin_exists);
    $statement_check_admin_exists->bind_param('s', $default_admin_username);
    $statement_check_admin_exists->execute();
    $statement_check_admin_exists->store_result();
    if ($statement_check_admin_exists->num_rows == 0) {
        $query_insert_admin = "INSERT INTO users (username, password) VALUES (?, SHA(?))";
        $statement_insert_admin = $link->prepare($query_insert_admin);
        $statement_insert_admin->bind_param('ss', $default_admin_username, $default_admin_password);
        $statement_insert_admin->execute();
        $statement_insert_admin->store_result();

        $admin_user_id = $statement_insert_admin->insert_id;
        $query_add_admin_to_role = "INSERT INTO users_in_roles(user_id, role_id) VALUES (?, ?)";
        $statement_add_admin_to_role = $link->prepare($query_add_admin_to_role);
        $statement_add_admin_to_role->bind_param('dd', $admin_user_id, $admin_role_id);
        $statement_add_admin_to_role->execute();
        $statement_add_admin_to_role->close();
    }
}
