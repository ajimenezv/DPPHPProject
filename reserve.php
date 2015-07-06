<!-- Reserve php page -->
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
				echo "<h1 align=center>Reservation area</h1>";
				
				if(isset($_POST['act_opt'])){
					/*
					 * If a user has a session and are request to a reservation,
					 * check if the input values are correct.
					 * All inputs via POST are sanitized.
					 */
					$ac=sanitizeString($_POST['act_opt']);
					$ad=sanitizeString($_POST['adult']);
					$ch=sanitizeString($_POST['children']);
					
					if ($_POST['act_opt'] == "" || $_POST['adult'] == "" || $_POST['children'] == "") {
						/*
						 * In case of missing information
						 */
						echo "<h2 align=center>Error</h2>";
						echo "<div id='container'>";
						echo "<div id='center'>";
						echo "<fieldset>";
						echo "<p>Wrong input information</p>";
						echo " <p>Please <a href='index.php'>click here</a> to return.</p>";
						echo "</fieldset>";
						echo "</div>";
						echo "</div>";
				      
					}else{
					
						if($ad>0 && $ch<0){
							/*
							 * If an adult want to do a single reservation with no children
							 */
							echo "<h2 align=center>Error</h2>";
							echo "<div id='container'>";
							echo "<div id='center'>";
							echo "<fieldset>";
							echo "<p>Sorry, must reserve at least one child</p>";
							echo " <p>Please <a href='index.php'>click here</a> to return.</p>";
							echo "</fieldset>";
							echo "</div>";
							echo "</div>";
							
							
						}elseif($ad<=0 && $ch>0){
							/*
							 * If there is no adults in a resrvation with children
							 */
							echo "<h2 align=center>Error</h2>";
							echo "<div id='container'>";
							echo "<div id='center'>";
							echo "<fieldset>";
							echo "<p>Sorry, every child must have at least one adult companion</p>";
							echo "<p>Please <a href='index.php'>click here</a> to return.</p>";
							echo "</fieldset>";
							echo "</div>";
							echo "</div>";
							
						}elseif($ch>3){
							/*
							 * If a request is made for more than 3 children
							 */
							echo "<h2 align=center>Error</h2>";
							echo "<div id='container'>";
							echo "<div id='center'>";
							echo "<fieldset>";
							echo "<p>Sorry, maximum quantity of childen exceded</p>";
							echo "<p>Please <a href='index.php'>click here</a> to return.</p>";
							echo "</fieldset>";
							echo "</div>";
							echo "</div>";
							
						}else{
							/*
							 * In all input information is correct, search the total free places
							 * on an activity and substract to it the quantity of the reservation.
							 */

							$tot=$ad+$ch;

							
							mysql_query("SET AUTOCOMMIT=0");
							mysql_query("START TRANSACTION");
							$query = mysql_query("SELECT free_places FROM activities WHERE name='$ac'");
							$row = mysql_fetch_array($query);
							$free=$row['free_places'];
							
							/*
							 * Update the database
							 */
							if($free-$tot>=0){
								
								
								$query1 = mysql_query("INSERT INTO reservation (user,activity,total_adult,total_children) VALUES ('$user','$ac',$ad,$ch) ") or die(mysql_error());
								$query2 = mysql_query("UPDATE activities SET free_places=free_places-$tot WHERE name='$ac'");
								
								if($query1 && $query2){
									mysql_query("COMMIT");
									echo "<h2 align=center>Reservation succesfull</h2>";
									echo "<div id='container'>";
									echo "<div id='center'>";
									echo "<fieldset>";
									echo "<p>Reservation successfull!</p>";
									echo " <p>Please <a href='account.php'>click here</a> to return.</p>";
									echo "</fieldset>";
									echo "</div>";
									echo "</div>";
								}else{
									mysql_query("ROLLBACK");
									echo "<h2 align=center>Error</h2>";
									echo "<div id='container'>";
									echo "<div id='center'>";
									echo "<fieldset>";
									echo '<p>DATABASE ERROR</p> ' . mysql_error().'</p>';
									echo " <p>Please <a href='account.php'>click here</a> to return.</p>";
									echo "</fieldset>";
									echo "</div>";
									echo "</div>";
								}
								
							}else{
								/*
								 * If there are no free places on the activity for the request
								 */
								echo "<h2 align=center>Error</h2>";
								echo "<div id='container'>";
								echo "<div id='center'>";
								echo "<fieldset>";
								echo "<p>Sorry, no free slots aviable for your reservation.</p>";
								echo " <p>Please <a href='account.php'>click here</a> to return.</p>";
								echo "</fieldset>";
								echo "</div>";
								echo "</div>";
								
							}
							
						}
					}
				
				}else{
					/*
					 * If a user has a session and there is no request.
					 * All inputs via POST are sanitized.
					 */
					$arrN=array();
					$query = mysql_query("SELECT * FROM activities") or die(mysql_error());
					while($row = mysql_fetch_array($query)){
						$n=$row['NAME'];
						$t=$row['FREE_PLACES'];
						$m=$row['MAX_PLACES'];
							
						$arrN[$n]=$t;
						$arrT[$n]=$m;
							
					}
					/*
					 * Generation of the form to make a reservation with the input spaces.
					 */
					echo "<div id='container'>";
					echo "<div id='left'>";
					echo "<fieldset><legend>Make a reservation</legend>";
					echo "<form method='POST' action='reserve.php'>";
					echo "Enter the information necessary to make a reservation
							<br>
							Please remember all children must have at least one adult companion
							<br><br>
							Select the Activity <select name='act_opt'>";
					foreach ($arrN as $key => $val) {
						if($val>0){
							echo "<option value='$key'>$key</option>";
						}
						}
					echo "</select><br><br>
							";
					echo 'Quantity of Adults <input type="text" name="adult"><br><br>';
					echo 'Quantity of Children <input type="text" name="children"><br><br>';
					echo '<input id="button" type="submit" name="Reserve" value="Reserve">';
					echo "</form>";
					echo "</fieldset>";
					echo "</div>";
					
					/*
					 * Generation of the table with actual activities 
					 */
					echo "<div id='center'>";
					echo "<h2>Actual Activities</h2>";
					echo "<fieldset>";
					echo "<p>Here are the actual activities that the center provide to you</p>";
					echo "<div class='datagrid'><table><thead><tr><th>Activity</th><th>Free places</th><th>Max. places</th></tr></thead>";
					arsort($arrN);
					foreach ($arrN as $key => $val) {
						echo "<tr><td>$key</td><td>$val</td><td>$arrT[$key]</td></tr>";
					}
					echo "</table></div>";
					echo "</fieldset>";
					echo "</div>";
					echo "</div>";
					echo " <p align='center'>Please <a href='account.php'>click here</a> to return.</p>";
				}
				
			}else{
				/*
				 * If a user does not have a session.
				 * 
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
