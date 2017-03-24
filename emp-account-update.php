<!--Test Oracle file for UBC CPSC304 2011 Winter Term 2
  Created by Jiemin Zhang
  Modified by Simona Radu
  This file shows the very basics of how to execute PHP commands
  on Oracle.  
  specifically, it will drop a table, create a table, insert values
  update values, and then query for values
 
  IF YOU HAVE A TABLE CALLED "tab1" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the 
  OCILogon below to be your ORACLE username and password -->

<!-- 
<p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>
<form method="POST" action="oracle-test.php">

<p><input type="submit" value="Reset" name="reset"></p>
</form>

<p>Insert values into tab1 below:</p>
<p><font size="2"> Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
Name</font></p>
<form method="POST" action="oracle-test.php">
<!~~refresh page when submit~~>

   <p><input type="text" name="insNo" size="6"><input type="text" name="insName" 
size="18">
<!~~define two variables to pass the value~~>
      
<input type="submit" value="insert" name="insertsubmit"></p>
</form>
<!~~ create a form to pass the values. See below for how to 
get the values~~> 

<p> Update the name by inserting the old and new values below: </p>
<p><font size="2"> Old Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
New Name</font></p>
<form method="POST" action="oracle-test.php">
<!~~refresh page when submit~~>

   <p><input type="text" name="oldName" size="6"><input type="text" name="newName" 
size="18">
<!~~define two variables to pass the value~~>
      
<input type="submit" value="update" name="updatesubmit"></p>
<input type="submit" value="run hardcoded queries" name="dostuff"></p>
</form>
 -->

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
	echo "<br>Resulting Data:<br>";
	echo "<table>";
	//echo "<tr><th>ID</th><th>Name</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";

}

// Connect Oracle...
if ($db_conn) {
	$result = executePlainSQL("select * from branch_employee");
    //printEmployeeResult($result);
    // connected:
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
			//printEmployeeResult($result);
		} 
// 		else
// 		if (array_key_exists('insertsubmit', $_POST)) {
// 			//Getting the values from user and insert data into the table
// 			$tuple = array (
// 				":bind1" => $_POST['insNo'],
// 				":bind2" => $_POST['insName']
// 			);
// 			$alltuples = array (
// 				$tuple
// 			);
// 			executeBoundSQL("insert into tab1 values (:bind1, :bind2)", $alltuples);
// 			OCICommit($db_conn);
// 
// 		} else
// 			if (array_key_exists('updatesubmit', $_POST)) {
// 				// Update tuple using data from user
// 				$tuple = array (
// 					":bind1" => $_POST['oldName'],
// 					":bind2" => $_POST['newName']
// 				);
// 				$alltuples = array (
// 					$tuple
// 				);
// 				executeBoundSQL("update tab1 set name=:bind2 where name=:bind1", $alltuples);
// 				OCICommit($db_conn);
// 
// 			} else
// 				if (array_key_exists('dostuff', $_POST)) {
// 					// Insert data into table...
// 					executePlainSQL("insert into tab1 values (10, 'Frank')");
// 					// Inserting data into table using bound variables
// 					$list1 = array (
// 						":bind1" => 6,
// 						":bind2" => "All"
// 					);
// 					$list2 = array (
// 						":bind1" => 7,
// 						":bind2" => "John"
// 					);
// 					$allrows = array (
// 						$list1,
// 						$list2
// 					);
// 					executeBoundSQL("insert into tab1 values (:bind1, :bind2)", $allrows); //the function takes a list of lists
// 					// Update data...
// 					//executePlainSQL("update tab1 set nid=10 where nid=2");
// 					// Delete data...
// 					//executePlainSQL("delete from tab1 where nid=1");
// 					OCICommit($db_conn);
//				}

	if ($_POST && $success) {
		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
		header("location: oracle-test.php");
	} else {
		// Select data...
		//$result = executePlainSQL("select * from tab1");
		printResult($result);
	}

	//Commit to save changes...
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}

?>

