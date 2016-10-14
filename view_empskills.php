<?php 

include ('mysqli_connect.php');
include ('include.php');

$display = 5;
//locID, locCity, locState, locPopulation 
$empno="";
$projno="";
$skillDescription="";


$param="";
if (isset($_POST['empno'])) {	
    $empno=$_POST['empno'];
    $projno=$_POST['projno'];
    $skillDescription=$_POST['skillDescription'];
    $param = "&empno=".$empno."&projno=".$projno."&skillDescription=".$skillDescription;
}
if (isset($_GET['empno'])) {	
    $empno=$_GET['empno'];
    $projno=$_GET['projno'];
    $skillDescription=$_GET['skillDescription'];
    $param = "&empno=".$empno."&projno=".$projno."&skillDescription=".$skillDescription;
}

$filterQuery = "";
if($empno!=""){
    $filterQuery = $filterQuery . " and em.empName like '%$empno%'";
}
if($projno!=""){
    $filterQuery = $filterQuery . " and p.projno like '%$projno%'";
}
if($skillDescription!=""){
    $filterQuery = $filterQuery . " and skillDescription like '%$skillDescription%'";
}

if (isset($_GET['np'])) { 
	$num_pages = $_GET['np'];
} else { 
	$query = "select count(1) from empskill e, skill s,employee em,project p where 
        e.skillno=s.skillno and p.projno=e.projno and e.empno=em.empno $filterQuery";
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




$query = "select skillId,e.empno, em.empName, p.projno,s.skillno,s.skillDescription from empskill e, skill s,employee em,project p where 
        e.skillno=s.skillno and p.projno=e.projno and e.empno=em.empno
          $filterQuery order by empname LIMIT $start, $display";		
$result = @mysqli_query ($dbc, $query); 

echo '<table align="" cellspacing="0" cellpadding="5">
<tr>
    <form action="view_empskills.php?" method="post">
    <td align="left" colspan=2> <input type="submit" name="submit" value="Filter" />  &nbsp;<input type="reset" value="reset" name="reset"></td>
	<td align="left"><input type="text" name="empno" maxlength="10" value="'.$empno.'" /></td>
	<td align="left"><input type="text" name="projno" maxlength="10" value="'.$projno.'" /></td>
	<td align="left"><input type="text" name="skillDescription" maxlength="10" value="'.$skillDescription.'" /></td>
    </form>
</tr>
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b>Employee Name</b></td>
	<td align="left"><b>Project No</b></td>
	<td align="left"><b>Employee Skill</b></td>
</tr>
';


// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_empskill.php?skillId=' . $row['skillId'] . '">Edit</a></td>
		<td align="left"><a href="delete_empskill.php?skillId=' . $row['skillId'] .  '">Delete</a></td>
		<td align="left">' . $row['empName'] . '</td>
		<td align="left">' . $row['projno'] . '</td>
		<td align="left">' . $row['skillDescription'] . '</td>

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
		echo '<a href="view_empskills.php?s=0&np=' . $num_pages . $param.'">First</a> ';
		echo '<a href="view_empskills.php?s=' . ($start - $display) . '&np=' . $num_pages . $param.'">Previous</a> ';
	}
	
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_empskills.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages .$param. '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	if ($current_page != $num_pages) {
		echo '<a href="view_empskills.php?s=' . ($start + $display) . '&np=' . $num_pages .$param. '">Next</a> ';
		echo '<a href="view_empskills.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . $param.'">Last</a>';

	}
	
	echo '</p>';
	
} 

?>


