<?php 
	$email = $_GET['cust_email'];
?>

<head>
    <meta charset="UTF-8">
    <title>Browse</title>
</head>

<header>
    <h1>Browse</h1>
</header>
<form method="POST" action="mainlogin.php">
    <p align="right">
        <input type="submit" value="Log Out" name="logout"/>
    </p>
</form>

<br>

<form method="POST" action="cart.php?cust_email=<?php echo $email;?>">
    <p align="right">
        <input type="submit" value="Cart" name="cart"/>
    </p>
</form>


<p style="font-size: x-large">Search in the following fields:</p>

<form method = "POST" action = "cust_browse.php?cust_email=<?php echo $email;?>">
    <p>
        <label for="song_search_input">Search For Song:</label><br>
        <input type="text" id = "song_search_input" name = "song_search_input" size = "40">
        <input type="submit" value="Search" name= song_search_submit>
    </p>
</form>

<form method = "POST" action = "cust_browse.php?cust_email=<?php echo $email;?>">
    <p>
        <label for="album_search_input">Search For Album:</label><br>
        <input type="text" id = "album_search_input" name = "album_search_input" size = "40">
        <input type="submit" value="Search" name= album_search_submit>
    </p>
</form>

<form method = "POST" action = "cust_browse.php?cust_email=<?php echo $email;?>">
    <p>
        <label for="artist_search_input">Search For Artist:</label><br>
        <input type="text" id = "artist_search_input" name = "artist_search_input" size = "40">
        <input type="submit" value="Search" name= artist_search_submit>
    </p>
</form>

<br>

<p style="font-size: x-large">Add Album to Cart</p>
<form method = "POST" action = "cust_browse.php?cust_email=<?php echo $email;?>">
    <p>
        <label for="cart_input">AlbumID</label><br>
        <input type="number" id = "cart_input" name = "cart_input" size = "15"><br>
        <label for="email_cart_input">Email</label><br>
        <input type="email" id = "email_cart_input" name = "email_cart_input" size = "50"><br>
        <label for="cart_quantity">Quantity</label><br>
        <input type="number" id = "cart_quantity" name = "cart_quantity" size = "15"><br>
        <input type="submit" value="Add to Cart" name= cart_submit><br>
    </p>
</form>

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

function printResult($result) { //prints results from a select statement
    echo "<p style='font-size: x-large'>Results From Your Search:</p>";
    echo "<table>";
    echo "<tr>
            <th>AlbumID:</th>
            <th>Minimum Stock:</th>
            <th>Stock:</th>
            <th>Price:</th>
            <th>Year:</th>
            <th>Name:</th>
            <th>Genre:</th>
            <th>Artist:</th>
        </tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ALBUM_ID"] . "</td><td>" . $row["MINIMUM_STOCK"] . "</td><td>" . $row["STOCK"] . "</td><td>" . $row["PRICE"] . "</td><td>" . $row["YEAR"] . "</td><td>" . $row["NAME"] . "</td><td>" . $row["GENRE"] . "</td><td>" . $row["ARTIST"] . "</td></tr>";
    }
    echo "</table>";

}

function printSongResult($result) { //prints results from a select statement
    echo "<p style='font-size: x-large'>Results From Your Search:</p>";
    echo "<table>";
    echo "<tr>
            <th>AlbumID:</th>
            <th>Year:</th>
            <th>Name:</th>
            <th>Genre:</th>
            <th>Artist:</th>
            <th>Song ID:</th>
            <th>Song Title:</th>
        </tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ALBUM_ID"] . "</td><td>" . $row["YEAR"] . "</td><td>" . $row["NAME"] . "</td><td>" . $row["GENRE"] . "</td><td>" . $row["ARTIST"] . "</td><td>" . $row["SONG_ID"] . "</td><td>" . $row["SONG_TITLE"] . "</td></tr>";
    }
    echo "</table>";

}

function printConfirm() { //prints results from a select statement
    echo "Item added to Cart";
}

// Connect Oracle...
if ($db_conn) {
	echo "Welcome, " . $_GET['cust_email'];
    if (array_key_exists('song_search_submit', $_POST)) {
        // Retrieve input from Song Search
        $result = executePlainSQL("SELECT album_has_song.song_id, album_has_song.song_title, album.album_id, album.name, album.artist, album.genre, album.year FROM album INNER JOIN album_has_song ON album.album_id=album_has_song.album_id AND album_has_song.song_title LIKE '%".$_POST['song_search_input']."%' ORDER BY album.artist");
        OCICommit($db_conn);
        printSongResult($result);
    } elseif (array_key_exists('album_search_submit', $_POST)) {
        // Retrieve input from Album Search
        $result = executePlainSQL("select * from album WHERE name LIKE '%".$_POST['album_search_input']."%'");
        OCICommit($db_conn);
        printResult($result);
    } elseif (array_key_exists('artist_search_submit', $_POST)) {
        // Retrieve input from Artist Search
        $result = executePlainSQL("select * from album WHERE artist LIKE '%".$_POST['artist_search_input']."%'");
        OCICommit($db_conn);
        printResult($result);
    }
    elseif (array_key_exists('cart_submit', $_POST)) {
        // $stock = executePlainSQL("select stock from album where album_id = $_POST[cart_input]");
        // if ($_POST[cart_quantity] <= stock {
        // Retrieve input from Artist Search
        $result = executePlainSQL("insert into cart values('".$_POST['email_cart_input']."', ".$_POST['cart_input'].", ".$_POST['cart_quantity'].")");
        OCICommit($db_conn);
        printConfirm();
    //} else { print an error that quantity entered exceeded stock limit of $stock}
    }
    OCILogoff($db_conn);
}

else {
    echo "CANNOT CONNECT. CONNECTION NONEXISTENT.";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);

}
?>
