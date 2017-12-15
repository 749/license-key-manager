<?php

//Checks to ensure config.php hasn't been removed or renamed. 
$filename = 'config.php'; 
if (file_exists($filename)) { }  
else { 
    exit("<h4>ERROR: $filename</b> file does not exist.</h4>
<ul> 
    <li>You must install the script via <font color=red>/install</font> so that the $filename file is created. Once you are done, it is highly recommended you delete the <font color=red>/install</font> subdirectory</li> 
</ul> 
</font>"); 
} 

require('config.php');

                //Set username and password for the key management
                $username=admin_username;
                $password=admin_password;

		$getuser = $_POST["username"];
		$getpass = $_POST["password"];

		$mode = $_GET["mode"];
		$deleteid = $_GET["id"];

		if(!isset($_COOKIE['keymanagementauth'])) {
		$mode = "login";
		}

		if(isset($_COOKIE['keymanagementauth']) && $mode == "") {
		$mode = "view";
		}

		if($username == $getuser && $password == $getpass && !isset($_COOKIE['keymanagementauth'])) {
		  setcookie('keymanagementauth', 'authed');
		  $mode = "view";
		}

if($mode == "logout") {
	 setcookie ("keymanagementauth", "", time()-60*60*24*100);
	 header('location: admin.php');
}

if($mode == "login") {
	echo '		<div id="loginbox"><center>
			<form action="admin.php" method="POST">
			Username <input type="text" name="username" /><br>
			Password <input type="password" name="password"><br><br>
			<input type="submit" value="Login" />
			</form></center></div>
	';
}

if ($mode == "delete") {

if(!isset($_COOKIE['keymanagementauth'])){
	header('Location: admin.php');
	exit;
}
		
		$connectdb;
		$selectdb;
		$query = 'DELETE FROM `keys` WHERE id=' . $deleteid;
		mysqli_query($connectdb, $query);

		header('Location: admin.php?mode=view');
}

if ($mode == "search") {

if(!isset($_COOKIE['keymanagementauth'])){
	header('Location: admin.php');
	exit;
}

		$s = $_GET["s"];

		$connectdb;
		$selectdb;
		$query = "SELECT * FROM `keys` WHERE `key` LIKE '%" . $s . "%'";
		$result = mysqli_query($connectdb, $query);
		$num=$result->num_rows || 0;


		echo '<div style="background:#cccccc;margin-top:-10px;margin-bottom:30px;height:30px;">
<div style="padding-left:5px;padding-top:3px;">Product Key Management Server</div>
<div style="padding-right:5px;float:right;margin-top:-22px;"><a href="admin.php?mode=logout">Logout</a></div></div>';

		echo '<div style="margin-left:45px;"><form action="add.php" method="post">
			<input type="text" name="key" />
			<input type="submit" value="Add Key" />
			</form></div>';

		echo '<div style="margin-left:45px;margin-bottom:-30px;"><form action="admin.php" method="get">
			<input type="text" name="s" />
			<input type="hidden" name="mode" value="search">
			<input type="submit" value="Find Key" />
			</form></div>';

		echo '<div id="tablewrapper">';		
		echo '<table><th>License Key</th><th></th>';
		$i = 0;
		$tablebreaker = 0;

		while ($i < $num) {
			
			if ($tablebreaker == "10") {
				echo "</table><table><th>License Key</th><th></th>";
				$tablebreaker = 0;
			}
            $result->data_seek($i);

			$row = $result->fetch_assoc();

			echo '<tr class="tablerow"><td>';
			echo $row['key'];
			echo '</td><td>';
			echo '<a href="?mode=delete&id=' . $row['id'] . '">[X]</a>';
			echo '</td></tr>';
			$i++;
			$tablebreaker++;


			}


		echo '</table></div>';

}

if ($mode == "view") {

if(!isset($_COOKIE['keymanagementauth'])){
	header('Location: admin.php');
	exit;
}
		$connectdb;
		$selectdb;
		$query = "SELECT * FROM `keys`";
		$result = mysqli_query($connectdb, $query);
		$num=$result->num_rows || 0;

		echo '<div style="background:#cccccc;margin-top:-10px;margin-bottom:30px;height:30px;">
<div style="padding-left:5px;padding-top:3px;">Product Key Management Server</div>
<div style="padding-right:5px;float:right;margin-top:-22px;"><a href="admin.php?mode=logout">Logout</a></div></div>';

		echo '<div style="margin-left:45px;"><form action="add.php" method="post">
			<input type="text" name="key" />
			<input type="submit" value="Add Key" />
			</form></div>';

		echo '<div style="margin-left:45px;margin-bottom:-30px;"><form action="admin.php" method="get">
			<input type="text" name="s" />
			<input type="hidden" name="mode" value="search">
			<input type="submit" value="Find Key" />
			</form></div>';

		echo '<div id="tablewrapper">';
		echo '<table><th>License Key</th><th></th>';
		$i = 0;
		$tablebreaker = 0;

		while ($i < $num) {

			if ($tablebreaker == 10) {
				echo "</table><table><th>License Key</th><th></th>";
				$tablebreaker = 0;
			}

            $result->data_seek($i);

            $row = $result->fetch_assoc();

            echo '<tr class="tablerow"><td>';
            echo $row['key'];
            echo '</td><td>';
            echo '<a href="?mode=delete&id=' . $row['id'] . '">[X]</a>';
            echo '</td></tr>';
			$i++;
			$tablebreaker++;
			
			
		}
		
		echo '</table></div>';		

}

?>
<link rel="stylesheet" type="text/css" href="stylesheet.css">