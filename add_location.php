<?php # 

include ('mysqli_connect.php');
include ('include.php');

//locID, locCity, locState, locPopulation 
$locCity="";
$locState="";
$locPopulation="";


if(isset($_POST['locCity'])){
    $locCity=$_POST["locCity"];
    $locState=$_POST["locState"];
    $locPopulation=$_POST["locPopulation"];
}

$errors = array(); 
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
    
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
            $query = "select max(REPLACE (locId, 'L', '')) from location";
            $result = @mysqli_query ($dbc, $query); // Run the query.
            $row = mysqli_fetch_array($result, MYSQL_NUM);
            $maxvalue= $row[0];
            $locId = $maxvalue+1;
            $locId = 'L000000'.$locId;
            $query = "insert into location values ('$locId','$locCity','$locState','$locPopulation')";
            $result = @mysqli_query ($dbc, $query); // Run the query.			
        if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
				echo '<p>The Location record has been added.</p>';	
			} else { 
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The Location could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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

if (isset($_POST['submitted'])&& empty($errors)) {	
?>
    <h2>Location added with following details</h2>
    <p>Location City: <?php echo $locCity; ?></p>
    <p>Location State: <?php echo $locState; ?></p>
    <p>Population: <?php echo $locPopulation; ?></p>
<?php
}else{

//Closing php
?>
<h2>Add a Location</h2>
<form action="add_location.php" method="post">
<p>Location City: <input type="text" name="locCity" size="15" maxlength="50" value="<?php echo $locCity; ?>" /></p>
<p>Location State: <input type="text" name="locState" size="30" maxlength="30" value="<?php echo $locState; ?>" /></p>
<p>Population: <input type="text" name="locPopulation" size="10" maxlength="10" value="<?php echo $locPopulation; ?>" /></p>
    <input type="hidden" name="submitted" value="TRUE" />
    <p><input type="submit" name="submit" value="Submit" /></p>
</form>
<?php #
}
?>