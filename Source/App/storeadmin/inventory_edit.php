<?php 
session_start();
require_once '../include/onlineStoreUser.php';
$onlineStore=new onlineStoreUseer();
if($onlineStore->get_session()){
    if($onlineStore->check_admin_login()){
        header("location: inventory_list.php");
        exit();
    }
}
//error report
$onlineStore->error_report();
?>

<?php
//Gather this product full information for inserting automatically into the edit form below on page
if(isset($_GET['pid'])){
    $targetID = $_GET['pid'];
    $product_list="";
    $sql =  mysql_query("SELECT * FROM products WHERE id = '$targetID' LIMIT 1");  //ORDER BY $date_added DESC
    $productCount =  mysql_num_rows($sql);//count the output amount
    //echo $productCount;
    //exit();
    if($productCount>0){
        while ($row = mysql_fetch_array($sql)) {
            //$id = $row["id"];
            $product_name = $row["product_name"];
            $price = $row["price"];
            $category = $row["category"];
            $subcategory = $row["subcategory"];
            $details = $row["details"];
            //$date_added =  strftime("%b %d, %y",  strftime($row["date_added"]));                  
        }
    }
    else {
        echo "Sorry dude that crap don't exist";
        exit();
    }
}
?>

<?php
//Parse the form data and add inventory item to the system
if(isset($_POST['btn_edit_item'])){
    $id = mysql_real_escape_string($_POST['thisID']);
    $product_name =  mysql_real_escape_string($_POST['product_name']);
    $price =  mysql_real_escape_string($_POST['price']);
    $category =  mysql_real_escape_string($_POST['category']);
    $subcategory =  mysql_real_escape_string($_POST['subcategory']);
    $details =  mysql_real_escape_string($_POST['details']);       
    //See if that product name is an identical match to another product in the system
    $sql =  mysql_query("UPDATE products SET product_name='$product_name',price='$price',details='$details',category='$category',subcategory='$subcategory',date_added=now() WHERE id='$id'");
        
    if($_FILES['fileField']['temp_name']!=""){
        //Place image in the folder
        $newname = "$pid.jpg";
        move_uploaded_file($_FILES['fileField']['temp_name'], "../inventory_image/$newname");
    }
    header("location: inventory_list.php");
    exit();
}
?>

<?php

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" href="../css/adminStyle.css" rel="stylesheet"/>
        <title>Inventory List</title>
    </head>
    <body>
        <div id="adminWrapper">
            <?php include_once ("./admin_layout/admin_header_template.php"); ?>
            
            <?php include_once './admin_layout/admin_nav_template.php'; ?>
  
            <section>
                <aside>
                    edit product
                </aside>
                
                <div id="content">
                    <div id="inventory_list" style="margin-left: 50px;">
                        <?php echo $product_list; ?>                       
                    </div>
                    
                     <div id="inventory_form" style="margin-left: 50px">                      
                        <a name="inventoryForm" id="inventoryForm"></a>
                        <h3 style="text-align: center;">&darr;Edit Inventory Item Form &darr;</h3>
                        <form action="inventory_edit.php" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
                            <table border="0" width="95%" cellspacing="0" cellpadding="6">
                                <thead>
                                    <tr>
                                        <td width="30%">Product Name:</td>
                                        <td width="75%">
                                            <label>
                                                <input name="product_name" type="text" id="product_name" size="60" value="<?php echo $product_name; ?>"/>
                                            </label>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Product Price: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp; $
                                        </td>                                       
                                        <td>
                                            <label>
                                                <input name="price" type="text" id="price" size="12" value="<?php echo $price; ?>"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Category: </td>
                                        <td>
                                            <label>
                                                <!--<input name="category" type="text" id="category" size="60"/>-->
                                                <select name="category" id ="category">
                                                    <option value="<?php $category;  ?>"><?php echo $category;  ?></option>
                                                    <option value="Clothing">Clothing</option>
                                                    <option value="Electronics">Electronics</option>
                                                </select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Subcategory:</td>
                                        <td>
                                            <label>
                                                <!--<input name="subcategory" type="text" id="subcategory" size="60"/>-->
                                                <select name="subcategory" id="subcategory">
                                                    <option name="<?php $subcategory; ?>"><?php echo $subcategory; ?></option>
                                                    <option name="Hats">Hats</option>
                                                    <option name="Pants">Pants</option>
                                                    <option name="Shirts">Shirts</option>
                                                    <option name=""></option>
                                                </select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Details:</td>
                                        <td>
                                            <label>
                                                <textarea name="details" id="details" cols="45" rows="8"><?php echo $details; ?></textarea>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Image:</td>
                                        <td>
                                            <label>
                                                <input type="file" name="fileField" id="fileField"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <label>
                                                <input name="thisID" type="hidden" value="<?php echo $targetID ?>"/>
                                                <input type="submit" name="btn_edit_item" id="btn_edit_item" value="Make Change"/>
                                                &nbsp;&nbsp;&nbsp;<a href="inventory_list.php"><button type="button">Revoke</button></a>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </form> 
                    </div>
                </div>                               
            </section>  
            
            <?php include_once ("./admin_layout/admin_footer_template.php"); ?>
            
        </div>
    </body>
</html>
