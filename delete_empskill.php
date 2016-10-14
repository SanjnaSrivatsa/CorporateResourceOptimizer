<?php # 


include ('mysqli_connect.php');
include ('include.php');


$skillId="";
$empno="";
$projno="";
$skillDescription="";


if (isset($_GET['skillId'])) {	
    $skillId=$_GET['skillId'];
    $query = "select skillId,e.empno, em.empName, p.projno,s.skillno,s.skillDescription from empskill e, skill s,employee em,project p where 
        e.skillno=s.skillno and p.projno=e.projno and e.empno=em.empno and skillId='$skillId'";
    $result = @mysqli_query($dbc,$query); 
    $row = mysqli_fetch_array($result, MYSQL_ASSOC);    
    $empno = $row["empName"];
    $projno= $row["projno"];
    $skillDescription= $row["skillDescription"];
    
}
if (isset($_POST['skillId'])) {	
    $skillId=$_POST['skillId'];
    $query = "select skillId,e.empno, em.empName, p.projno,s.skillno,s.skillDescription from empskill e, skill s,employee em,project p where 
        e.skillno=s.skillno and p.projno=e.projno and e.empno=em.empno and skillId='$skillId'";
    $result = @mysqli_query($dbc,$query); 
    $row = mysqli_fetch_array($result, MYSQL_ASSOC);    
    $empno = $row["empName"];
    $projno= $row["projno"];
    $skillDescription= $row["skillDescription"];
}


$query = "select projno,skillno,empno from empskill where skillId='$skillId'";
$result = @mysqli_query ($dbc, $query); 
if (mysqli_num_rows($result) == 1) { 

//Closing php
?>
<h2>Delete a Employee Skill</h2>
<form action="delete_empskill.php" method="post">
<p>Employee: <?php echo $empno; ?></p>
<p>Project: <?php echo $projno; ?></p>
<p>Skill: <?php echo $skillDescription; ?></p>
<input type="hidden" name="skillId" value="<?php echo $skillId; ?>"/>
<?php # 
    if (!isset($_POST['submitted'])) 
{
?>
<p>Are you sure you want to delete this Skill?<br />
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
	<p class="error">This page has been accessed in error. Not a valid record.</p><p><br /><br /></p>';
}

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
    
    echo "================================================================";
    
	if ($_POST['sure'] == 'Yes') { 
        $query = "DELETE FROM empskill WHERE skillId='$skillId'";		
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