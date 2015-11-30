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
//Parse the form data and add inventory item to the system
if(isset($_POST['btn_add_item'])){
    $product_name =  mysql_real_escape_string($_POST['product_name']);
    $price =  mysql_real_escape_string($_POST['price']);
    $category =  mysql_real_escape_string($_POST['category']);
    $subcategory =  mysql_real_escape_string($_POST['subcategory']);
    $details =  mysql_real_escape_string($_POST['details']);     
    
    //See if that product name is an identical match to another product in the system
    $sql =  mysql_query("SELECT id FROM products WHERE product_name='$product_name' LIMIT 1");
    $productMatch =  mysql_num_rows($sql);//Count the output amount
    //echo $productMatch;
   
    if($productMatch > 0){
        echo 'Sorry you try to place a duplicate "Product Name" into the system,
            <a href="inventory_list.php">Click here</a>';
        exit();
    }
    //Add this product into the database now
    $sql =  mysql_query("INSERT INTO products (product_name, price, details, category, subcategory, date_added)
        VALUES('$product_name','$price','$details','$category','$subcategory',now())") or die('SQL error: '.mysql_error());
    $pid =  mysql_insert_id();
    //Place image in the folder
    $newname = "$pid.jpg";
    move_uploaded_file($_FILES['fileField']['temp_name'], "../inventory_image/$newname");
    header("location: inventory_list.php");
    exit();
}
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
                    <ul>
                        <li><a href="inventory_list.php#inventoryForm">+Add new Inventory Item</a></li>
                    </ul>
                </aside>
                
                <div id="content">
                    <div id="inventory_list" style="margin-left: 50px;">
                        <?php
                        //Delete Item Questions to Admin, and Deleter Product if they choose
                        if(isset($_GET['deleteid'])){
                            echo 'Do you really want to delete product ID of '.$_GET['deleteid'].'?
                                <a href="inventory_list.php?yesdelete='.$_GET['deleteid'].'">Yes</a> | 
                                <a href="inventory_list.php">No</a>';
                            exit();
                        }
                        if(isset($_GET['yesdelete'])){
                            //remove item from system and delete its picture
                            //delete from database
                            $id_to_delete=$_GET['yesdelete'];
                            $sql=  mysql_query("DELETE FROM products WHERE id='$id_to_delete' LIMIT 1") or die(mysql_error());
                            //unlink the image from server
                            //Remove the picture
                            $pictodelete=("../inventory_images/$id_to_delete.jpg");
                            if(file_exists($pictodelete)){
                                unlink($pictodelete);
                            }
                            header("location: inventory_list.php");
                            exit();
                        }
                        ?>
                        
                        <h2>Inventory list</h2>
                        
                        <?php
                        //This block grabls the whole list for viewing
                        $sql =  mysql_query("SELECT * FROM products ORDER BY date_added DESC");
                        $productCount =  mysql_num_rows($sql);//count the output amount
                        if($productCount>0){
                            while ($row = mysql_fetch_array($sql)) {
                                $id = $row["id"];
                                $product_name = $row["product_name"];
                                $price = $row['price'];
                                $date_added = $row['date_added'];
                                //$date_added =  strftime("%d %b, %y",  strftime($row["date_added"])); 
                                echo $product_list = "<strong>$date_added</strong> - Product ID: <strong>$id</strong>  -  Product Title: <strong>$product_name</strong>&nbsp;&nbsp;&nbsp;  
                                    <a href='inventory_edit.php?pid=$id'>edit</a> &bull;
                                    <a href='inventory_list.php?deleteid=$id'>delete</a><br/>";
                            }
                        }
                        else {
                             echo $product_list="You have no products listed in your store yet";
                        }
                        
                        ?>
                    </div>
                    
                    <div id="inventory_form" style="margin-left: 50px">
                        <a name="inventoryForm" id="inventoryForm"></a>
                        <h3 style="text-align: center;">&darr;Add New Inventory Item Form &darr;</h3>
                        <form action="inventory_list.php" enctype="multipart/form-data" name="inventory_list" id="inventory_list_form" method="post">
                            <table border="0" width="95%" cellspacing="0" cellpadding="6">
                                <thead>
                                    <tr>
                                        <td width="30%">Product Name:</td>
                                        <td width="75%">
                                            <label>
                                                <input name="product_name" type="text" id="product_name" size="60"/>
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
                                                <input name="price" type="text" id="price" size="12"/>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Category: </td>
                                        <td>
                                            <label>
                                                <!--<input name="category" type="text" id="category" size="60"/>-->
                                                <select name="category" id ="category">
                                                    <option value=""></option>
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
                                                    <option name=""></option>
                                                    <option value="Laptop">Laptop</option>
                                                    <option value="Desktop">Desktop</option>
                                                    <option name="Hats">Hats</option>
                                                    <option name="Pants">Pants</option>
                                                    <option name="Shirts">Shirts</option>
                                                </select>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Details:</td>
                                        <td>
                                            <label>
                                                <textarea name="details" id="details" cols="45" rows="8"></textarea>
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
                                                <input type="submit" name="btn_add_item" id="btn_add_item" value="Add This Item Now"/>
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
