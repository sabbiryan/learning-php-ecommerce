<?php 
session_start();
require_once '../include/onlineStoreUser.php';
$onlineStore=new onlineStoreUseer();
if($onlineStore->get_session()){
    if($onlineStore->check_admin_login()){
        header("location: index.php");
        exit();
    }    
}
else{
    header("location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" href="../css/adminStyle.css" rel="stylesheet"/>
        <title>Store Admin Area</title>
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
                
                <div id="content">
                    <h2 style="text-align: center;">Hello Store Manager, What would you like today?</h2>
                    <div id="inventory_list" style="margin-left: 50px;">
                        <p><?php $onlineStore->display_product(); ?><p>
                    </div>                    
                </div>                      
            </section>         
            <?php include_once ("./admin_layout/admin_footer_template.php"); ?>
        </div>
    </body>
</html>
