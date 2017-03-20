<?php 
$email = "jg@xyz.com"; // hardcoded email for now
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_t1m8", "a34564120", "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))");

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

function printCart($cart) {
	echo "<table>";
	echo "<tr><th>Album</th><th>Artist</th><th>Price</th><th>Quantity</th></tr>";

	while ($row = OCI_Fetch_Array($cart, OCI_BOTH)) {
		$temp = executePlainSQL("select name, artist from album where albumid=" . $row["ALBUMID"]);
		$row2 = OCI_Fetch_Array($temp, OCI_BOTH);
		echo "<tr><td>" . $row2["NAME"] . "</td><td>" . $row2["ARTIST"] . "</td><td>" . $row["PRICE"] . "</td><td>" . $row["QUANTITY"] . "</td></tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";
}

function tableExists($tablename){
	$statement = "select 1 from " . $tablename;
	$exists = OCIExecute($statement, OCI_DEFAULT);
	return ($exists) ? True : False;
}
?>

<html>
	<head>
		<title>Cart</title>
	</head>
	<body>
		<h1>Cart</h1>
		<div id="items">
			<?php
				if($db_conn){
					$cart = executePlainSQL("select * from cart where email='". $email . "'");
					printCart($cart);
				}else {
					echo "cannot connect";
					$e = OCI_Error(); // For OCILogon errors pass no handle
					echo htmlentities($e['message']);
				}
			?>
		</div>
		<div>
			<form method="POST" action="cart.php">
				Address: <input type="text" name="address"/><br/>
                City: <input type="text" name="city"/><br/>
                Province: <input type="text" name="province"/><br/>
                Postal Code: <input type="text" name="postal"/><br/>
				Date: <input type="text" name="date"/><br/>
                <h3>Payment</h3><br/>
                Name: <input type="text" name="name"/><br/>
                Card Number: <input type="text" name="cardno"/><br/>
                CVV: <input type="text" name="cvv"/><br/>
                EXP: mm/yy <input type="text" name="expiry"/><br/>
                <input type="submit" value="Make Purchase" name="purchase"/>
			</form>
		</div>
		
	</body>
</html>

<?php
if ($db_conn) {
	/*
	if(!tableExists('cart')){
		executePlainSQL("create table cart (email char(30), albumid integer, quantity integer, price float, primary key (email, albumid), foreign key (email) references customer, foreign key (albumid) references album)");
	}
	
	if(!tableExists('makes_purchase_of')){
		executePlainSQL("create table makes_purchase_of (orderno integer, orderdate char(8), totalprice float, email char(30), primary key (orderno), foreign key (email) references customer)");
	}
	
	if(!tableExists('purchase_consists_of')){
		executePlainSQL("create table purchase_consists of (orderno integer, albumid integer not null, quantity integer, primary key (orderno, albumid), foreign key (albumid) references album, foreign key (orderno) references makes_purchase_of)");
	}
	*/
	if (array_key_exists('purchase', $_POST)) {
		//$currorderid = 'orderseq.nextval';
		
		$cartitems = executePlainSQL("select albumid, quantity, price from cart where email='" . $email . "'");
		$totalprice = 0;
		$allconsists = array();
		while($row = OCI_Fetch_Array($cartitems, OCI_BOTH)){
			$totalprice += ($row["PRICE"] * $row["QUANTITY"]);
			array_push($allconsists, array(
				//":bind1" => $currorderid,
				":bind2" => $row["ALBUMID"],
				":bind3" => $row["QUANTITY"]
			));
		}
		
		$purchaseentry = array (
			//":bind1" => $currorderid,
			":bind2" => $_POST['date'],
			":bind3" => $totalprice,
			":bind4" => $email
		);
		$allpurchases = array (
			$purchaseentry
		);
		
		executeBoundSQL("insert into makes_purchase_of values (orderseq.nextval, :bind2, :bind3, :bind4)", $allpurchases);
		executeBoundSQL("insert into purchase_consists_of values (orderseq.currval, :bind2, :bind3)", $allconsists);
		OCICommit($db_conn);

	} 
	/*
	if ($_POST && $success) {
		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
		header("location: cart.php");
	} else {
		// Select data...
		$result = executePlainSQL("select * from cart");
		printResult($result);
	}
	*/
	//Commit to save changes...
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}
?>