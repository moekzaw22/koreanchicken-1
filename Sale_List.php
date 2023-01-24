<?php 
	include('Connection.php');
	$row = array();
	$fetch_rows = mysqli_query($connection,"SELECT total_price_p, Product_Name, Quantity, orr.Order_ID, Order_Date, Table_R FROM order_detail od, product p, order_r orr WHERE p.Product_ID=od.Product_ID AND orr.Order_ID = od.Order_ID ORDER BY Order_ID");
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title></title>
 </head>
 <body>
 	<form action="Sale_List_Search.php" method="GET">
 	<input type="date" name="datefrom" id="Datefrom" onchange="datefromchg()">
 	<input type="date" name="dateto">
 		<input type="submit" name="btnsearch" value="Search">
 	<input type="submit" name="btnsearch" value="Top Sale">
 
 	<table>
 		<tr>
 			<td>Table N0.</td>
 			<td>Item</td>
 			<td>Qty</td>
 			<td>Total Sale</td>
 			<td>Date</td>
 		</tr>
 		<?php 
 			while ($row = mysqli_fetch_assoc($fetch_rows)) {
  			$rows[] = $row;
  			echo "<tr>";
  			echo "<td>".$row['Table_R']."</td>";
  			echo "<td>".$row['Product_Name']."</td>";
  			echo "<td>".$row['Quantity']."</td>";
  			echo "<td>".$row['total_price_p']."</td>";
  			echo "<td>".$row['Order_Date']."</td>";
  			echo "</tr>";
			}
 		 ?>
 	</table>
 	</form>
    <script type="text/javascript">
       
    </script>
 </body>
 </html>