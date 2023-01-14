<?php    
    session_start();
    require_once  ("includes/connect_db.php");

    function logged_in()
    {
        return isset($_SESSION['userid']);
    }

    function confirm_is_admin() {
        if (!logged_in())
        {
            header ("Location: login.php");
        }

        if (!is_admin())
        {
            header ("Location: index.php");
        }
    }

    function confirm_is_user() {
        if (!logged_in())
        {
            header ("Location: login.php");
        }

    }

    function confirm_is_logged_in(){
        if(!logged_in())
        {
            header ("Location: index.php");
        }
    }

    
    function is_admin()
    {
        global $link;
        $query = "SELECT user_id FROM users_in_roles UIR INNER JOIN roles R on UIR.role_id = R.id WHERE R.name = 'admin' AND UIR.user_id = ? LIMIT 1";
        $stmt = $link->prepare($query);
        $stmt->bind_param('d', $_SESSION['userid']);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows == 1;
    }

    function is_user()
    {
        global $link;
        $query = "SELECT user_id FROM users_in_roles UIR INNER JOIN roles R on UIR.role_id = R.id WHERE R.name = 'user' AND UIR.user_id = ? LIMIT 1";
        $stmt = $link->prepare($query);
        $stmt->bind_param('d', $_SESSION['userid']);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows == 1;
    }

