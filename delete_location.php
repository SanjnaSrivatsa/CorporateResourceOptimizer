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
    $query = "select locID,locCity,locState,locPopulation from location where locID='$locID'";
    $result = @mysqli_query($dbc,$query); 
    $row = mysqli_fetch_array($result, MYSQL_ASSOC);    
    $locCity=$row["locCity"];
    $locState=$row["locState"];
    $locPopulation=$row["locPopulation"];
}


if ($locState!="") {

//Closing php
?>
<h2>Delete Location</h2>
<form action="delete_location.php" method="post">
<p>Location City: <?php echo $locCity; ?></p>
<p>Location State: <?php echo $locState; ?></p>
<p>Population: <?php echo $locPopulation; ?></p>
<input type="hidden" name="locID" value="<?php echo $locID; ?>" />

<?php # 
    if (!isset($_POST['submitted'])) 
{
?>
<p>Are you sure you want to delete this Location?<br />
<input type="radio" name="sure" value="Yes" /> Yes 
<input type="radio" name="sure" value="No" checked="checked" /> No</p>
<p><input type="submit" name="submit" value="Submit" /></p>
<input type="hidden" name="submitted" value="TRUE" />
<?php # 
    }
?>
</form>
<?php #  
    
} else { 
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Location Number.</p><p><br /><br /></p>';
}



// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
    
    echo "================================================================";
    
	if ($_POST['sure'] == 'Yes') { 
        $query = "DELETE FROM location WHERE locID='$locID'";		
		$result_del = @mysqli_query ($dbc, $query); 
		if (mysqli_affected_rows($dbc) == 1) 
        { 
		$row = mysqli_fetch_array ($result, MYSQL_NUM);
		echo '<p><b>This record has been deleted.</b></p><p><br /><br /></p>';	
	    } 
        
    }
    
    if ($_POST['sure'] == 'No') { 
		echo '<p><b>Dont worry we didnt delete this record.phew!!!</b></p><p><br /><br /></p>';	
	}
    
}
?>