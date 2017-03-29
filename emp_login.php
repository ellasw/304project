<html>
	<head>
		<meta charset="UTF-8">
		<title>Employee Login</title>
	</head>

	<body>
		<header>
			<h1>Employee Login</h1>
		</header>

		<p style="font-size: x-large">Have an Account? Log In Now:</p>

		<form method="POST" action="emp_login.php">
			<p>
				<label for="email">Email</label><br>
				<input type="email" id = "emp_email" name="emp_email" size = "40"> <br><br>

				<label for="password">Password</label><br>
				<input type="password" id = "emp_password" name="emp_password" size = "40"><br><br>

				<input type="submit" value="Log In" name = "emp_login">
			</p>
		</form>
		<br>
		<br>

		<p style="font-size: x-large">Create an Account</p>

		<form method="POST" action="emp_login.php">
			<p>
				<label for="name">Name</label><br>
				<input type="text" name="new_name_emp" size = "40"> <br><br>

				<label for="new_email">Email</label><br>
				<input type="email" name="new_email_emp" size = "40"> <br><br>

				<label for="new_password">Password</label><br>
				<input type="password" name = "new_password_emp" size = "40"><br><br>

				<label for="branchNo">Branch ID#</label><br>
				<input type="" name = "branchNo" size = "40"><br><br>

				<input type="submit" value ="Create Employee Account" name="create_account_employee">
			</p>
		</form>
	</body>
</html>

<?php

//this tells the system that it's no longer just parsing
//html; it's now parsing PHP

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

// Connect Oracle...
if ($db_conn) {

    if (array_key_exists('create_account_employee', $_POST)) {
        //Getting the values from user and insert data into the table
        $tuple = array(
            ":bind1" => $_POST['new_name_emp'],
            ":bind2" => $_POST['new_email_emp'],
            ":bind3" => $_POST['new_password_emp'],
            ":bind4" => $_POST['branchNo'],

        );
        $alltuples = array(
            $tuple
        );
        executeBoundSQL("insert into branch_employee values (:bind2, :bind1, :bind3, :bind4)", $alltuples);
        OCICommit($db_conn);

        if ($_POST && $success) {
            //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
            header("location: create_acc_confirm.php");
        } else {
            // Select data...
            $result = executePlainSQL("select * from branch_employee");
        }

        //Commit to save changes...
        OCILogoff($db_conn);
    }
    elseif (array_key_exists('emp_login', $_POST)) {
        //Getting the values from user and insert data into the table
        $result = executePlainSQL("select Count(*) AS eemail from branch_employee WHERE emp_email = '" . $_POST['emp_email'] . "' AND password = '" . $_POST['emp_password'] . "'");
        $resultarray = OCI_Fetch_Array($result, OCI_BOTH);
        if ($resultarray["EEMAIL"] > 0) {
			header("location: emp_browse.php?emp_email=" . $_POST['emp_email']);
            exit;
        } else {
            echo "Invalid Login. Please try again.";
        }
    }
}
else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>
