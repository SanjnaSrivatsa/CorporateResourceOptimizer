<?php # 


include ('mysqli_connect.php');
include ('include.php');

//locID, locCity, locState, locPopulation 
$locID="";
$locCity="";
$locState="";
$locPopulation="";

if (isset($_GET['locID'])) {	
    $locID = $_GET['locID'];    
    $query = "select locID,locCity,locState,locPopulation from location where locID='$locID'";
    $result = @mysqli_query($dbc,$query); 
    $row = mysqli_fetch_array($result, MYSQL_ASSOC);    
    $locCity=$row["locCity"];
    $locState=$row["locState"];
    $locPopulation=$row["locPopulation"];
}
if(isset($_POST['locID'])){
    $locID = $_POST['locID'];    
    $locCity=$_POST["locCity"];
    $locState=$_POST["locState"];
    $locPopulation=$_POST["locPopulation"];
}


if ($locID!="") {

//Closing php
?>
<h2>Edit a Location</h2>
<form action="edit_location.php" method="post">
<p>Location City: <input type="text" name="locCity" size="15" maxlength="50" value="<?php echo $locCity; ?>" /></p>
<p>Location State: <input type="text" name="locState" size="30" maxlength="30" value="<?php echo $locState; ?>" /></p>
<p>Population: <input type="text" name="locPopulation" size="10" maxlength="10" value="<?php echo $locPopulation; ?>" /></p>
    <input type="hidden" name="submitted" value="TRUE" />
    <input type="hidden" name="locID" value="<?php echo $locID; ?>" />
    <p><input type="submit" name="submit" value="Submit" /></p>
</form>
<?php #  
    
} else { 
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Location Number.</p><p><br /><br /></p>';
}


// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
	$errors = array(); 
    
	if (empty($locCity)) {
		$errors[] = 'You forgot to enter the City details for this location.';
	} 
    if (empty($locState)) {
		$errors[] = 'You forgot to enter the state details for this location.';
	} 
    if (empty($locPopulation)) {
		$errors[] = 'You forgot to enter the population details for this location.';
	} 
    if (!is_numeric($locPopulation)) {
		$errors[] = 'Please enter a numeric value for population field.';
	} 
    
	if (empty($errors)) { 
        $query = " UPDATE location 
                    SET locCity='$locCity', 
                    locState='$locState', 
                    locPopulation='$locPopulation'
                    WHERE locID = '$locID'";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
				echo '<p>The Location record has been edited.</p><p><br /><br /></p>';	
			} else { 
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The Location could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				exit();
			}				
	} else { 
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';		
	} 
} 
?>