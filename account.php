<!-- Account php page -->
<!-- Made by Andres Camilo Jimenez Vargas -->
<!-- ID: 217147-->

<?php
/*
 * To initialize the page it is required to connect to the data base, then check if a session is already
 * set or if a session has arrived to a timeout of 2 minutes.
 */
	require_once('connect.php');
	session_start();
	
	$sw=true;
	if(!isset($_SESSION['user'])){
		$sw=false;
	}else{
		$user=$_SESSION['user'];
		
		$timeout = 120; 
		if(isset($_SESSION['timeout'])) {
			
			$duration = time() - (int)$_SESSION['timeout'];
			if($duration > $timeout) {
				session_destroy();
				session_start();
			}
		}
		
		$_SESSION['timeout'] = time();
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>User account</title>
		
		<link rel="stylesheet" type="text/css" href="styles.css">
		
	</head>
	<body>
		<?php 
		if($sw==true){
			/*
			 * User has a session
			 */
				echo "<h1 align=center>Welcome $user</h1>";
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
				/*
				 * Generation of left navigation bar
				 */
				echo "<div id='container'>";
				echo "<div id='left'>";
				echo "<fieldset>";
				echo "<legend>Navigation Menu</legend>";
				echo "<p><a href='reserve.php'>Make a reservation</a></p>";
				echo "<p><a href='cancel.php'>Cancel a reservation</a></p>";
				echo "<p><a href='logout.php'>Log-Out</a></p>";
				
				echo "</fieldset>";
				echo "</div>";
				
				/*
				 * Generation of the table with the actual activities
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
				echo "</table></thead>";
				echo "</fieldset>";
				
				/*
				 * Generation of the table with the actual reservations of a user 
				 */
				echo "<h2>Your reservations</h2>";
				echo "<fieldset>";
				
				$query = mysql_query("SELECT * FROM reservation WHERE USER='$user'") or die(mysql_error());
				if(mysql_num_rows($query) > 0){
					
					echo "<div class='datagrid'><table><thead><tr><th>Activity</th> <th>Adults</th> <th>Children</th></tr></thead>";
					while($row = mysql_fetch_array($query)){
						$id=$row['RES_ID'];
						$ac=$row['activity'];
						$ad=$row['total_adult'];
						$ch=$row['total_children'];
						
						echo "<tr>
								<td>$ac</td> <td>$ad</td> <td>$ch</td>
							</tr>";
					}
					
					echo "</table></div>";
				}else{
					
					echo "<p>No reservations made</p>";
				}
				echo "</fieldset>";
					echo "</div>";
				
			
			}else{
				/*
				 * User has no session
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