<!-- Connection php page -->
<!-- Made by Andres Camilo Jimenez Vargas -->
<!-- ID: 217147-->

<!DOCTYPE HTML> 
	<html> 
		<head> 
			<title>Connection</title> 
			<link rel="stylesheet" type="text/css" href="styles.css">
		 </head>
		  
		 <body>

			<?php 
			/*
			 * Here are defined the parameters for the connection to the database
			 * In case of errors connecting to the data base it will show an error in a single version of the page
			 */
				define('DB_HOST', 'localhost'); 
				define('DB_NAME', 'reservations');
				define('DB_USER','root'); 
				define('DB_PASSWORD',''); 
				$con=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die('
						<h1 align=center>Error</h1>
						<div id="container">
						<div id="center">
						<fieldset>
						<p>DATABASE ERROR:  '.mysql_error().'</p> 
						<p>Please <a href="index.php">click here</a> to return.</p></fieldset></div></div>' );
				 
				$db=mysql_select_db(DB_NAME,$con) or die('
						<h1 align=center>Error</h1>
						<div id="container">
						<div id="center">
						<fieldset>
						<p>DATABASE ERROR:  '.mysql_error().'</p> 
						<p>Please <a href="index.php">click here</a> to return.</p></fieldset></div></div>' ); 
			/*
			 * Function to sanitaze the input variables to the pages
			 */
				function sanitizeString($var) {
					$var = strip_tags($var);
					$var = htmlentities($var);
					$var = stripcslashes($var);
					return mysql_real_escape_string($var);
				}
			
			
			?>

	</body>
</html>