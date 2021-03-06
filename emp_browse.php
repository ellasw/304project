<?php 
$email = $_GET['emp_email'];
$success = True;
$db_conn = OCILogon("ora_a2v9a", "a17792145", "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))");
function executePlainSQL($cmdstr) {
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr); 
    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }
    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement);
        echo htmlentities($e['message']);
        $success = False;
    } else {
    }
    return $statement;
}
function executeBoundSQL($cmdstr, $list) {
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
function printAlbumNoStock($result) { //prints results from a select statement
    echo "<p style='font-size: x-large'>Results From Your Search:</p>";
    echo "<table>";
    echo "<tr>
            <th>AlbumID:</th>
            <th>Price:</th>
            <th>Year:</th>
            <th>Name:</th>
            <th>Genre:</th>
            <th>Artist:</th>
        </tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ALBUM_ID"] . "</td><td>" . $row["PRICE"] . "</td><td>" . $row["YEAR"] . "</td><td>" . $row["NAME"] . "</td><td>" . $row["GENRE"] . "</td><td>" . $row["ARTIST"] . "</td></tr>";
    }
    echo "</table>";
}
function printSongResult($result) { //prints results from a select statement
    echo "<p style='font-size: x-large'>Results From Your Search:</p>";
    echo "<table>";
    echo "<tr>
            <th>AlbumID:</th>
			<th>Minimum Stock:</th>
			<th>Stock:</th>
            <th>Year:</th>
            <th>Name:</th>
            <th>Genre:</th>
            <th>Artist:</th>
            <th>Song ID:</th>
            <th>Song Title:</th>
        </tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ALBUM_ID"] . "</td><td>" . $row["MINIMUM_STOCK"] . "</td><td>" . $row["STOCK"] . "</td><td>" . $row["YEAR"] . "</td><td>" . $row["NAME"] . "</td><td>" . $row["GENRE"] . "</td><td>" . $row["ARTIST"] . "</td><td>" . $row["SONG_ID"] . "</td><td>" . $row["SONG_TITLE"] . "</td></tr>";
    }
    echo "</table>";
}
function printSongResultNoStock($result) { //prints results from a select statement
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

function printAlbum($result) { //prints results from a select statement
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
function printEmptyError() {
	echo "<p style='font-size: x-large'>To add an album, all fields must be completed </p>";
}

function printInputError() {
	echo "<p style='font-size: x-large'>Album ID, Stock, Minimum Stock, Year and Price must be numbers.</p>";
	}
?>

<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard</title>
</head>

<header>
    <h1>Welcome, <?php echo $email;?></h1>
</header>

<form method="POST" action="emp_browse.php?emp_email=<?php echo $email;?>">
    <div align="right">
		<input type="submit" value="Go To Edit Accounts" name="update_account"/>
        <input type="submit" value="Log Out" name="logout"/>
    </div>
	
	<div id="search_by_song">
        <label for="song_search_input">Search For Song:</label><br>
        <input type="text" id="song_search_input" name="song_search_input" size="40">
		<input type="checkbox" name="displayStockSong" value="displayStockSong" checked> Stock Information<br>
        <input type="submit" value="Search" name="song_search_submit">
		
    </div>
	<div id="search_by_album">
        <label for="album_search_input">Search For Album:</label><br>
        <input type="text" id="album_search_input" name="album_search_input" size="40">
		<input type="checkbox" name="displayStockAlbum" value="displayStockAlbum" checked> Stock Information<br>
        <input type="submit" value="Search" name="album_search_submit">
    </div>
	<div id="search_by_artist">
        <label for="artist_search_input">Search For Artist:</label><br>
        <input type="text" id="artist_search_input" name="artist_search_input" size="40">
		<input type="checkbox" name="displayStockArtist" value="displayStockArtist" checked> Stock Information<br>
        <input type="submit" value="Search" name="artist_search_submit">
    </div>
	
	<h3>Add Album</h3>
	<div id="add_albums">
		Album ID:<input type="number" name="AlbumID" size="3"/><br/>
		Album Name: <input type="text" name="AlbumName" size="10"/><br/>
		Genre: <input type="text" name="AlbumGenre" size="10"/><br/>
		Artist: <input type="text" name="AlbumArtist" size="10"/><br/>
		Price:<input type="float" name="AlbumPrice" size="3"/><br/>
		Year:<input type="number" name="AlbumYear" size="3"/><br/>
		Stock:<input type="number" name="AlbumStock" size="3"/><br/>
		Minimum Stock:<input type="number" name="AlbumMinStock" size="3"/><br/>
		<input type="submit" name="add_album" value="Add Album"/>
	</div>
	
	<h3>Update Album</h3>
	<div id="update_albums">
		Album ID:<input type="number" name="album_to_update" size="3"/><br/>
		Update Minimum Stock:<input type="text" name="minstock_to_update" size="3"/>
		<input type="submit" name="update_minstock" value="Update Min Stock"/><br/>
		Update Album Stock:<input type="text" name="stock_to_update" size="3"/>
		<input type="submit" name="update_stock" value="Update Stock"/><br/>
		Update Album Price:<input type="text" name="price_to_update"/>
		<input type="submit" name="update_price" value="Update Price"/>
	</div>
	
	<h3>Delete Album</h3>
	<div id="delete_album">
		Album ID:<input type="number" name="albumID_to_delete" size="3"/><br/>
		<input type="submit" name="delete_album" value="Delete Album"/>
		</div>
	
	<h3>List of Purchases</h3>
	<div id="list_purchases">
		Purchase Month:<input type="text" name="purchase_month" size="2"/><br/>
		Purchase Year:<input type="text" name="purchase_year" size="4"/><br/>
		<input type="submit" name="get_purchases" value="Get Purchases"/>
	</div>
	
	<h3>Get Stats</h3>
	<div id="get_stats">
		<input type="submit" name="get_min_price_album" value="Cheapest Album"/>
		<input type="submit" name="get_max_price_album" value="Most Expensive Album"/>
		<input type="submit" name="get_avg_price_all_albums" value="Average Price of All Albums"/>
		<input type="submit" name="get_total_price_all_albums" value="Total Price of All Albums"/>
		<input type="submit" name="get_avg_price_albums_by_genre" value="Average Prices of Each Genre"/>
		<input type="submit" name="get_customers_bought_all" value="Customers Who Bought All Albums"/>
		<input type="submit" name="get_min_price_album_by_genre" value="Minimum Price of Each Genre"/>
		<input type="submit" name="get_max_price_album_by_genre" value="Maximum Price of Each Genre"/>
		<input type="submit" name="get_count_of_all_albums" value="Count of All Albums"/>
	</div>
</form>

<?php
// Connect Oracle...
if ($db_conn) {
	if (array_key_exists('song_search_submit', $_POST)) {
        // Retrieve input from Song Search
		if (isset($_POST['displayStockSong'])){
        $result = executePlainSQL("SELECT album_has_song.song_id, album.minimum_stock, album.stock, album_has_song.song_title, album.album_id, album.name, album.artist, album.genre, album.year FROM album INNER JOIN album_has_song ON album.album_id=album_has_song.album_id AND album_has_song.song_title LIKE '%".$_POST['song_search_input']."%' ORDER BY album.artist");
        OCICommit($db_conn);
        printSongResult($result);
	} else {
        $result = executePlainSQL("SELECT album_has_song.song_id, album_has_song.song_title, album.album_id, album.name, album.artist, album.genre, album.year FROM album INNER JOIN album_has_song ON album.album_id=album_has_song.album_id AND album_has_song.song_title LIKE '%".$_POST['song_search_input']."%' ORDER BY album.artist");
        OCICommit($db_conn);
        printSongResultNoStock($result);
		
	}
    } 
	elseif (array_key_exists('album_search_submit', $_POST)) {
        // Retrieve input from Album Search
		if (isset($_POST['displayStockAlbum'])){
        $result = executePlainSQL("select * from album WHERE name LIKE '%".$_POST['album_search_input']."%'");
        OCICommit($db_conn);
        printResult($result);
	} else {        
		$result = executePlainSQL("select * from album WHERE name LIKE '%".$_POST['album_search_input']."%'");
		        OCICommit($db_conn);
		        printAlbumNoStock($result);
//echo "don't show stock";
} //else don't display the stock
    } 
	elseif (array_key_exists('artist_search_submit', $_POST)) {
        // Retrieve input from Artist Search
		if (isset($_POST['displayStockArtist'])){
        $result = executePlainSQL("select * from album WHERE artist LIKE '%".$_POST['artist_search_input']."%'");
        OCICommit($db_conn);
        printResult($result);
	} else {	
        $result = executePlainSQL("select * from album WHERE artist LIKE '%".$_POST['artist_search_input']."%'");
        OCICommit($db_conn);
        printAlbumNoStock($result);
		}
	} 
	elseif(array_key_exists('update_account', $_POST)){
		header("location: emp-account-update.php?emp_email=" . $email);
	}
	elseif (array_key_exists('logout', $_POST)){
		header("location: mainlogin.php");
	}
	elseif(array_key_exists('update_minstock', $_POST)){
		$album_id = $_POST['album_to_update'];
		$minstock = $_POST['minstock_to_update'];
		if(!empty($album_id) && isset($album_id) && !empty($minstock) && isset($minstock)){
			executePlainSQL("update album set minimum_stock=" . $minstock . " where album_id=" . $album_id);
			OCICommit($db_conn);
        	$result = executePlainSQL("select * from album order by album_id");
        	printAlbum($result);
		}
		else{
			echo "Cannot update minstock. Please enter valid album_id and minstock";
		}
	}
	elseif(array_key_exists('update_stock', $_POST)){
		$album_id = $_POST['album_to_update'];
		$stock = $_POST['stock_to_update'];
		if(!empty($album_id) && isset($album_id) && !empty($stock) && isset($stock)){
			executePlainSQL("update album set stock=" . $stock . " where album_id=" . $album_id);
			OCICommit($db_conn);
        	$result = executePlainSQL("select * from album order by album_id");
        	printAlbum($result);
			
		}
		else{
			echo "Cannot update stock. Please enter valid album_id and stock";
		}
	}
	elseif(array_key_exists('update_price', $_POST)){
		$album_id = $_POST['album_to_update'];
		$price = $_POST['price_to_update'];
		if(!empty($album_id) && isset($album_id) && !empty($price) && isset($price)){
			executePlainSQL("update album set price=" . $price . " where album_id=" . $album_id);
			OCICommit($db_conn);
        	$result = executePlainSQL("select * from album order by album_id");
        	printAlbum($result);
			
		}
		
		else{
			echo "Cannot update price. Please enter valid album_id and price";
		}
	}
	elseif(array_key_exists('get_purchases', $_POST)){
		$pmonth = $_POST['purchase_month'];
		$pyear = $_POST['purchase_year'];
		if(!empty($pmonth) && isset($pmonth) && !empty($pyear) && isset($pyear)){
			$purchases = executePlainSQL("select m.purchase_no as pno, m.cust_email as email, m.purchase_month as month, m.purchase_year as year, p.album_id as album_id, p.quantity as quantity from makes_purchase m, purchase_has_album p where m.purchase_no=p.purchase_no and m.purchase_month=" . $pmonth . " and m.purchase_year=" . $pyear);
			echo "<table><tr><th>Purchase No</th><th>Customer</th><th>Purchase Month</th><th>Purchase Year</th><th>Album ID</th><th>Quantity</th></tr>";
			while($row = OCI_Fetch_Array($purchases, OCI_BOTH)){
				echo "<tr><td>" . $row['PNO'] . "</td><td>" . $row['EMAIL'] . "</td><td>" . $row['MONTH'] . "</td><td>" . $row['YEAR'] . "</td><td>" . $row['ALBUM_ID'] . "</td><td>" . $row['QUANTITY'] . "</td></tr>";
			}
			echo "</table>";
			OCICommit($db_conn);
		}
		else{
			echo "Cannot get list of purchases. Please enter valid purchase month and year";
		}
	}
	elseif(array_key_exists('get_min_price_album', $_POST)){
		$album = executePlainSQL("select album_id, price from album where price <= (select min(price) from album)");
		$minprice = OCI_Fetch_Array(executePlainSQL("select min(price) as minprice from album"), OCI_BOTH);
		echo "<table><tr><th>Album ID</th><th>Price</th></tr>";
		while($row = OCI_Fetch_Array($album, OCI_BOTH)){
			echo "<tr><td>" . $row['ALBUM_ID'] . "</td><td>" . $row['PRICE'] . "</td></tr>";
		}
		echo "</table><br/>Minimum Price of Albums: " . $minprice["MINPRICE"];
		OCICommit($db_conn);
		
	}
	elseif(array_key_exists('get_max_price_album', $_POST)){
		$album = executePlainSQL("select album_id, price from album where price >= (select max(price) from album)");
		$maxprice = OCI_Fetch_Array(executePlainSQL("select max(price) as maxprice from album"), OCI_BOTH);
		echo "<table><tr><th>Album ID</th><th>Price</th></tr>";
		while($row = OCI_Fetch_Array($album, OCI_BOTH)){
			echo "<tr><td>" . $row['ALBUM_ID'] . "</td><td>" . $row['PRICE'] . "</td></tr>";
		}
		echo "</table><br/>Maximum Price of Albums: " . $maxprice["MAXPRICE"];
		OCICommit($db_conn);
	}
	elseif(array_key_exists('get_count_of_all_albums', $_POST)){
		$album = executePlainSQL("select album_id from album");
		$count = OCI_Fetch_Array(executePlainSQL("select count(album_id) as count from album"), OCI_BOTH);
		echo "<table><tr><th>Album ID</th></tr>";
		while($row = OCI_Fetch_Array($album, OCI_BOTH)){
			echo "<tr><td>" . $row['ALBUM_ID'] . "</td><td>" . $row['COUNT'] . "</td></tr>";
		}
		echo "</table><br/>Count of Albums: " . $count["COUNT"];
		OCICommit($db_conn);
	}
	elseif(array_key_exists('get_avg_price_all_albums', $_POST)){
		$avgprice = OCI_Fetch_Array(executePlainSQL("select avg(price) as avgprice from album"), OCI_BOTH);
		echo "Average Price of Albums: " . $avgprice["AVGPRICE"];
		OCICommit($db_conn);
		
	}
	elseif(array_key_exists('get_total_price_all_albums', $_POST)){
		$totalprice = OCI_Fetch_Array(executePlainSQL("select sum(price) as totalprice from album"), OCI_BOTH);
		echo "Total Price of Albums: " . $totalprice["TOTALPRICE"];
		OCICommit($db_conn);
		
	}
	elseif(array_key_exists('get_avg_price_albums_by_genre', $_POST)){
		$result = executePlainSQL("select genre, avg(price) as avgprice, sum(price) as totalprice, count(*) as numingenre from album group by genre");
		echo "<table><tr><th>Genre</th><th>Average Price of Genre</th><th>Total Price of Albums in Genre</th><th>Total Albums in Genre</th></tr>";
		while($row = OCI_Fetch_Array($result, OCI_BOTH)){
			echo "<tr><td>" . $row['GENRE'] . "</td><td>" . $row['AVGPRICE'] . "</td><td>" . $row['TOTALPRICE'] . "</td><td>" . $row['NUMINGENRE'] . "</td></tr>";
		}
		echo "</table>";
		OCICommit($db_conn);
	}
	elseif(array_key_exists('get_min_price_album_by_genre', $_POST)){
		$result1 = executePlainSQL("select genre, min(price) as minprice, sum(price) as totalprice, count(*) as numingenre from album group by genre");
		echo "<table><tr><th>Genre</th><th>Minimum Price of Genre</th><th>Total Price of Albums in Genre</th><th>Total Albums in Genre</th></tr>";
		while($row = OCI_Fetch_Array($result1, OCI_BOTH)){
			echo "<tr><td>" . $row['GENRE'] . "</td><td>" . $row['MINPRICE'] . "</td><td>" . $row['TOTALPRICE'] . "</td><td>" . $row['NUMINGENRE'] . "</td></tr>";
		}
		echo "</table>";
		OCICommit($db_conn);
	}
	elseif(array_key_exists('get_max_price_album_by_genre', $_POST)){
		$result2 = executePlainSQL("select genre, max(price) as maxprice, sum(price) as totalprice, count(*) as numingenre from album group by genre");
		echo "<table><tr><th>Genre</th><th>Maximum Price of Genre</th><th>Total Price of Albums in Genre</th><th>Total Albums in Genre</th></tr>";
		while($row = OCI_Fetch_Array($result2, OCI_BOTH)){
			echo "<tr><td>" . $row['GENRE'] . "</td><td>" . $row['MAXPRICE'] . "</td><td>" . $row['TOTALPRICE'] . "</td><td>" . $row['NUMINGENRE'] . "</td></tr>";
		}
		echo "</table>";
		OCICommit($db_conn);
	}
	elseif(array_key_exists('get_customers_bought_all', $_POST)){
		$result = executePlainSQL("SELECT c.cust_email as cust_email FROM customer c WHERE NOT EXISTS (SELECT * FROM album a WHERE NOT EXISTS (select * FROM makes_purchase m, purchase_has_album p WHERE c.cust_email=m.cust_email AND m.purchase_no=p.purchase_no AND a.album_id=p.album_id))");
		echo "<table><tr><th>Customer</th></tr>";
		while($row = OCI_Fetch_Array($result, OCI_BOTH)){
			echo "<tr><td>" . $row['CUST_EMAIL'] . "</td></tr>";
		}
		echo "</table>";
		OCICommit($db_conn);
	}
	elseif(array_key_exists('add_album', $_POST)) {
		$AlbumID 	   = $_POST['AlbumID'];
		$AlbumMinStock = $_POST['AlbumMinStock'];
		$AlbumStock    = $_POST['AlbumStock'];
		$AlbumPrice    = $_POST['AlbumPrice'];
		$AlbumYear     = $_POST['AlbumYear'];
		$AlbumName     = $_POST['AlbumName'];
		$AlbumGenre    = $_POST['AlbumGenre'];
		$AlbumArtist   = $_POST['AlbumArtist'];
		if (!(is_numeric($AlbumID) && is_numeric($AlbumMinStock) && is_numeric($AlbumStock) && is_numeric($AlbumYear) && is_numeric($AlbumPrice))) {
			printInputError();
            	$result = executePlainSQL("select * from album order by album_id");
            	printAlbum($result);

		} else if (empty($AlbumID) || empty($AlbumMinStock) || empty($AlbumStock) || empty($AlbumPrice) || empty($AlbumYear) || empty($AlbumName) || empty($AlbumGenre) || empty($AlbumArtist)) {
			printEmptyError();
            	$result = executePlainSQL("select * from album order by album_id");
            	printAlbum($result);
		} 
		else {
			$tuple = array ( 
				":bind1" => $AlbumID,
				":bind2" => $AlbumMinStock,
				":bind3" => $AlbumStock,
				":bind4" => $AlbumPrice,
				":bind5" => $AlbumYear,
				":bind6" => $AlbumName,
				":bind7" => $AlbumGenre,
				":bind8" => $AlbumArtist
			);
			$alltuples = array ($tuple);
			executeBoundSQL("insert into album values(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8)", $alltuples);
			OCICommit($db_conn);
			if ($_POST && $success) {
            	echo "<p style='font-size: x-large'>Album added.</p>";
            	$result = executePlainSQL("select * from album order by album_id");
            	printAlbum($result);

       		 } else {
				echo "<p style='font-size: x-large'>Failed to add album.</p>";
            	$result = executePlainSQL("select * from album order by album_id");
            	printAlbum($result);
        }
		}
	}
	elseif(array_key_exists('delete_album', $_POST)) {
		$tuple = array (
			":bind1" => $_POST['albumID_to_delete']
		);
		$alltuples = array ($tuple);
		executeBoundSQL("delete from album where album_id=:bind1", $alltuples);
		OCICommit($db_conn);
		// if there isn't an album with that ID give error 
		if ($_POST && $success) {
		        echo "<p style='font-size: x-large'>Album deleted.</p>";
            	$result = executePlainSQL("select * from album order by album_id");
            	printAlbum($result);

		} else { 
				echo "<p style='font-size: x-large'>Failed to delete album.</p>";
            	$result = executePlainSQL("select * from album order by album_id");
            	printAlbum($result);
		}
		
	}
		
		
	
    OCILogoff($db_conn);
}
else {
    echo "CANNOT CONNECT. CONNECTION NONEXISTENT.";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>
