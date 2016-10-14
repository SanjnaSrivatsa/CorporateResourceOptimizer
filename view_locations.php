<?php 

include ('mysqli_connect.php');
include ('include.php');

$display = 5;
//locID, locCity, locState, locPopulation 
$locID="";
$locCity="";
$locState="";
$locPopulation="";

$param="";
if (isset($_POST['locID'])) {	
    $locID=$_POST['locID'];
    $locCity=$_POST['locCity'];
    $locPopulation=$_POST['locPopulation'];
    $locState=$_POST['locState'];
    $param = "&locID=".$locID."&locCity=".$locCity."&locState=".$locState."&locPopulation=".$locPopulation;
}
if (isset($_GET['locID'])) {	
    $locID=$_GET['locID'];
    $locCity=$_GET['locCity'];
    $locPopulation=$_GET['locPopulation'];
    $locState=$_GET['locState'];
    $param = "&locID=".$locID."&locCity=".$locCity."&locState=".$locState."&locPopulation=".$locPopulation;
}

$filterQuery = "";
if($locCity!=""){
    $filterQuery = $filterQuery . " and locCity like '%$locCity%'";
}
if($locPopulation!=""){
    $filterQuery = $filterQuery . " and locPopulation like '%$locPopulation%'";
}
if($locState!=""){
    $filterQuery = $filterQuery . " and locState like '%$locState%'";
}

if (isset($_GET['np'])) { 
	$num_pages = $_GET['np'];
} else { 
	$query = "SELECT count(1) FROM Location where 1=1 $filterQuery";
	$result = @mysqli_query ($dbc, $query);
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
	$num_records = $row[0];
	
	if ($num_records > $display) { 
		$num_pages = ceil ($num_records/$display);
	} else {
		$num_pages = 1;
	}
} 

if (isset($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

$query = "SELECT locID, locCity, locState, locPopulation FROM Location where 1=1 $filterQuery order by locCity LIMIT $start, $display";		
$result = @mysqli_query ($dbc, $query); 

echo '<table align="" cellspacing="0" cellpadding="5">
<tr>
    <form action="view_locations.php?" method="post">
    <td align="left" colspan=2> <input type="submit" name="submit" value="Filter" />  &nbsp;<input type="reset" value="reset" name="reset"></td>
	<td align="left"><input type="text" name="locID" maxlength="10" value="'.$locID.'" /></td>
	<td align="left"><input type="text" name="locCity" maxlength="10" value="'.$locCity.'" /></td>
	<td align="left"><input type="text" name="locState" maxlength="10" value="'.$locState.'" /></td>
	<td align="left"><input type="text" name="locPopulation" maxlength="10" value="'.$locPopulation.'" /></td>
    </form>
</tr>
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b>Location ID</b></td>
	<td align="left"><b>City</b></td>
	<td align="left"><b>State</b></td>
	<td align="left"><b>Population</b></td>
</tr>
';


// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_location.php?locID=' . $row['locID'] . '">Edit</a></td>
		<td align="left"><a href="delete_location.php?locID=' . $row['locID'] . '">Delete</a></td>
		<td align="left">' . $row['locID'] . '</td>
		<td align="left">' . $row['locCity'] . '</td>
		<td align="left">' . $row['locState'] . '</td>
		<td align="left">' . $row['locPopulation'] . '</td>

	</tr>
	';
}

echo '</table>';
mysqli_free_result ($result); 
mysqli_close($dbc); 
if ($num_pages > 1) {
	
	echo '<p>';
	$current_page = ($start/$display) + 1;
	if ($current_page != 1) {
		echo '<a href="view_locations.php?s=0&np=' . $num_pages . $param.'">First</a> ';
		echo '<a href="view_locations.php?s=' . ($start - $display) . '&np=' . $num_pages . $param.'">Previous</a> ';
	}
	
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_locations.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages .$param. '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	if ($current_page != $num_pages) {
		echo '<a href="view_locations.php?s=' . ($start + $display) . '&np=' . $num_pages .$param. '">Next</a> ';
		echo '<a href="view_locations.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . $param.'">Last</a>';

	}
	
	echo '</p>';
	
} 

?>


