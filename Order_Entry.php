<?php 
$quantity = 0;
	include('Connection.php');
     
     date_default_timezone_set("Asia/Yangon");
     // $orderdate=date('Y-m-d');
     $orderdate='2023/1/1';
    ## counting order 
    $last_order = "SELECT * FROM order_r ORDER BY Order_ID";
    $lastorder_query = mysqli_query($connection, $last_order);
    $count_order = mysqli_num_rows($lastorder_query);

    ##getting last page
    $lastorder = ceil($count_order/1);

    ## selecting order for new order button

     $order_tdy = "SELECT * FROM order_r WHERE Order_Date = '$orderdate' ";
    $order_tdy_q = mysqli_query($connection,$order_tdy);
     $order_tdy_count = mysqli_num_rows($order_tdy_q);
      $table_R = $order_tdy_count + 1;

    if (isset($_REQUEST['OID'])) {
        $OID = $_GET['OID'];
       
    }
    if (empty($OID)) {
        $OID = $lastorder;
    }
     
   
    else if ($OID > $lastorder) {
        $OID = $lastorder;
    }
     if ($OID < 1) {
        $OID = 1;
    }
	 $get_order = "SELECT * FROM order_r WHERE Order_ID = '$OID'";
	$order_Query = mysqli_query($connection,$get_order);
	$order_array = mysqli_fetch_array($order_Query);
	$order_ID = $order_array['Order_ID'];
    $table = $order_array['Table_R'];
   
    
   
    if (isset($_POST['ProductAdd'])) {
          $order_id= $_POST['order_id'];
        $Product_ID = $_POST['ProductID'];

        ## Getting product Price
        $fetch_product = "SELECT * FROM product WHERE Product_ID =' $Product_ID'";
        $product_f_query = mysqli_query($connection,$fetch_product);
        $product_fetch = mysqli_fetch_array($product_f_query);
     $price = $product_fetch['Product_Price']; 
     
       $product_name = $product_fetch['Product_Name'];
        $checking_quantity = "SELECT * FROM order_detail WHERE Product_ID = '$Product_ID' AND Order_ID = '$order_id'";
        $quantity_f_query = mysqli_query($connection,$checking_quantity);
       echo $count = mysqli_num_rows($quantity_f_query);
       if ($count == 0 ) {
        
         $quantity = 1;
          $insert_order_detail = "INSERT INTO order_detail VALUES ('','$order_id','$Product_ID','$price','$quantity','$price')";
          $insert_order_query = mysqli_query($connection,$insert_order_detail);
            print("Quantity set");
       }
       elseif ($count > 0) {
           $quantity = $quantity + 1;
           echo $add_quantity = "UPDATE order_detail SET Quantity= Quantity + 1, total_price_p =total_price_p +$price WHERE Product_ID = '$Product_ID' AND Order_ID = '$order_id'";
           $add_quantity_query = mysqli_query($connection,$add_quantity);

           print("Quantity Added");
       }

      
        print($product_name." Added");
      
    }
    if (isset($_POST['NewOrder'])) {
         print("New Order");
      echo  $insert_new_order = "INSERT INTO order_r VALUES ('','$table_R','$orderdate')";
        $new_order_query = mysqli_query($connection,$insert_new_order);
       echo "<meta http-equiv='refresh' content='0'>";
       
    }
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Script -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
 
<!-- jQuery UI -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
 
<!-- Bootstrap Css -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

 	<title></title>
 </head>
 <body>
 	<?php  
        $pre = $OID - 1;
        $next = $OID + 1; 
    ?>
    <?php echo "<a href = 'Order_Entry.php?OID=".$pre."'>Pre</a>" ?>
   
    <?php echo "<a href = 'Order_Entry.php?OID=".$next."'>Next</a>" ?>
    <form action="Order_Entry.php" method="POST">
 	<button type="submit" name="NewOrder" onclick="NewOrder()">New Order</button>
 	<table>
 	
 	<tr>
 		<td><input type="text" name="order_id" value="<?php echo $order_ID ?>"></td>
 	</tr>
    <tr>
        <td><input type="text" name="table" value="<?php echo $table ?>"></td>
    </tr>
</table>
<table>
  
    <tr>
        <td>Item</td>

        <td><input type="text" name="ProductID" id="myInput"></td>
        <td><input type="submit" name="ProductAdd" id="Product"></td>
    </tr>	
 	</table>

    <table class="item-table">
        <tr>
            <td>Item</td>
            <td>Price</td>
            <td>Qty</td>
            <td>Total</td>
        </tr>
        <?php 
            $select_total = "SELECT SUM(total_price_p) AS totalprice FROM order_detail WHERE Order_ID = '$order_ID'";
            $total_result = mysqli_query($connection,$select_total);
            $row1=mysqli_fetch_array($total_result);
            $total_sum = $row1['totalprice'];
            $query_order_detail = "SELECT * FROM order_detail od, product p WHERE od.Order_ID = '$order_ID' AND p.Product_ID = od.Product_ID";
            $order_detail_f_q = mysqli_query($connection,$query_order_detail);
            $order_detail_count = mysqli_num_rows($order_detail_f_q);

            for ($i=0; $i < $order_detail_count; $i++) { 
                $order_detail_fetch = mysqli_fetch_array($order_detail_f_q);
                $item_name = $order_detail_fetch['Product_Name'];
                $item_price = $order_detail_fetch['Item_Price'];
                $quantity = $order_detail_fetch['Quantity'];
                $item_total = $order_detail_fetch['total_price_p'];
                echo "<tr>";
                    echo "<td>".$item_name."</td>";
                    echo "<td>".$item_price."</td>";
                    echo "<td>".$quantity."</td>";
                    echo "<td>".$item_total."</td>";
                echo "</tr>";
              
            }
            echo "total is",$total_sum;
         ?>
    </table>
    </form>
 </body>
 <script type="text/javascript">
var input = document.getElementById("myInput");
var OrderNew = document.getElementById("NewOrder");
input.addEventListener("keypress", function(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
         document.getElementById("Product").click();
        
       
    }
});
function NewOrder(){
    window.location.reload();
}
</script>
<style type="text/css">
    .item-table{
        border:1px solid grey;
    }.item-table tr td{
        border:1px dotted  grey;
    }

</style>
 </html>