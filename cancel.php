<!-- Cancel php page -->
<!-- Made by Andres Camilo Jimenez Vargas -->
<!-- ID: 217147-->

<?php		
/*
 * To initialize the page it is required to connect to the data base, then check if a session is already
 * set or if a session has arrived to a timeout of 2 minutes.
 */
	require_once('connect.php');
	session_start();
	$timeout = 120;
	
	if(isset($_SESSION['timeout'])) {
			
		$duration = time() - (int)$_SESSION['timeout'];
		if($duration > $timeout) {
			session_destroy();
			session_start();
		}
	}
		
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Reservation</title>
		
			<link rel="stylesheet" type="text/css" href="styles.css">
		
	</head>
		<body>
			
			<?php
				
				if (isset($_SESSION['user'])){
					
					$user=$_SESSION['user'];
					
					echo "<h1 align=center>Cancellation area</h1>";
					if(isset($_POST['res'])){
						/*
						 * If a request of cancelation is made the database is updated
						 */
						$id=$_POST['res'];
						
						mysql_query("SET AUTOCOMMIT=0");
						mysql_query("START TRANSACTION");
						
						$query = mysql_query("SELECT * FROM reservation WHERE res_id='$id'") or die(mysql_error());
						$row = mysql_fetch_array($query);
						$ac=$row['activity'];
						$ad=$row['total_adult'];
						$ch=$row['total_children'];
						
						$tot=$ad+$ch;
						$query1 = mysql_query("UPDATE activities SET free_places=free_places+$tot WHERE name='$ac'");
						
						$query2 = mysql_query("DELETE FROM reservation WHERE res_id=$id") or die(mysql_error());
						
						if($query1 && $query2){
							mysql_query("COMMIT");
							echo "<div id='container'>";
							echo "<div id='center'>";
							echo "<fieldset>";
							echo "<p>Reservation canceled succfessfull!</p>";
							echo " <p>Please <a href='account.php'>click here</a> to return.</p>";
							echo "</fieldset>";
							echo "</div>";
							echo "</div>";
						}else{
							mysql_query("ROLLBACK");
							echo "<h1 align=center>Error</h1>";
							echo "<div id='container'>";
							echo "<div id='center'>";
							echo "<fieldset>";
							echo '<p>DATABASE ERROR</p> ' . mysql_error().'</p>';
							echo "<p>Please <a href='account.php'>click here</a> to return.</p>";
							echo "</fieldset>";
							echo "</div>";
							echo "</div>";
						}
						
					}else{
						/*
						 * If there is no request, the options fo cancellation are shown in a table with the actual reservations of the user
						 */
						echo "<div id='container'>";
						echo "<div id='center'>";
						echo "<h2>Your reservations</h2>";
						echo "<fieldset>";
						echo "<p>Here are your reservations, please select the reservation that you want to cancel and press Cancel</p>";
						$query = mysql_query("SELECT * FROM reservation WHERE USER='$user'") or die(mysql_error());
							if(mysql_num_rows($query) > 0){
								
								echo "<form method='POST' action='cancel.php'>";
								echo "<div class='datagrid'><table><thead><tr><th>Activity</th> <th>Adults</th> <th>Children</th><th>Check to Cancel</th></tr></thead>";
								
								
								while($row = mysql_fetch_array($query)){
									$id=$row['RES_ID'];
									$ac=$row['activity'];
									$ad=$row['total_adult'];
									$ch=$row['total_children'];
										
									echo "<tr><td>$ac</td> <td>$ad</td> <td>$ch</td><td><input type='radio' name='res' value='$id'></td></tr>";
									}
									echo "</table></div>";
									echo "<br><br><input id='button' type='submit' name='cancel' value='Cancel'> </form>";
									
						}else{
							 echo "<p>No reservations made</p>";
							 
							}
						echo "</fieldset>";
						echo "</div>";
						echo "</div>";
						echo " <p align='center'>Please <a href='account.php'>click here</a> to return.</p>";
					}
					
				}else{
					/*
					 * If the user doesnt have no session
					 */
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