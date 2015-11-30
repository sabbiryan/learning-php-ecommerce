<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" href="css/style.css" rel="stylesheet"/>
        <title>Home</title>
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
                        <p><?php //echo $dynamicList; ?></p>
                        <?php
                        //Run a select quary to get my latest 6 items
                        //connect to my sql database
                        include "storescripts/connect_to_mysql.php";
                        $dynamicList="";
                        $sql =  mysql_query("SELECT * FROM products ORDER BY date_added DESC LIMIT 6");
                        $productCount =  mysql_num_rows($sql);//count the output amount
                        if($productCount>0){
                            while ($row = mysql_fetch_array($sql)) {
                                $id = $row["id"];
                                $product_name = $row["product_name"];
                                $price = $row['price'];
                                //$date_added =  strftime("%b %d, %y",  strftime($row["date_added"]));
                                echo $dynamicList = '<table width="100%" border="0" cellspacing="0" cellpadding="6" style="text-align: left;">
                                                  <tr>
                                                    <td width ="17%"><a href="product.php?id='.$id.'"><img style="border: #666666 1px solid" src="inventory_image/'.$id.'.jpg" alt="' .$product_name. '" width="77" height="102" border="1"/></a></td>
                                                    <td width="83%" valign="top">
                                                        ' .$product_name. '<br/>
                                                        $' .$price. '<br/>
                                                        <a href="product.php?id='.$id.'">View Product Details</a>
                                                    </td>
                                                  </tr>                                                          
                                                </table><br/>';
                            }
                        }
                         else {
                             echo $dynamicList="We have no products listed in our store yet";
                        }
                        mysql_close();
                        ?>
                    </div>
                    <hr/>
                </div>
                
                <aside>
                    More crap
                </aside>
                
            </section> 
            
            <?php include_once ("./layout/template_footer.php"); ?>
            
        </div>
    </body>
</html>
