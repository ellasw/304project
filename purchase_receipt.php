<?php
$email = $_GET['cust_email'];
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_t1m8", "a34564120", "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))");
$totalprice = 0;

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
	//echo "<br>running ".$cmdstr."<br>";
	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn); // For OCIParse errors pass the       
		// connection handle
		echo htmlentities($e['message']);
		$success = False;
	}

	$r = OCIExecute($statement, OCI_DEFAULT);
	if (!$r) {
		echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
		$e = oci_error($statement); // For OCIExecute errors pass the statementhandle
		echo htmlentities($e['message']);
		$success = False;
	} else {

	}
	return $statement;

}

function executeBoundSQL($cmdstr, $list) {
	/* Sometimes a same statement will be excuted for severl times, only
	 the value of variables need to be changed.
	 In this case you don't need to create the statement several times; 
	 using bind variables can make the statement be shared and just 
	 parsed once. This is also very useful in protecting against SQL injection. See example code below for       how this functions is used */

	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr);

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn);
		echo htmlentities($e['message']);
		$success = False;
	}

	foreach ($list as $tuple) {
		foreach ($tuple as $bind => $val) {
			//echo $val;
			//echo "<br>".$bind."<br>";
			OCIBindByName($statement, $bind, $val);
			unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

		}
		$r = OCIExecute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
			echo htmlentities($e['message']);
			echo "<br>";
			$success = False;
		}
	}

}
?>

<html>
	<head>
		<title>Purchase Receipt</title>
	</head>
	<body>
		<h1>Purchase Receipt</h1>
		<table>
			<tr>
				<th>Purchase No</th>
				<th>Customer Email</th>
				<th>Purchase Month</th>
				<th>Purchase Year</th>
				<th>Album ID</th>
				<th>Album Name</th>
				<th>Unit Price</th>
				<th>Quantity</th>
			</tr>
			
			<?php
				if($db_conn){
					// PurNo | CustEmail | PurMonth | PurYear | AlbumId | AlbumName| UnitPrice |Quantity
					$mostrecent = OCI_Fetch_Array(executePlainSQL("select MAX(purchase_no) as pno from makes_purchase"), OCI_BOTH);
					$mostrecentpurchase = $mostrecent['PNO'];
					$purchases = executePlainSQL("select cust_email, purchase_month, purchase_year, p.album_id as album_id, name, price, quantity from makes_purchase m, purchase_has_album p, album a where m.purchase_no=p.purchase_no and m.purchase_no=" . $mostrecentpurchase . " and p.album_id=a.album_id");
					while($row = OCI_Fetch_Array($purchases, OCI_BOTH)){
						echo "<tr><td>" 
							. $mostrecentpurchase . "</td><td>" 
							. $row['CUST_EMAIL'] . "</td><td>" 
							. $row['PURCHASE_MONTH'] . "</td><td>" 
							. $row['PURCHASE_YEAR'] . "</td><td>"
							. $row['ALBUM_ID'] . "</td><td>"
							. $row['NAME'] . "</td><td>"
							. $row['PRICE'] . "</td><td>"
							. $row['QUANTITY'] . "</td></tr>";
						//$price = OCI_Fetch_Array(executePlainSQL("select price from album where album_id=" . $row['ALBUM_ID']), OCI_BOTH);
						$totalprice += $row['QUANTITY'] * $row['PRICE'];
					}
				} else{
					echo "cannot connect";
					$e = OCI_Error(); // For OCILogon errors pass no handle
					echo htmlentities($e['message']);
				}
			?>
		</table>
		<?php echo "<p>Total Price: " . $totalprice . "</p>"?>
		<form method="POST" action="purchase_receipt.php?cust_email=<?php echo $email;?>">
			<input type="submit" name="back_to_browse" value="Back to Browse Page"/>
		</form>
	</body>
</html>

<?php 
if(array_key_exists('back_to_browse', $_POST)){
	OCILogoff($db_conn);
	header("location: cust_browse.php?cust_email=" . $email);
}
?>