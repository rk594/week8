re_once __DIR__.'/sys/Database.php';
require_once __DIR__ . '/managers/UserManager.php';
?>
<html>
<head>
<title>PDO</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>
thead > tr > td {
font-weight: bold;
}
</style>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-lg-12">
<?php
$conn = null;
$users = null;
$database = new Database();
try {
$conn = $database->getConnection();
echo "Connected successfully!<br>";
} catch (\Exception $e) {
echo $e->getMessage()."<br>";
}
?>
</div>
</div>
<div class="row">
<div class="col-lg-12">
<?php
if($conn !== null) {
$userManager = new UserManager($conn);
$users = $userManager->getByIDLessThan(6);
echo "Number of records: <strong>" . count($users) . "</strong><br>";
}
?>
</div>
</div>
<div class="row">
<div class="col-lg-12" style="padding-top: 20px">
<?php
if(!empty($users)) {
?>
<table class="table table-bordered">
<thead>
<tr>
<td>ID</td>
<td>Email</td>
<td>First Name</td>
<td>Last Name</td>
<td>Phone Number</td>
<td>Birthday</td>
<td>Gender</td>
<td>Password</td>
</tr>
</thead>
<tbody>
<?php
foreach($users as $user) {
echo "<tr>";
echo "<td>".$user['id']."</td>";
echo "<td>".$user['email']."</td>";
echo "<td>".$user['fname']."</td>";
echo "<td>".$user['lname']."</td>";
echo "<td>".$user['phone']."</td>";
echo "<td>".$user['birthday']."</td>";
echo "<td>".$user['gender']."</td>";
echo "<td>".$user['password']."</td>";
echo "</tr>";
}
?>
</tbody>
</table>
<?php
}
?>
</div>
</div>
</div>
<script src="js/jquery-3.2.1.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
