<!-- Log-Out php page -->
<!-- Made by Andres Camilo Jimenez Vargas -->
<!-- ID: 217147-->

<!DOCTYPE html>
<html>
	<head>
		<title>Log Out</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
	</head>
	<body>
		<?php 
		/*
		 * To initialize the page it is required to connect to check if a session has arrived to a timeout of 2 minutes.
		 * If not the current session is destroyed, and a simple version of the page show the message of successful.
		 * In case of error a simple version of the page is shown with an error.
		 */
		session_start();
		
		
		$timeout = 120; 
		if(isset($_SESSION['timeout'])) {
		
			$duration = time() - (int)$_SESSION['timeout'];
			if($duration > $timeout) {
				session_destroy();
				session_start();
			}
		}
		if (isset($_SESSION['user']))
		{
			session_destroy();
			echo "<h1 align=center>Log out</h1>";
			echo "<div id='container'>";
			echo "<div id='center'>";
			echo "<fieldset>";
			echo "<p>Logged out successfull!</p>";
			echo " <p>Please <a href='index.php'>click here</a> to return.</p>";
			echo "</fieldset>";
			echo "</div>";
			echo "</div>";
		}else{
			
			echo "<h1 align=center>Error</h1>";
			echo "<div id='container'>";
			echo "<div id='center'>";
			echo "<fieldset>";
			echo "<p>You are not logged in</p>";
			echo " <p>Please <a href='index.php'>click here</a> to return.</p>";
			echo "</fieldset>";
			echo "</div>";
			echo "</div>";
		}
		
		?>
	</body>
</html>