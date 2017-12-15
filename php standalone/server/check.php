<?php

	/* Grab the URL variable */
	$key = $_GET["key"];

	/* Connect to database and grab the keys */
	require( "config.php" );
	$query = "SELECT `key` FROM `keys`";
	$result = mysqli_query($connectdb, $query);
	$num=$result->num_rows;

	/* Set up a loop to check if requested key is found in database */
	$i=0;
	while ($i < $num) {

		$validationkey = $result->fetch_assoc()["key"];

			if ($validationkey == $key) {
				$validation = "Success";
			}

		$i++;
	}

	/* If a match was found in database, echo "VALID". Else echo "INVALID" */
	if($validation != "") {
		echo 'VALID';
	} else {
		echo 'INVALID';
	}
