<?php 
	//session_start();
	$email = $_GET['emp_email'];
	echo "Welcome " . $email;
?>

<html>
	<head>
		<meta charset="UTF-8">
		<title>Employee Browse</title>
	</head>

	<body>
		<header>
			<h1>Employee Browse</h1>
		</header>

		<p align="right"><input type="submit" value="Log Out" name="logout"/>
		</p><br>
		<p align="right"><input type="submit" value="Cart" name="cart"/>
		</p><br>


		</form>


		<p style="font-size: x-large">Search in the following fields:</p>

		<p>
			<label for="search for song">Search For Song:</label><br>
			<input type="text" name = "search for song" name="Search For Song" size = "40">
			<input type="submit" value="Search" name= search>  <br><br>

			<label for="search for album">Search For Album:</label><br>
			<input type="text" name = "search for album" name="Search For Album" size = "40">
			<input type="submit" value= "Search" name="searchAlbum" size = "40"><br><br>

			<label for="search for album">Search For Artist:</label><br>
			<input type="text" name = "search for artist" name="Search For Artist" size = "40">
			<input type="submit" value= "Search" name="searchArtist" size = "40"><br><br>

		</p>
		</form>
		<br>
		<br>

		<p>
			<label for="results"style = "font-size: x-large"> Results:</label><br>
		<table>
			<tr>
				<th>
					Album Name:
				</th>
				<th>
					Artist:
				</th>
				<th>
					Year:
				</th>
				<th>
					Songs:
				</th>
				<th>
					Genre:
				</th>
				<th>
					Price:
				</th>
				<th>
					Stock:
				</th>
			</tr>
			<td>
				-
			</td>
			<td>
				-
			</td>

		</table>
		<input type="submit" value = "Add To Cart" name = "Add To Cart">
		</p>
	</body>
</html>

<?php

//this tells the system that it's no longer just parsing
//html; it's now parsing PHP
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_j2c0b", "a46509148", "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))");
//$db_conn = $_SESSION['db_conn'];

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

function printResult($result) { //prints results from a select statement
    echo "<br>Got data from table tab1:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
    }
    echo "</table>";

}

//function create_table(){
//    global $db_conn;
//    if ($db_conn){
//        echo "<br> dropping table <br>";
//        executePlainSQL("Drop table tab1");
//
//        // Create new table...
//        echo "<br> creating new table <br>";
//        executePlainSQL("create table tab1 (nid number, name varchar2(30), primary key (nid))");
//        OCICommit($db_conn);
//
//    }
//}

// Connect Oracle...
if ($db_conn) {
//if($_SESSION['db_conn']){
	if (array_key_exists('reset', $_POST)) {
        // Drop old table...
        echo "<br> dropping table <br>";
        executePlainSQL("Drop table tab1");

        // Create new table...
        echo "<br> creating new table <br>";
        executePlainSQL("create table tab1 (nid number, name varchar2(30), primary key (nid))");
        OCICommit($db_conn);
    }else
        if (array_key_exists('createaccount', $_POST)) {
            //Getting the values from user and insert data into the table
            $tuple = array(
                ":bind1" => $_POST['new_name'],
                ":bind2" => $_POST['new_password']
            );
            $alltuples = array(
                $tuple
            );
            executeBoundSQL("insert into tab1 values (:bind1, :bind2)", $alltuples);
            OCICommit($db_conn);


            if ($_POST && $success) {
                //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
                header("location: http://www.ugrad.cs.ubc.ca/~t1m8/emp_login.php");
            } else {
                // Select data...
                $result = executePlainSQL("select * from tab1");
                printResult($result);
            }

            //Commit to save changes...
            OCILogoff($db_conn);
        }
}
else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>