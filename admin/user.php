<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-6 ">

    
<div>
  <table class="table table-striped border my-5 w-900" >
  <thead>
    <th>id</th>
    <th>Name</th>
    <th>Email</th>
    <th>Password</th>
    <th>update/delete</th>
  </thead>
 
  <tbody>
<?php
include'../login/config.php';
$record= mysqli_query($con,"SELECT * FROM users ");
  while($row= mysqli_fetch_array($record))
  echo"
  <tr>
  <td>$row[id]</td>
  <td>$row[username]</td>
  <td>$row[email]</td>
  <td>$row[password]</td>
  <td> <a href='update.php?ID=$row[id]' ><button class='operation'>Update</button></a></td>
  <td> <a href='delete.php?ID=$row[id]'><button  id='deleteButton' class ='operation'>Delete</button></a></td>
</tr>
";
?>

  </tbody>
</table>
</div>
</div>
  </div>
</div>
</body>
</html>