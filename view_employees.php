<?php 

include ('mysqli_connect.php');
include ('include.php');

$display = 5;

$EmployeeNum="";
$Name="";
$Title="";
$Date_of_birth="";
$DeptName="";

$param="";
if (isset($_POST['EmployeeNum'])) {	
    $EmployeeNum=$_POST['EmployeeNum'];
    $Name=$_POST['Name'];
    $Title=$_POST['Title'];
    $Date_of_birth=$_POST['Date_of_birth'];
    $DeptName=$_POST['DeptName'];
    $param = "&EmployeeNum=".$EmployeeNum."&Name=".$Name."&Title=".$Title."&Date_of_birth=".$Date_of_birth."&DeptName=".$DeptName;
}
if (isset($_GET['EmployeeNum'])) {	
    $EmployeeNum=$_GET['EmployeeNum'];
    $Name=$_GET['Name'];
    $Title=$_GET['Title'];
    $Date_of_birth=$_GET['Date_of_birth'];
    $DeptName=$_GET['DeptName'];
    $param = "&EmployeeNum=".$EmployeeNum."&Name=".$Name."&Title=".$Title."&Date_of_birth=".$Date_of_birth."&DeptName=".$DeptName;
}

$filterQuery = "";
if($EmployeeNum!=""){
    $filterQuery = $filterQuery . " and empNo like '%$EmployeeNum%'";
}
if($Name!=""){
    $filterQuery = $filterQuery . " and empName like '%$Name%'";
}
if($Title!=""){
    $filterQuery = $filterQuery . " and empTitle like '%$Title%'";
}
if($Date_of_birth!=""){
    $filterQuery = $filterQuery . " and empDateofBirth like '%$Date_of_birth%'";
}
if($DeptName!=""){
    $filterQuery = $filterQuery . " and Deptname like '%$DeptName%'";
}

if (isset($_GET['np'])) { 
	$num_pages = $_GET['np'];
} else { 
	$query = "SELECT COUNT(*) FROM employee where 1=1 $filterQuery";
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




$query = "select empNo, EmpName, empTitle, empDateofBirth,Deptname from employee where 1=1 $filterQuery order by empname LIMIT $start, $display";		
$result = @mysqli_query ($dbc, $query); 

echo '<table align="" cellspacing="0" cellpadding="5">
<tr>
    <form action="view_employees.php?" method="post">
    <td align="left" colspan=2> <input type="submit" name="submit" value="Filter" />  &nbsp;<input type="reset" value="reset" name="reset"></td>
	<td align="left"><input type="text" name="EmployeeNum" maxlength="10" value="'.$EmployeeNum.'" /></td>
	<td align="left"><input type="text" name="Name" maxlength="10" value="'.$Name.'" /></td>
	<td align="left"><input type="text" name="Title" maxlength="10" value="'.$Title.'" /></td>
	<td align="left"><input type="text" name="Date_of_birth" maxlength="10" value="'.$Date_of_birth.'" /></td>
	<td align="left"><input type="text" name="DeptName" maxlength="10" value="'.$DeptName.'" /></td>
    </form>
</tr>
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b>Employee Number</b></td>
	<td align="left"><b>Name</b></td>
	<td align="left"><b>Title</b></td>
	<td align="left"><b>Date of Birth</b></td>
	<td align="left"><b>Deptname</b></td>
</tr>
';


// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
	echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_employee.php?empNo=' . $row['empNo'] . '">Edit</a></td>
		<td align="left"><a href="delete_employee.php?empNo=' . $row['empNo'] . '">Delete</a></td>
		<td align="left">' . $row['empNo'] . '</td>
		<td align="left">' . $row['EmpName'] . '</td>
		<td align="left">' . $row['empTitle'] . '</td>
		<td align="left">' . $row['empDateofBirth'] . '</td>
		<td align="left">' . $row['Deptname'] . '</td>

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
		echo '<a href="view_employees.php?s=0&np=' . $num_pages . $param.'">First</a> ';
		echo '<a href="view_employees.php?s=' . ($start - $display) . '&np=' . $num_pages . $param.'">Previous</a> ';
	}
	
	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_employees.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages .$param. '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
	
	if ($current_page != $num_pages) {
		echo '<a href="view_employees.php?s=' . ($start + $display) . '&np=' . $num_pages .$param. '">Next</a> ';
		echo '<a href="view_employees.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . $param.'">Last</a>';

	}
	
	echo '</p>';
	
} 

?>


