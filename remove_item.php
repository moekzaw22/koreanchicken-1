<?php 
include('Connection.php');
echo $detailid = $_GET['PID'];
echo $qty = $_GET['Qty'];
if ($qty == "all") {
	$remove_pd = mysqli_query($connection,"DELETE FROM order_detail WHERE Detail_ID='$detailid'");
	echo "<script>window.location='order_entry_test.php';</script>";
}
if ($qty == "1") {
	$remove_pd = mysqli_query($connection,"UPDATE order_detail SET Quantity= Quantity - 1, total_price_p =total_price_p -Item_Price WHERE Detail_ID = '$detailid'");
	echo "<script>window.location='order_entry_test.php';</script>";
}

 ?>