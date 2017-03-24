<p> <font size = "5"> Employee Update Page</font> </p>

<p> <font size = "3" color = "medblue">Edit Existing Employee Account: </font> </p>
<form method="POST" action="emp-account-update.php">
<p> Employee email: <input type="text" name="EmpEmail" size="24"></p>
<p> Information to update: </p>
<p> New Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 			<input type="text" name="NewEmpName" size="20"></p>
<p> New Password:		
			<input type="text" name="NewEmpPass" size="20"></p>
<p> New Branch#: &nbsp;	
			<input type="text" name="NewEmpBranch" size="5"></p>
<p><input type="submit" value="update account" name="UpdateEmpAccount"></p>
</form>

<p> <font size = "3" color = "medblue">Delete Existing Employee Account: </font> </p>
<form method="POST" action="emp-account-update.php">
<p> Employee email: <input type="text" name="EmployeeEmail" size="24"></p>
<p><input type="submit" value="delete account" name="DeleteEmpAccount"></p>
</form>


<?php

//this tells the system that it's no longer just parsing 
//html; it's now parsing PHP

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
	 parsed once. This is also very useful in protecting against SQL injection. See example code below for how this functions is used */

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

function printResult($result) { //prints results from a select statement
	//echo "<br>Resulting Data:<br>";
	echo "<table>";
	echo "<tr><th>ID</th><th>Name</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";

}

function printEmployeeResult($result) {
	echo "<br> All Employees: </br>";
	echo "<table>";
	echo "<tr>
            <th>Name:</th>
            <th>Email:</th>
            <th>Password:</th>
            <th>Branch #:</th>
        </tr>";
        
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
     echo "<tr><td>" . $row["name"] . "</td><td>" . $row["emp_email"] . "</td><td>" . $row["password"] . "</td><td>" . $row["branch_no"] . "</td></tr>";
    }
    echo "</table>";

}

// Connect Oracle...
if ($db_conn) {
	$result = executePlainSQL("select * from branch_employee");
	printEmployeeResult($result);
	
    if (array_key_exists('UpdateEmpAccount', $_POST)) {
			$EmpEmail   = $_POST['EmpEmail'];
			$NewEmpName = $_POST['NewEmpName'];
			$NewEmpPass = $_POST['NewEmpPass'];
			$NewEmpBranch = $_POST['NewEmpBranch'];
			if (!empty($NewEmpName) && isset($NewEmpName)) { // update name
			$tuple = array (
				":bind1" => $_POST['EmpEmail'],
				":bind2" => $_POST['NewEmpName'] 
				);
			$alltuples = array ($tuple);
			executeBoundSQL("update branch_employee set name=:bind2 where emp_email=:bind1", $alltuples);
			OCICommit($db_conn);
			}
			if (!empty($NewEmpPass) && isset($NewEmpPass)) {  //update password
			$tuple = array (
				":bind1" => $_POST['EmpEmail'],
				":bind2" => $_POST['NewEmpPass'] 
				);
			$alltuples = array ($tuple);
			executeBoundSQL("update branch_employee set password=:bind2 where emp_email=:bind1", $alltuples);
			OCICommit($db_conn);
			}
			if (!empty($NewEmpBranch) && isset($NewEmpBranch)) { //update branch number 
			$tuple = array (
				":bind1" => $_POST['EmpEmail'],
				":bind2" => $_POST['NewEmpBranch'] 
				);
			$alltuples = array ($tuple);
			executeBoundSQL("update branch_employee set branch_no=:bind2 where emp_email=:bind1", $alltuples);
			OCICommit($db_conn);
			}
			$result = executePlainSQL("select * from branch_employee");
			printEmployeeResult($result);
		} 
		
	OCILogoff($db_conn);
	
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}

?>


