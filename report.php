<?php # 

include ('mysqli_connect.php');
include ('include.php');

echo '<br><h2>Total Number of Employees at Each Location:</h2>';
echo '<table align="" cellspacing="0" cellpadding="5">
        <tr>
        <td>Location</td>
        <td>Population</td>
        <td>Total Number of Employee</td>
        </tr>';
		
		$Detailquery = "select count(e.empno) No_of_employee, sum(l.locPopulation) population,locstate state 
        from Project p, employee e, location l, assignedto a
        where p.projno=a.projno and e.empno=a.empno and l.locid=a.locid  group by locstate";
		$detailresult = @mysqli_query($dbc, $Detailquery); 
        $bg = '#eeeeee'; // Set the background color.
        while ($row = mysqli_fetch_array($detailresult, MYSQL_ASSOC)){
        $bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
				echo '<tr bgcolor="' . $bg . '">
				<td><b>'.$row['state'].'</b></td>
				<td><b>'.$row['population'].'</b></td>
				<td><b>'.$row['No_of_employee'].'</b></td>
				</tr>';
        }
echo '</table>';

echo "<form action='report.php' method='post'>";
echo '<br>';
echo '<br>';
echo '<br>';
echo 'Please select a state';
echo '<br>';
echo '<br>';
if (isset($_POST['state']))
{
   $selected_state = $_POST['state'];
}
echo 'State: &nbsp;&nbsp;<select name="state">';
$query = "select distinct(locstate) state from location";
$result = @mysqli_query($dbc, $query);  
if($result){
    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
        if(isset($selected_state)&& $selected_state==$row['state']){
          echo '<option selected=selected value="'.$row['state'].'">'.$row['state'].'</option>';
        }else{
          echo '<option value="'.$row['state'].'">'.$row['state'].'</option>';
        }
    }
}
echo '</select>';
echo '<br>';
echo '<br>';
echo "<input type='submit' value='View detailed Report for the Selected State'>";
echo '</form>';

if (isset($_POST['state']))
{
        $selected_state = $_POST['state'];
        $query = "select count(e.empno) No_of_employee, sum(p.projestcost) Total_Project_cost, avg(p.projestcost) avg_Project_cost,
                    count(p.projno) totalProjects,locstate state from Project p, employee e, location l, assignedto a
                    where p.projno=a.projno and e.empno=a.empno and l.locid=a.locid and locstate = '$selected_state'";
		$result = @mysqli_query($dbc, $query);  
    
        echo '<br><h2>Detail Report at Location :'.$selected_state.'</h2>';
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
            echo 'Location:<b>&nbsp;'.$row['state'].'</b><br>';
            echo 'Total Number of Project:<b>&nbsp;'.$row['totalProjects'].'</b><br>';
            echo 'Total Number of Employee:<b>&nbsp;'.$row['No_of_employee'].'</b><br>';
            echo 'Total Investment in Project:<b>&nbsp;$'.$row['Total_Project_cost'].'</b><br>';
            echo 'Average Cost per project:<b>&nbsp;$'.$row['avg_Project_cost'].'</b><br>';
        }
}

?>