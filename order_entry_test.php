<?php 
$quantity = 0;
	include('Connection.php');
     $total_sum = 0;
     date_default_timezone_set("Asia/Yangon");
     // $orderdate=date('Y-m-d');
    echo  $orderdate="2023/1/8";
    ## counting order 
    $last_order = "SELECT Order_ID FROM order_r ORDER BY Order_ID DESC";
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
       
        $new_order_query = mysqli_query($connection,"INSERT INTO order_r VALUES ('','$table_R','$orderdate')");
       echo "<meta http-equiv='refresh' content='0'>";
    }
     if ($OID < 1) {
        $OID = 1;
    }
    if (isset($_POST['lastorder'])) {
        $OID = $lastorder;
    }
	 $get_order = "SELECT * FROM order_r WHERE Order_ID = '$OID'";
	$order_Query = mysqli_query($connection,$get_order);
	$order_array = mysqli_fetch_array($order_Query);
	$order_ID = $order_array['Order_ID'];
    $table = $order_array['Table_R'];   
    
    $select_total = "SELECT SUM(total_price_p) AS totalprice FROM order_detail WHERE Order_ID = '$order_ID'";
    $total_result = mysqli_query($connection,$select_total);
    $row1=mysqli_fetch_array($total_result);
    $total_sum = $row1['totalprice'];
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
  <title>Order Entry</title>  
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
<!-- Bootstrap Css -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 	<title></title>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#Product").click(function(){
        var productid=$("#myInput").val();
        var order_id =$("#OrderID").val();
        $.ajax({
                    url:'insert_order_detail.php',
                    method:'POST',
                    data:{
                        productid:productid,
                        order_id:order_id
                    },
                   success:function(data){                 
                   }
                });
        });
});
          $(document).ready(function(){  
        $('#myInput').keyup(function(){  
            var query = $(this).val();  
            if(query != '')  
            {  
                $.ajax({  
                    url:"Search.php",  
                    method:"POST",  
                    data:{query:query},  
                    success:function(data)  
                    {  
                        $('#userList').show();  
                        $('#userList').html(data);  
                    }  
                });  
            }  
        });  
        $(document).on('click', 'li', function(){  
            $('#myInput').val($(this).text());  
            $('#userList').hide();  

             var productid=$("#myInput").val();
             var order_id =$("#OrderID").val();
             $.ajax({
                    url:'insert_order_detail.php',
                    method:'POST',
                    data:{
                        productid:productid,
                        order_id:order_id
                    },
                   success:function(data){
                           window.location.reload();
                   }
                });
        });  
    });  
//removing 1 record from data
function edit_field(id){
}
    </script>
 </head>
 <body>
 	<?php  
        $pre = $OID - 1;
        $next = $OID + 1; 
    ?>
    <form action="order_entry_test.php?OID=<?php echo $OID; ?>" method="POST"> 
    <?php echo "<a href = 'order_entry_test.php?OID=".$pre."' class='prenext'>Pre</a>" ?>
    <!-- <button type="submit" id="neworder" name="NewOrder" onclick="NewOrder()">New Order</button> -->
    <?php echo "<a href = 'order_entry_test.php?OID=".$next."' class='prenext'>Next</a>" ?>
    <input type="submit" name="lastorder" value="Last Order">
    <div>
        <input type="text" name="order_id" id="OrderID" value="<?php echo $order_ID ?>">
    </div>
    <div>
        <input type="text" name="table" value="<?php echo $table ?>">
    </div>
 	
<table>
    <tr>
        <td style="user-select: none;">Item</td>
        <td><input type="text" name="ProductID" id="myInput" autocomplete="off" autofocus="true" ></td>
        <td><input type="submit" name="ProductAdd" id="Product"></td>
    </tr>	
     <tr>
        <td colspan="3"> <div id="userList"></div></td>
    </tr>
 	</table>
    <table class="item-table">
       <tr>
           <td colspan="8"> Total is <span><?php echo number_format($total_sum) ?></span></td>
       </tr>
        <tr>
            <td>ID</td>
            <td>Item</td>
            <td>Price</td>
            <td>Qty</td>
            <td>Total</td>
        </tr>
        <?php 
            $query_order_detail = "SELECT * FROM order_detail od, product p WHERE od.Order_ID = '$order_ID' AND p.Product_ID = od.Product_ID";
            $order_detail_f_q = mysqli_query($connection,$query_order_detail);
            $order_detail_count = mysqli_num_rows($order_detail_f_q);
            $data = array();
       while ($row = mysqli_fetch_object($order_detail_f_q)) { 
            // $order_detail_fetch = mysqli_fetch_array($order_detail_f_q);
            //     $item_name = $order_detail_fetch['Product_Name'];
            //     $item_price = $order_detail_fetch['Item_Price'];
            //     $quantity = $order_detail_fetch['Quantity'];
            //     $item_total = $order_detail_fetch['total_price_p'];
                echo "<tr>";
                 echo "<td>".$row->Product_ID."</td>";
                    echo "<td>".$row->Product_Name."</td>";
                    echo "<td>".number_format($row->Item_Price)."</td>";
                    echo "<td>".$row->Quantity."</td>";
                    echo "<td>".number_format($row->total_price_p)."</td>";
                    if ($row->Quantity < 2) {
                       echo "<td><a href='remove_item.php?PID=$row->Detail_ID&Qty=all'>Remove 1</a></td>"; 
                    }
                    else{
                       echo "<td><a href='remove_item.php?PID=$row->Detail_ID&Qty=1'>Remove 1</a></td>"; 
                       echo "<td><a href='remove_item.php?PID=$row->Detail_ID&Qty=1'>Remove ".$row->Quantity."</a></td>";
                    }                  
                echo "</tr>";
            }
            
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
        input.foucs();
       
    }
});
function NewOrder(){
    window.location.reload();
    input.focus();
}
</script>
<style type="text/css">
    .item-table{
        border:1px solid grey;
        position: absolute;
        left: 350px;
        top: 0px;
    }.item-table tr td{
        border:1px dotted  grey;
        font-size: 20px;
    }
    .prenext{
        padding:5px 10px 5px 10px;
        
         color: black;
         border: 1px solid grey;
    }
    .prenext:hover{
        color: black;
        text-decoration: none;
    }
    
       
 
</style>
 </html>