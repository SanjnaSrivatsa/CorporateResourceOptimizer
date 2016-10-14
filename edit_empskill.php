<?php # 

include ('mysqli_connect.php');
include ('include.php');

$empno="";
$projno="";
$skillno="";
$skillId="";

if (isset($_GET['skillId'])) {	
    $skillId    =$_GET['skillId'];
    $query      = "select projno,skillno,empno from empskill where skillId='$skillId'";
    $result     = @mysqli_query($dbc,$query); 
    $row        = mysqli_fetch_array($result, MYSQL_ASSOC);
    $projno     =$row['projno'];
    $skillno    =$row['skillno'];
    $empno      =$row['empno'];

}
if(isset($_POST['skillId'])){
    $skillId    = $_POST['skillId'];
    $empno      = $_POST['empno'];    
    $projno     = $_POST['projno'];
    $skillno    = $_POST['skillno'];
}


$query = "select projno,skillno,empno from empskill where skillId='$skillId'";
$result = @mysqli_query ($dbc, $query); 
if (mysqli_num_rows($result) == 1) { 

//Closing php
?>
<h2>Edit a Employee Skill</h2>
<form action="edit_empskill.php" method="post">
<p>Employee: 
<?php 
    $result = @mysqli_query($dbc, "select empno,empname from employee where empno='$empno'");
    if($result){
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
            echo $row['empname'];  
        }
    }else{
         echo $empno; 
    }
    
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
    <input type="hidden" name="empno" value="<?php echo $empno; ?>"/>
    <input type="hidden" name="skillId" value="<?php echo $skillId; ?>"/>
    <p><input type="submit" name="submit" value="Submit" /></p>
</form>
<?php #  
    
} else { 
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid record.</p><p><br /><br /></p>';
}


// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
	$errors = array(); 
    
	$query = "select count(1) from empskill where projno='$projno' and skillno='$skillno' and empno='$empno'";
    $result = @mysqli_query ($dbc, $query); // Run the query.
    $row = mysqli_fetch_array($result, MYSQL_NUM);
    $noOfrecords=$row[0];
    if($noOfrecords>0){
        $errors[] = 'Duplicate Entry. Employee already has the mentioned skill for this project in database.';
    }
    
	if (empty($errors)) { 
        $query = "update empskill set projno='$projno', skillno='$skillno' where skillId='$skillId'";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { 
				echo '<p>The Skill has been edited.</p><p><br /><br /></p>';	
			} else { 
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The Skill could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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