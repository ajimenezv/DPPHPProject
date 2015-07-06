<!-- Index php page -->
<!-- Made by Andres Camilo Jimenez Vargas -->
<!-- ID: 217147-->

<!DOCTYPE HTML> 
	<html> 
		<head> 
			<title>Sign-In</title> 
			<link rel="stylesheet" type="text/css" href="styles.css">
		 </head>
			<?php 
			/*
			 * 
			 * 
			 * At the begining it is necessary to check two things:
			 * 
			 * 1.if the page is accessed via HTTPS, if not it is redirected to
			 * the url requested.
			 * 
			 * 2.If the cookies are enabled 
			 * 
			 * To initialize the page it is required to connect to the data base, then check if a session is already
			 * set to redirect the user to his account page.
			 */
			if($_SERVER["HTTPS"]!="on"){
				header("Location:https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
				exit();
			}
			
	
			if (!isset ( $_COOKIE ['check'] )) {
				
			if (isset ( $_GET ['login'] )) {
				echo "<h1 align=center>Error</h1>";
				echo "<div id='container'>";
				echo "<div id='center'>";
				echo "<fieldset>";
				echo "<p>Cookies are not enabled!</p>";
				echo " <p>Please <a href='index.php'>click here</a> to return.</p>";
				echo "</fieldset>";
				echo "</div>";
				echo "</div>";
				exit();
					
				} else {
					setcookie ( 'check', '1', time () + 60 );
					header ( 'Location: index.php?login' );
					exit ();
				}
				
			}
			
			require_once('connect.php');
			session_start();
			if (isset($_SESSION['user'])) {
				header("Location:account.php");
			}
			?>


		  
		 <body>
		 
		 <!-- Basic structure of the principle page -->
		 	<h1 align=center >Welcome to the Activity Center</h1>
		 	<div id="container"> 
		 		<div id="left">
			 	<fieldset>
			 		<legend>Log-in here</legend>
			 	 	<form method="POST" action="login.php"> 
				 		User 
				 		<br>
				 		<input type="text" name="user">
				 		<br>
				 		Password 
				 		<br>
				 		<input type="password" name="pass">
				 		<br> 
				 		<input id="button" type="submit" name="Login" value="Log-In"> 
				 	</form> 
			 	</fieldset>
			 	
			 	<fieldset>
			 		<legend>Register here</legend>
			 	 	<form method="POST" action="login.php"> 
				 		New user name 
				 		<br>
				 		<input type="text" name="user">
				 		<br>
				 		New Password 
				 		<br>
				 		<input type="password" name="pass">
				 		<br> 
				 		<input id="button" type="submit" name="Register" value="Register"> 
				 	</form> 
			 	</fieldset>
			 	</div>
			 	<div id="center">
			 		<h2>Actual Activities</h2>
					<?php 
					/*
					 * Generation of the table with the actual activities
					 */
						$arrN=array();
						$arrT=array();
						mysql_query("SET AUTOCOMMIT=0");
						mysql_query("START TRANSACTION");	
						
						$query = mysql_query("SELECT * FROM activities") or die(mysql_error());
						while($row = mysql_fetch_array($query)){
							$n=$row['NAME'];
							$t=$row['FREE_PLACES'];
							$m=$row['MAX_PLACES'];
							
							$arrN[$n]=$t;
							$arrT[$n]=$m;
							
						}
						
						echo "<div class='datagrid'><table><thead><tr><th>Activity</th><th>Free places</th><th>Max. places</th></tr></thead>";
						arsort($arrN);
						foreach ($arrN as $key => $val) {
							echo "<tr><td>$key</td><td>$val</td><td>$arrT[$key]</td>";
						}
						echo "</table></div>";
					?>	
				</div>
			</div>
			
		</body> 
	</html> 

