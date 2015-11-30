<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'db_mystore');
class onlineStoreUseer {
    //Database connection
    public function __construct() {
        $connection=  mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die("Connection erroe -> ") .mysql_errno();
        mysql_select_db(DB_DATABASE) or die("Database connection error -> ".  mysql_errno());
    }
    
    //Script Error reporting
    public function error_report(){
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    }


    //Check session
    public function get_session() {	    
       if(isset($_SESSION['login'])) {
            return $_SESSION['login'];
       }
    }
    
    //Admin login
    public function admin_login($username,$password){
        //Parse the log in form if the user hase field it out and press log in
        $username = preg_replace('#[^A-Za-z0-9]#i', '', $username);
        $password = preg_replace('#[^A-Za-z0-9]#i', '', $password);
        //exit();
        $sql =  mysql_query("SELECT id FROM admin WHERE username='$username' AND password='$password' LIMIT 1");  
        
        //Make sure person exist the database
        $existCount =  mysql_num_rows($sql);
        if($existCount == 1){
            while($row =  mysql_fetch_array($sql, MYSQL_ASSOC)){
                $id = $row["id"];           
            }
            $_SESSION["login"]=true;
            $_SESSION["id"]=$id;
            $_SESSION["username"]=$username;
            $_SESSION["password"]=$password;
            //exit();
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    //Check admin login
    public function check_admin_login(){
        //Be sure to check that manager SESSION value is in fact in the database
        $userid =  preg_replace('#[^0-9]#i', '', $_SESSION["id"]);
        $username =  preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]);
        $password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]);
        //Run my sql quary to be sure that this person an admin and that their password session val equels the database information
        $sql = mysql_query("SELECT * FROM admin WHERE id='$userid' AND username='$username' AND password='$password' LIMIT 1");

        //Make sure person exist the database
        $existCount = mysql_num_rows($sql);
        if($existCount == 0){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
}

?>
