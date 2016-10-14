<?php # 

include ('mysqli_connect.php');
include ('include.php');

$empName = "";
$empNo= "";
$empTitle= "";
$deptName= "";
$empDateOfBirth= "";

if (isset($_GET['empNo'])) {	
    $empNo = $_GET['empNo'];    
    $query = "select empno,empname,emptitle,empDateofbirth,deptname from employee where empno='$empNo'";
    $result = @mysqli_query($dbc,$query); 
    $row = mysqli_fetch_array($result, MYSQL_ASSOC);    
    $empName = $row["empname"];
    $empTitle= $row["emptitle"];
    $deptName= $row["deptname"];
    $empDateOfBirth= $row["empDateofbirth"];
}
if(isset($_POST['empNo'])){
    $empNo = $_POST['empNo'];    
    $query = "select empno,empname,emptitle,empDateofbirth,deptname from employee where empno='$empNo'";
    $result = @mysqli_query($dbc,$query); 
    $row = mysqli_fetch_array($result, MYSQL_ASSOC);    
    $empName = $row["empname"];
    $empTitle= $row["emptitle"];
    $deptName= $row["deptname"];
    $empDateOfBirth= $row["empDateofbirth"];
}


if ($empTitle!="") {

//Closing php
?>
<h2>Delete Employee</h2>
<form action="delete_employee.php" method="post">
<p>Employee Name: <?php echo $empName; ?></p>
<p>Employee Title: <?php echo $empTitle; ?></p>
<p>Employee Date of Birth: <?php echo $empDateOfBirth; ?></p>
<p>Department: <?php echo $deptName; ?> </p>
<input type="hidden" name="empNo" value="<?php echo $empNo; ?>" />
<?php # 
    if (!isset($_POST['submitted'])) 
{
?>
<p>Are you sure you want to delete this Employee?<br />
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
	<p class="error">This page has been accessed in error. Not a valid Employee Number.</p><p><br /><br /></p>';
}


// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
    
    echo "================================================================";
    
	if ($_POST['sure'] == 'Yes') { 
        $query = "DELETE FROM employee WHERE empNo='$empNo'";		
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