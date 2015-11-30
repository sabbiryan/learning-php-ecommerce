<?php 
session_start();
include_once '../include/onlineStoreUser.php';
$onlineStore=new onlineStoreUseer();
if($onlineStore->get_session()){
    //echo 'hallo session';
    //exit();
    //if($onlineStore->check_admin_login($username, $password))
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" href="../css/adminStyle.css" rel="stylesheet"/>
        <title>Admin Log In</title>
    </head>
    <body>
        <div id="adminWrapper">
            <?php include_once ("./admin_layout/admin_header_template.php"); ?>
            
            <?php include_once './admin_layout/admin_nav_template.php'; ?>
  
            <section>
                <aside>
                    <ul>
                        <li><a href="inventory_list.php">Manage Inventory</a></li>
                        <li><a href="#">Manage Blah Blah</a></li>
                    </ul>
                </aside>
                
                <div id="content" style="text-align: center;">
                    <h2>Please Log In To Manage The Store</h2>
                    <p>
                        <?php
                        
                        if($_SERVER["REQUEST_METHOD"]=="POST"){
                            $login=$onlineStore->admin_login($_POST['username'], $_POST['password']);
                            //echo '<pre>';
                            //print_r($login);
                            //exit();
                            if($login){
                                //exit();
                                header("location: index.php");        
                                exit();
                            }
                            else{
                                echo 'Username or password may incorrect, try again';
                                //header("location: admin_login.php");
                                //exit();
                            }
                        }
                        ?>
                    </p>
                    <form id="admin_login_form" name="admin_login" method="POST" action="admin_login.php">
                       User Name: <br/>
                       <input name="username" type="text" id="username" size="40" required="true"/>
                       <br/><br/>
                       Password:<br/>
                       <input name="password" type="password" id="password" size="40" required="true"/>
                       <br/>
                       <br/>
                       <br/>
                       
                       <input type="submit" name="buttton" id="button" value="Log In"/>
                       
                    </form>
                    
                </div>            
                
            </section>         
            <?php include_once ("./admin_layout/admin_footer_template.php"); ?>
        </div>
    </body>
</html>



<?php
//Test mysql data fetch succes or not
/*include '../storescripts/connect_to_mysql.php';
$query = "SELECT * FROM admin";
$result = mysql_query($query);
while($data = mysql_fetch_array($result)){
    echo "ID: ".$data["id"]."&nbsp;Username: ".$data["username"]."&nbsp;Password: ".$data["password"];
}
echo mysql_num_rows($result);
exit();*/
?>