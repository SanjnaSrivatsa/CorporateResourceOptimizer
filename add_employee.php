<?php # 

include ('mysqli_connect.php');
include ('include.php');

$empName = "";
$empTitle= "";
$deptName= "";
$empDateOfBirth= "";

//When Submitted
if(isset($_POST['empname'])){
    $empName = $_POST["empname"];
    $empTitle= $_POST["emptitle"];
    $deptName= $_POST["deptname"];
    $empDateOfBirth= $_POST["empDateofbirth"];
}

$errors = array(); 
if (isset($_POST['submitted'])) {
	
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
            $query = "select max(REPLACE (empNo, 'E', '')) from employee";
            $result = @mysqli_query ($dbc, $query); // Run the query.
            $row = mysqli_fetch_array($result, MYSQL_NUM);
            $maxvalue= $row[0];
            $empNo = $maxvalue+1;
            $empNo = 'E000000'.$empNo;
            $query = "insert into employee values ('$empNo','$empName','$empTitle','$empDateOfBirth','$deptName')";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
				echo '<p><b>The Employee record has been added.</b></p>';	
			} else { 
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The Employee could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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

//Insert Data
if (isset($_POST['submitted'])&& empty($errors)) {	
?>
    <h2>Employee added with following details</h2>
    <p>Employee Name: <?php echo $empName; ?></p>
    <p>Employee Title: <?php echo $empTitle; ?></p>
    <p>Employee Date of Birth: <?php echo $empDateOfBirth; ?></p>
    <p>Department: <?php echo $deptName; ?> </p>
<?php
}else{
?>
<h1 id="mainhead">Add a Employee</h1>
<form action="add_employee.php" method="post">
<p>Employee Name: <input type="text" name="empname" size="15" maxlength="50" value="<?php echo $empName; ?>" /></p>
<p>Employee Title: <input type="text" name="emptitle" size="30" maxlength="50" value="<?php echo $empTitle; ?>" /></p>
<p>Employee Date of Birth: <input type="text" name="empDateofbirth" size="10" maxlength="10" value="<?php echo $empDateOfBirth; ?>" /></p>
<p>Department:    
<?php 
    $result = @mysqli_query($dbc, "select deptName, deptphone from department order by deptName"); 
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
    <p><input type="submit" name="submit" value="Submit" /></p>
</form>
<?php #      
}

?>