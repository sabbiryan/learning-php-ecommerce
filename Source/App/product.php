<?php
//Error Reporting
error_reporting(E_ALL);
ini_set('display_errors','1');
?>

<?php
//Check to see the URL Variable is set and that it exists in the database
if(isset($_GET['id'])){
    //Connect to Mysql
    $con = include "storescripts/connect_to_mysql.php";  
    $id = preg_replace('#[^0-9]#i', '', $_GET['id']); 
    //Use this variable to check to see if this ID exists, if yes then get the product
    //Details, if no then exit this script and give message why    
    $sql =  mysql_query("SELECT * FROM products WHERE id='$id' LIMIT 1");
    $productCount = mysql_num_rows($sql);
    if($productCount > 0){
        //get all the product details
        while ($row = mysql_fetch_array($sql)) {
            $id = $row["id"];
            $product_name = $row["product_name"];
            $price = $row['price'];
            $details = $row['details'];
            $category = $row['category'];
            $subcategory = $row['subcategory'];
            $date_added =$row["date_added"];
        }
    }
    else{
        echo "That item does not exist";
    }
}
else{
    echo "Data to render this page is missing";
    exit();
}
mysql_close();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" href="css/style.css" rel="stylesheet"/>
        <title><?php echo $product_name; ?></title>
    </head>
    <body>
        <div id="mainWrapper">
            
            <?php include_once ("./layout/template_header.php"); ?>
            
            <?php include_once './layout/template_nav.php'; ?>
            
            <section>
                <aside>
                    Some Crap
                </aside>
                
                <div id="content">
                    <p>Newest item Added to the store</p>
                    <hr/>
                    
                    <div id="product_view" style="margin-left:50px; ">
                        <table width="100%" border="0" cellspacing="0" cellpadding="15" style="text-align: left;">
                            <tr>
                                <td width ="20%" valign="top">                                  
                                    <img width="150px" height="200px" style="border: #666666 1px solid" src="inventory_image/<?php echo $id; ?>.jpg" alt="<?php echo $product_name; ?>" width="77" height="102" border="1"/>
                                    <a href="inventory_image/<?php echo $id; ?>.jpg">View Full Size Image</a>
                                </td>
                                <td width="80%" valign="top">
                                    <h3><?php echo $product_name; ?></h3>
                                    <p>$&nbsp;<?php echo $price; ?></p>
                                    <?php echo "$subcategory, $category"; ?>
                                    <p><?php echo $details; ?></p>
                                    <p>
                                    <form id="cartForm" name="cartForm" method="post" action="cart.php">
                                        <input type="hidden" name="pid" id="pid" value="<?php echo $id; ?>"/>
                                        <input type="submit" name="btn_add_cart" id="btn_add_cart" value="Add to Shopping Cart"/>
                                    </form>
                                    </p>
                                    <br/>
                                </td>
                            </tr>                                                          
                        </table>
                    </div>                                                       
               </div>
                
                <aside>
                    More crap
                </aside>
                
            </section> 
            
            <?php include_once ("./layout/template_footer.php"); ?>
            
        </div>
    </body>
</html>
