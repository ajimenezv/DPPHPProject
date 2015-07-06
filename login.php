<!-- Log-In php page -->
<!-- Made by Andres Camilo Jimenez Vargas -->
<!-- ID: 217147-->
<?php
/*
 * To initialize the page it is required to connect to the data base, then check if a session is already
 * set or if a session has arrived to a timeout of 2 minutes.
 */

require_once('connect.php');
session_start();

/*
 * This function do the consult to the database to check the input information is correct
 * if not a simple version of the page is shown with an error.
 * All entries by POST are sanitized.
 */
function SignIn() {

	if(!empty($_POST['user'])){ 
		
		$user=sanitizeString($_POST['user']);
		$pass=sanitizeString($_POST['pass']);


		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");

		$query = mysql_query("SELECT * FROM user where name = '$user' AND pass = '$pass'") or die(mysql_error());
		$row = mysql_fetch_array($query);


		if(!empty($row['name']) AND !empty($row['pass'])) {
			
			echo "HELLO $user!!";
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;
			$_SESSION['timeout'] = time();
			
			header("Location:account.php");
		} else {
			echo "<h1 align=center>Error</h1>";
			echo "<div id='container'>";
			echo "<div id='center'>";
			echo "<fieldset>";
			echo "<p>Sorry, wrong user name and/or password. Please retry.</p>";
			echo "<p>Please <a href='index.php'>click here</a> to return.</p>";
			echo "</fieldset>";
			echo "</div>";
			echo "</div>";
		}
	}
}
/*
 * This function do the consult to the database to check the new user does not take a user name
 * already existing.
 * 
 * If the user do the registration correctly a simple version of the page is shown and 
 * the user is asked to log in in the index page.
 * 
 * If not, a simple version of the page is shown with an error.
 * All entries by POST are sanitized.
 */
function Register() {
	if(!empty($_POST['user'])){

		$user=sanitizeString($_POST['user']);
		$pass=sanitizeString($_POST['pass']);

		$query = mysql_query("SELECT * FROM user where name = '$user'") or die(mysql_error());
		$row = mysql_fetch_array($query);

		if(empty($row['name'])) {
			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");	
			$query = mysql_query("INSERT INTO user (NAME,PASS) VALUES ('$user','$pass')");
				
			if(! $query )
			{
				mysql_query("ROLLBACK");
				echo "<h1 align=center>Error</h1>";
				echo "<div id='container'>";
				echo "<div id='center'>";
				echo "<fieldset>";
				echo '<p>DATABASE ERROR</p> ' . mysql_error().'</p>';
				echo "<p>Please <a href='index.php'>click here</a> to return.</p>";
				echo "</fieldset>";
				echo "</div>";
				echo "</div>";
				
			}else{
				mysql_query("COMMIT");
				echo "<h1 align=center>Registration Area</h1>";
				echo "<div id='container'>";
				echo "<div id='center'>";
				echo "<fieldset>";
				echo "<p>Registration successfull! Please Log-in.</p>";
				echo "<p>Please <a href='index.php'>click here</a> to return.</p>";
				echo "</fieldset>";
				echo "</div>";
				echo "</div>";
			}

				
		} else {
			echo "<h1 align=center>Error</h1>";
			echo "<div id='container'>";
			echo "<div id='center'>";
			echo "<fieldset>";
			echo "<p>Sorry, User name already in use. Please retry.</p>";
			echo "<p>Please <a href='index.php'>click here</a> to return.</p>";
			echo "</fieldset>";
			echo "</div>";
			echo "</div>";
			
		}
	}
}
?>
<!DOCTYPE HTML> 
	<html> 
		<head> 
			<title>Log-In and Registration</title> 
			<link rel="stylesheet" type="text/css" href="styles.css">
		 </head>
		  
		 <body>
				<?php 
				if(isset($_POST['Login'])) {
					SignIn();
				}
				if(isset($_POST['Register'])) {
					Register();
				}
				
				
				?>
		</body>
	</html>	