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

<p> <font size = "3" color = "red">Delete Existing Employee Account: </font> </p>
<form method="POST" action="emp-account-update.php">
<p> Employee email: <input type="text" name="EmpEmailDel" size="24"></p>
<p><input type="submit" value="delete account" name="DeleteEmpAccount"></p>
</form>


<p> <font size = "3" color = "medblue">Edit Existing Customer Account: </font> </p>
<form method="POST" action="emp-account-update.php">
<p> Customer email: <input type="text" name="CustEmailUp" size="24"></p>
<p> Information to update: </p>
<p> New Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 			<input type="text" name="NewCustName" size="20"></p>
<p> New Password:		
			<input type="text" name="NewCustPass" size="20"></p>
<p><input type="submit" value="update account" name="UpdateCustAccount"></p>
</form>

<!-- 
<p> <font size = "3" color = "red">Delete Existing Customer Account: </font> </p>
<form method="POST" action="emp-account-update.php">
<p> Customer email: <input type="text" name="CustEmailDel" size="24"></p>
<p><input type="submit" value="delete account" name="DeleteCustAccount"></p>
</form>
 -->

<p> <font size = 3 color = "medblue"> Add a new branch: </font> </p>
<form method="POST" action="emp-account-update.php">
<p> Branch #:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="number" name="BranchNo"></p>
<p> Address: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="text" name="BranchAddress" size="30"></p>
<p> City: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="text" name="BranchCity" size="20"></p>
<p> Province:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		<input type="text" name="BranchProv" size="5"></p>
<p> Postal Code:&nbsp; 
		<input type="text" name="BranchZip" size="10"></p>
<p><input type="submit" value="add branch" name="AddBranch"></p>
</form>


<p> <font size = "3" color = "medblue">Edit Existing Branch: </font> </p>
<form method="POST" action="emp-account-update.php">
<p> Branch #: <input type="number" name="BranchNoUp"></p>
<p> Information to update: </p>
<p> New Address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 			<input type="text" name="NewBranchAddress" size="30"></p>
<p> New City:		
			<input type="text" name="NewBranchCity" size="20"></p>
<p> New Province:
			<input type="text" name="NewBranchProv" size="5"></p>
<p> New Postal Code:
			<input type="text" name="NewBranchZip" size="10"></p>
<p><input type="submit" value="update branch" name="UpdateCustAccount"></p>
</form>

<p> <font size = "3" color = "red">Delete Existing Branch: </font> </p>
<form method="POST" action="emp-account-update.php">
<p> Branch #: <input type="number" name="BranchNoDel" size="24"></p>
<p><input type="submit" value="delete branch" name="DeleteBranch"></p>
</form>

<form method="POST" action="emp-account-update.php">
<input type="submit" value="show all customers" name="ShowCust">
<input type="submit" value="show all employees" name="ShowEmp">
<input type="submit" value="show all branches" name="ShowBranch">
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


function printEmployee($result) {
	echo "<br> All Employees: </br>";
	echo "<table>";
	echo "<tr>
            <th>Name:</th>
            <th>Email:</th>
            <th>Branch #:</th>
        </tr>";
        
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
     echo "<tr><td>" . $row["NAME"] . "</td><td>" . $row["EMP_EMAIL"] . "</td><td>" . $row["BRANCH_NO"] . "</td></tr>";
    }
    echo "</table>";

}

function printCustomer($result) { //prints results from a select statement
    echo "<br>Got data from table customer:<br>";
    echo "<table>";
    echo "<tr><th>Email</th><th>Name</th></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["CUST_EMAIL"] . "</td><td>" . $row["CUST_NAME"] . "</td></tr>"; //or just use "echo $row[0]"
    }
    echo "</table>";
}

function printBranch($result) {
	echo "<p> Existing Branches:</p>";
	echo "<table>";
	echo "<tr><th>Branch #:</th><th>Address:</th><th>City:</th><th>Province:</th><th>Postal Code:</th></tr>";
	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["BRANCH_NO"] . "</td><td>" . $row["STREET_NO"] . "</td><td>" . $row["CITY"] . "</td><td>" . $row["PROVINCE"] . "</td><td>" . $row["POSTAL_CODE"] . "</td></tr>"; //or just use "echo $row[0]"
    }
    echo "</table>";
}

