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
    $empName = $_POST["empname"];
    $empTitle= $_POST["emptitle"];
    $deptName= $_POST["deptname"];
    $empDateOfBirth= $_POST["empDateofbirth"];
}


if ($empNo!="") {

//Closing php
?>
<h2>Edit a Employee</h2>
<form action="edit_employee.php" method="post">
<p>Employee Name: <input type="text" name="empname" size="15" maxlength="50" value="<?php echo $empName; ?>" /></p>
<p>Employee Title: <input type="text" name="emptitle" size="30" maxlength="50" value="<?php echo $empTitle; ?>" /></p>
<p>Employee Date of Birth: <input type="text" name="empDateofbirth" size="10" maxlength="10" value="<?php echo $empDateOfBirth; ?>" /></p>
<p>Department:
    
<?php 
    $result = @mysqli_query($dbc, "select deptName, deptphone from department order by deptName"); //I'm not sure about the 'Employees'
    echo '<select name="deptname">';
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
            if($row["deptName"]==$deptName){
                echo '<option value="'.$row["deptName"].'" selected="selected">'.$row["deptName"].'-'.$row["deptphone"].'</option>';
            }else{
                 echo '<option value="'.$row["deptName"].'">'.$row["deptName"].'-'.$row["deptphone"].'</option>';
            }
        }
    }
    echo '</select>';  
?>    
    <input type="hidden" name="submitted" value="TRUE" />
    <input type="hidden" name="empNo" value="<?php echo $empNo; ?>" />
    <p><input type="submit" name="submit" value="Submit" /></p>
</form>
<?php #  
    
} else { 
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Employee Number.</p><p><br /><br /></p>';
}


// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
	$errors = array(); 
    
	if (empty($empName)) {
		$errors[] = 'You forgot to enter the Name of the Employee.';
	} 
    if (empty($empTitle)) {
		$errors[] = 'You forgot to enter the Title of the Employee.';
	} 
    if (empty($empDateOfBirth)) {
		$errors[] = 'You forgot to enter the date of birth of Employee.';
	} 
    
	if (empty($errors)) { 
        $query = " UPDATE employee 
                    SET empName='$empName', 
                    empTitle='$empTitle', 
                    empDateOfBirth='$empDateOfBirth'
                    WHERE empNo = '$empNo'";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
				echo '<p>The Employee record has been edited.</p><p><br /><br /></p>';	
			} else { 
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The Employee could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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