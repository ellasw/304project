<html>
	<head>
		<meta charset="UTF-8">
		<title>Customer Login</title>
	</head>

	<body>
		<header>
			<h1>Customer Login</h1>
		</header>

		<p style="font-size: x-large">Have an Account? Log In Now:</p>

		<form method="POST" action="cust_login.php">
			<p>
				<label for="cust_email">Email</label><br>
				<input type="text" id = "cust_email" name="cust_email" size = "40"> <br><br>

				<label for="cust_password">Password</label><br>
				<input type="password" id = "cust_password" name="cust_password" size = "40"><br><br>
				<input type="submit" value="Log In" name = "cust_login">
			</p>
		</form>
		<br>
		<br>

		<p style="font-size: x-large">Create an Account</p>

		<form method="POST" action="cust_login.php">
			<p>
				<label for="name">Name</label><br>
				<input type="text" name="new_name_cust" size = "40"> <br><br>

				<label for="new_email">Email</label><br>
				<input type="email" name="new_email_cust" size = "40"> <br><br>

				<label for="new_password">Password</label><br>
				<input type="password" name = "new_password_cust" size = "40"><br><br>

				<input type="submit" value ="Create Customer Account" name="create_account_customer">
			</p>
		</form>
	</body>
</html>

<?php
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_a2v9a", "a17792145", "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))");
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
            //echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            //$e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
            //echo htmlentities($e['message']);
            //echo "<br>";
            $success = False;
        }
    }
}
function printResult($result) { //prints results from a select statement
    echo "<br>Customers:<br>";
    echo "<table>";
    echo "<tr><th>Email</th><th>Password</th></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["CUST_NAME"] . "</td><td>" . $row["CUST_EMAIL"] . "</td><td>" . $row["CUST_PASSWORD"] . "</td></tr>"; //or just use "echo $row[0]"
    }
    echo "</table>";
}
// Connect Oracle...
if ($db_conn) {
    if (array_key_exists('create_account_customer', $_POST)) {
        //Getting the values from user and insert data into the table
        $tuple = array(
            ":bind1" => $_POST['new_name_cust'],
            ":bind2" => $_POST['new_email_cust'],
            ":bind3" => $_POST['new_password_cust']
        );
        $alltuples = array(
            $tuple
        );
        executeBoundSQL("insert into customer values (:bind2, :bind1, :bind3)", $alltuples);
        OCICommit($db_conn);
        if ($_POST && $success) {
            header("location: http://www.ugrad.cs.ubc.ca/~a2v9a/create_acc_confirm.php");
        } else {
			echo "An account with the given email already exists. Please Login.";
            $result = executePlainSQL("select * from customer");
            printResult($result);
        }
        //Commit to save changes...
        OCILogoff($db_conn);
    }
    elseif (array_key_exists('cust_login', $_POST)) {
        //Getting the values from user and insert data into the table
        $result = executePlainSQL("select Count(*) AS cemail from customer WHERE cust_email = '".$_POST['cust_email']."' AND cust_password = '".$_POST['cust_password']."'");
        $resultarray = OCI_Fetch_Array($result, OCI_BOTH);
        if ($resultarray["CEMAIL"] > 0){
            header("location: http://www.ugrad.cs.ubc.ca/~a2v9a/cust_browse.php?cust_email=" . $_POST['cust_email']);
            exit;
		}
        else{
            echo "Invalid Login. Please try again.";
        }
        OCILogoff($db_conn);
    }
}
else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>