// Connect Oracle...
if ($db_conn) {
// 				$result = executePlainSQL("select * from customer");
// 				printCustomer($result);
				// 				$result = executePlainSQL("select * from branch");
				// printBranch($result);


	// $result = executePlainSQL("select * from branch_employee");
	//  	printEmployee($result);
	
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
			//here should add check to see if given branchNo is in the database. If not list all the branch numbers that exist 
			$tuple = array (
				":bind1" => $_POST['EmpEmail'],
				":bind2" => $_POST['NewEmpBranch'] 
				);
			$alltuples = array ($tuple);
			executeBoundSQL("update branch_employee set branch_no=:bind2 where emp_email=:bind1", $alltuples);
			OCICommit($db_conn);
			}
			$result = executePlainSQL("select * from branch_employee");
			printEmployee($result);
		} 

		else if (array_key_exists('UpdateCustAccount', $_POST)) {
					$CustEmailUp   = $_POST['CustEmailUp'];
					$NewCustName = $_POST['NewCustName'];
					$NewCustPass = $_POST['NewCustPass'];
				if (!empty($NewCustName) && isset($NewCustName)) {
				$tuple = array (
					":bind1" => $_POST['CustEmailUp'],
					":bind2" => $_POST['NewCustName']
				);
				$alltuples = array ($tuple);
				executeBoundSQL("update customer set cust_name=:bind2 where cust_email=:bind1", $alltuples);
				OCICommit($db_conn);
				}
				if (!empty($NewCustPass) && isset($NewCustPass)) {
				$tuple = array (
					":bind1" => $_POST['CustEmailUp'],
					":bind2" => $_POST['NewCustPass']
				);
				$alltuples = array ($tuple);
				executeBoundSQL("update customer set cust_password=:bind2 where cust_email=:bind1", $alltuples);
				OCICommit($db_conn);
				}
				$result = executePlainSQL("select * from customer");
				printCustomer($result);

				}
				
		else if (array_key_exists('DeleteEmpAccount', $_POST)) {
			$tuple = array (
				":bind1" => $_POST['EmpEmailDel']
			);
			$alltuples = array ($tuple);
			executeBoundSQL("delete from branch_employee where emp_email=:bind1", $alltuples);
			OCICommit($db_conn);
			$result = executePlainSQL("select * from branch_employee");
		 	printEmployee($result);
		//delete employee account
		}
				
				
		else if (array_key_exists('AddBranch', $_POST)) {
				if (empty($_POST['BranchNo']) || empty($_POST['BranchAddress']) || empty($_POST['BranchCity']) || empty($_POST['BranchProv']) || empty($_POST['BranchZip'])) {
				echo "All fields must be completed";
				} else {
				$tuple = array (
					":bind1" => $_POST['BranchNo'],
					":bind2" => $_POST['BranchAddress'],
					":bind3" => $_POST['BranchCity'],
					":bind4" => $_POST['BranchProv'],
					":bind5" => $_POST['BranchZip'] );
				$alltuples = array ($tuple);
				executeBoundSQL("insert into branch values (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
				OCICommit($db_conn);
				$result = executePlainSQL("select * from branch");
				printBranch($result);

					}
		}
		
		else if (array_key_exists('DeleteBranch', $_POST)) {
			$tuple = array (
				":bind1" => $_POST['BranchNoDel']
			);
			$alltuples = array ($tuple);
			executeBoundSQL("delete from branch where branch_no=:bind1", $alltuples);
			OCICommit($db_conn);
			$result = executePlainSQL("select * from branch");
		 	printBranch($result);
			$result = executePlainSQL("select * from branch_employee");
			 	printEmployee($result);
			
		} else if (array_key_exists('ShowEmp', $_POST)) {
			$result = executePlainSQL("select * from branch_employee");
			printEmployee($result);
		} else if (array_key_exists('ShowCust', $_POST)) {
			$result = executePlainSQL("select * from customer");
			printCustomer($result);
			
		} else if (array_key_exists('ShowBranch', $_POST)) {
			$result = executePlainSQL("select * from branch");
			printBranch($result);

		}
		
		 
// 		if($_POST && success){
// 		header("location: emp-account-update.php");
// 	}else{
// 		$result = executePlainSQL("select * from branch_employee");
// 		printEmployeeResult($result);
// 	}		
	OCILogoff($db_conn);
	
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}

?>

