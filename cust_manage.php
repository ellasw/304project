<?php
    $email = $_GET[cust_email];
?>

<head>
    <meta charset="UTF-8">
    <title>Customer Account Management</title>
</head>

<header>
    <h1>Customer Account Management</h1>
    <h2>Submit Changes to Customer Account Below:</h2>
</header>

<form method = "POST" action = "cust_browse.php?cust_email=<?php echo $email;?>">
    <p align="right">
        <input type="submit" value="Return" name="return"/>
    </p>
</form>

<p style="font-size: x-large">Change Name</p>

    <form method = "POST" action = "cust_manage.php?cust_email=<?php echo $email;?>">
    <p>
        <label for="cust_email">Enter Customer Email:</label><br>
        <input type="email" name="cust_email" size="30"><br>
        <label for="change_name">New Name:</label><br>
        <input type="text" name="newName" size="30"><br>
        <input type="submit" value="Update Name" name="NameSubmit"><br>
    </p>
</form>

<p style="font-size: x-large">Change Password</p>

    <form method = "POST" action = "cust_manage.php?cust_email=<?php echo $email;?>">
    <p>
        <label for="cust_email">Enter Customer Email:</label><br>
        <input type="email" name="cust_email" size="30"><br>
        <label for="newPassword">New Password:</label><br>
        <input type="text" name="newPassword" size="30"><br>
        <input type="submit" value="Update Password" name="PasswordSubmit"><br>
    </p>
</form>

<!--<p style="font-size: x-large">Delete Account</p>-->
<!---->
<!--<form method = "POST" action = "cust_manage.php">-->
<!--    <p>-->
<!--        <label for="cust_email">Customer Email:</label><br>-->
<!--        <input type="email" name="cust_email" size="30"><br>-->
<!--        <input type="submit" value="Delete" name="DeleteSubmit"><br>-->
<!--    </p>-->
<!--</form>-->
<!---->
<!--<p style="font-size: x-large">Delete Branch</p>-->
<!---->
<!--<form method = "POST" action = "cust_manage.php">-->
<!--    <p>-->
<!--        <label for="branch">Customer Email:</label><br>-->
<!--        <input type="number" name="branch" size="30"><br>-->
<!--        <input type="submit" value="Delete" name="BranchSubmit"><br>-->
<!--    </p>-->
<!--</form>-->

<?php

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_j2c0b", "a46509148", "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))");

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

if ($db_conn) {

    if (array_key_exists('NameSubmit', $_POST)) {
        // Update email with new email
        executePlainSQL("update customer set cust_name = '".$_POST[newName]."' WHERE cust_email = '".$_POST[cust_email]."'");
        OCICommit($db_conn);
    }
    elseif (array_key_exists('PasswordSubmit', $_POST)) {
        //Getting the values from user and insert data into the table
        executePlainSQL("update customer set cust_password = '".$_POST[newName]."' WHERE cust_email = '".$_POST[cust_email]."'");
        OCICommit($db_conn);
    }
//    elseif (array_key_exists('DeleteSubmit', $_POST)) {
//        //Getting the values from user and insert data into the table
//        executePlainSQL("delete from customer WHERE cust_email = '".$_POST[cust_email]."'");
//        OCICommit($db_conn);
//    }
    OCILogoff($db_conn);
}
else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>
