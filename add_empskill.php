<?php # 

include ('mysqli_connect.php');
include ('include.php');

$empno="";
$projno="";
$skillno="";

if(isset($_POST['projno'])){
    $empno      = $_POST['empno'];    
    $projno     = $_POST['projno'];
    $skillno    = $_POST['skillno'];
}


$errors = array(); 
if (isset($_POST['submitted'])) {
    
    $query = "select count(1) from empskill where empno='$empno'";
    $result = @mysqli_query ($dbc, $query); // Run the query.
    $row = mysqli_fetch_array($result, MYSQL_NUM);
    $noOfrecords=$row[0];
    if($noOfrecords>0){
        $errors[] = 'Duplicate Entry. Employee already skill added please edit the skill.';
    }
    
	if (empty($errors)) { 
        $query = "insert into empskill(projno,skillno,empno) values('$projno','$skillno','$empno')";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
				echo '<p>The Skill has been added.</p>';	
			} else { 
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The Skill could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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
    <h2>Skill has been added with following details</h2>
    <p>Employee:<?php echo $empno; ?></p>
    <p>Project:<?php echo $projno; ?></p>
    <p>Skill:<?php echo $skillDescription; ?></p>
<?php
}else{

?>
<h2>Add a Employee Skill</h2>
<form action="add_empskill.php" method="post">
<p>Employee: 
<?php 
    $result = @mysqli_query($dbc, "select empno,empname from employee");
    echo '<select name="empno">';
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
            if($row["empno"]==$empno){
                echo '<option value="'.$row["empno"].'" selected="selected">'.$row["empname"].'</option>';
            }else{
                 echo '<option value="'.$row["empno"].'">'.$row["empname"].'</option>';
            }
        }
    }
     echo '</select>';  
    
?></p></p>
<p>Project:
    
<?php 
    $result = @mysqli_query($dbc, "select projno,projEstcost from project");
    echo '<select name="projno">';
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
            if($row["projno"]==$projno){
                echo '<option value="'.$row["projno"].'" selected="selected">'.$row["projno"].': Estimate Cost -$'.$row["projEstcost"].'</option>';
            }else{
                 echo '<option value="'.$row["projno"].'">'.$row["projno"].': Estimate Cost - $'.$row["projEstcost"].'</option>';
            }
        }
    }
    echo '</select>';  
?></p>
<p>Skill:
    
<?php 
    $result = @mysqli_query($dbc, "select skillno, skilldescription from skill"); 
    echo '<select name="skillno">';
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
            if($row["skillno"]==$skillno){
                echo '<option value="'.$row["skillno"].'" selected="selected">'.$row["skilldescription"].'</option>';
            }else{
                 echo '<option value="'.$row["skillno"].'">'.$row["skilldescription"].'</option>';
            }
        }
    }
    echo '</select>';  
?></p>
    <input type="hidden" name="submitted" value="TRUE" />
    <p><input type="submit" name="submit" value="Submit" /></p>
</form>
<?php #  
    
} 


?>