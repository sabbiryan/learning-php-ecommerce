<?php
session_start();//Start session first things in script
//Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors','1');

//connect to my sql database
include "storescripts/connect_to_mysql.php";
?>

<?php
//////////////////////////////
//Section 1 (if user attempt to add something to the cart)
//////////////////////////////
if(isset($_POST['pid'])){
    $pid = $_POST['pid'];
    $wasFound = false;
    $i = 0;
    //If the cart session variable is not set or the cart array is empty
    if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"])<1){
        //Run if the cart is empty or not set
        $_SESSION["cart_array"] = array(
                                        1=>array("item_id"=>$pid,"quantity"=>1)
                                        );       
    }
    else {
        //Run if the cart has at least one item in int
        foreach ($_SESSION["cart_array"] as $each_item){
            $i++;
            while(list($key, $value) = each($each_item)){
                if($key == "item_id" && $value == $pid){
                    //That item is in cart already so lets adjust its quantity using array_splice()
                    array_splice($_SESSION["cart_array"],$i-1,1,array(array("item_id"=>$pid,"quantity"=>$each_item['quantity']+1)));
                    $wasFound = true;
                }//close if condition
            }//close while loop
        }//close foreach loop
        if($wasFound==false){
            array_push($_SESSION["cart_array"], array("item_id"=>$pid, "quantity"=>1));
        }
    }
    header("location: cart.php");
    exit();
}
?>

<?php
//////////////////////////////
//Section 2 (if user chooses to empty their shopping cart)
//////////////////////////////
if(isset($_GET['cmd']) && ($_GET['cmd']=="emptyCart")){
    unset($_SESSION["cart_array"]);
}
?>

<?php
//////////////////////////////
//Section 3 (if user chooses to adjust item quantity)
//////////////////////////////
if(isset($_POST['item_to_adjust']) && ($_POST['item_to_adjust']="")){
    $item_to_adjust = $_POST['item_to_adjust'];
    $quantity = $_POST['quantity'];
    $quantity = preg_replace('#[^A-Za-z0-9]#i', '', $quantity);
    if($quantity >= 100){
        $quantity = 99; 
    }
    if($quantity < 1){
        $quantity = 1;
    }
    $i = 0;
    foreach ($_SESSION["cart_array"] as $each_item){
        $i++;
        while(list($key, $value) = each($each_item)){
            if($key == "item_id" && $value == $item_to_adjust){
                //That item is in cart already so lets adjust its quantity using array_splice()
                array_splice($_SESSION["cart_array"],$i-1,1,array(array("item_id"=>$item_to_adjust,"quantity"=>$quantity)));
            }//close if condition
        }//close while loop
    }//close foreach loop
}
?>

<?php
//////////////////////////////
//Section 4 (if user want ot remove an item from cart)
//////////////////////////////
if(isset($_POST['index_to_remove']) && $_POST['index_to_remove']!=""){
    //Access the array and run code to remove that array index
    
    $key_to_remove = $_POST['index_to_remove'];
    if(count($_SESSION['cart_array'])<=1){
        unset($_SESSION["cart_array"]);
    }
    else{
        unset($_SESSION["cart_array"]["$key_to_remove"]);
        sort($_SESSION["cart_array"]);
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
        <title>Your Cart</title>
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
                    <table align="center" style="margin-top: 10px;" width="96%" border="1" cellspacing="0" cellpadding="6">
                        <tr>
                            <td width="18%"><strong>Product</strong></td>
                            <td width="40%"><strong>Product Description</strong></td>
                            <td width="13%"><strong>Unit Price</strong></td>
                            <td width="9%"><strong>Quantity</strong></td>
                            <td width="7%"><strong>Total</strong></td>
                            <td width="9%"><strong>Remove</strong></td>
                        </tr>
                        <?php //echo $cartOutput; ?>
                        <?php
                        //////////////////////////////
                        //Section 5 (render the cart for the user to view on the page)
                        //////////////////////////////
                        $cartOutput = "";
                        $cartTotal = "";
                        $pp_checkout_btn = '';
                        $product_id_array = '';
                        if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"])<1){
                            $cartOutput = "<h2 align='center'>Your shopping cart is empty</h2>";
                        }
                        else{
                            // Start PayPal Checkout Button
                            /*$pp_checkout_btn .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                                <input type="hidden" name="cmd" value="_cart">
                                                <input type="hidden" name="upload" value="1">
                                                <input type="hidden" name="business" value="you@youremail.com">';*/
                            $i = 0;
                            foreach ($_SESSION["cart_array"] as $each_item){
                                $item_id = $each_item['item_id'];
                                $sql = mysql_query("SELECT * FROM products WHERE id='$item_id' LIMIT 1");
                                while ($row = mysql_fetch_array($sql)){
                                    $product_name = $row['product_name'];
                                    $price = $row['price'];
                                    $details = $row['details'];
                                }
                                $price_total = $price * $each_item['quantity'];
                                $cartTotal = $price_total + $cartTotal;
                                
                                //setlocale(LC_MONETARY,"en_US");
                                //$price_total = money_format("%10.2n", $price_total);
                                
                                // Dynamic Checkout Btn Assembly
                                $x = $i + 1;
                                $pp_checkout_btn .= '<input type="hidden" name="item_name_' . $x . '" value="' . $product_name . '">
                                                    <input type="hidden" name="amount_' . $x . '" value="' . $price . '">
                                                    <input type="hidden" name="quantity_' . $x . '" value="' . $each_item['quantity'] . '">  ';
                                // Create the product array variable
                                $product_id_array .= "$item_id-".$each_item['quantity'].","; //6-1,8-1,3-1,

                                //Dynamic table row assembly
                                echo $cartOutput = '<tr>';
                                echo $cartOutput = "<td><a href=\"product.php?id=$item_id\">".$product_name.'<a/><br/><img src="inventory_image/'.$item_id.'.jpg" alt="'.$product_name.'" width="40" height="52" border="1"/></td>';
                                echo $cartOutput = '<td>'.$details."<br/></td>";
                                echo $cartOutput = '<td>$'.$price."<br/></td>";
                                echo $cartOutput = '<td>
                                                        <form action="cart.php" method="post">
                                                            <input name="quantity" type="text" value="'.$each_item['quantity'].'" size="1" maxlength="2" />
                                                            <input type="submit" name="btn_adjust'.$item_id.'" value="Change"/>
                                                            <input type="hidden" name="item_to_adjust" value="' .$item_id. '"/>    
                                                        </form>
                                                    </td>';
                                //echo $cartOutput = '<td>'.$each_item['quantity']."<br/></td>";
                                echo $cartOutput = '<td>$'.$price_total."<br/></td>";
                                echo $cartOutput = '<td>
                                                        <form action="cart.php" method="post">
                                                            <input type="submit" name="btn_delete'.$item_id.'" value="X"/>
                                                            <input type="hidden" name="index_to_remove" value="' .$i. '"/>    
                                                        </form>
                                                    </td>';
                                echo $cartOutput = '</tr>';
                                $i++;
                            } 
                            //setlocale(LC_MONETARY,"en_US");
                            //$cartTotal = money_format("%10.2n", $cartTotal);
                            $cartTotal = "Cart Total: $".$cartTotal." USD";                            
                        
                             // Finish the Paypal Checkout Btn
                            $pp_checkout_btn .= '<input type="hidden" name="custom" value="' . $product_id_array . '">
                                                <input type="hidden" name="notify_url" value="https://www.yoursite.com/storescripts/my_ipn.php">
                                                <input type="hidden" name="return" value="https://www.yoursite.com/checkout_complete.php">
                                                <input type="hidden" name="rm" value="2">
                                                <input type="hidden" name="cbt" value="Return to The Store">
                                                <input type="hidden" name="cancel_return" value="https://www.yoursite.com/paypal_cancel.php">
                                                <input type="hidden" name="lc" value="US">
                                                <input type="hidden" name="currency_code" value="USD">
                                                <input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
                                                </form>';
                        }
                        ?>
                    </table> 
                    <p style="text-align: right; margin-right: 10px;"><?php echo $cartTotal; ?></p>
                    <br/><br/>
                    <p style="text-align: left; margin-left: 10px;"><?php echo $pp_checkout_btn; ?></p>
                    <br/><br/>
                    <a href="cart.php?cmd=emptyCart" style="margin-left: -350px;">Click Here To Empty Your Shopping Cart</a>
                    <br/><br/>                   
                </div>
                
                <aside>
                    More crap
                </aside>
                
            </section> 
            
            <?php include_once ("./layout/template_footer.php"); ?>
            
        </div>
    </body>
</html>
