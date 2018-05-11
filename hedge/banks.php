<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hedging Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <script src="js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">MathaRX</a>
    </div>
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="banks.php">Banks</a></li>
      <li class="nav-item"><a class="nav-link" href="runauction.php">Run Auction</a></li>
      <li class="nav-item"><a class="nav-link" href="winresults.php">Auction Results</a></li>
    </ul>
  </div>
</nav>
  
<div class="container">
	<a href="addbank.php" >Add New Bank</a><br>
<!-- 	<a href="editbank.php" >Update Bank Limit</a><br> -->
<!-- 	<a href="deletebank.php" >Delete Bank</a> -->
	
</div>

<div class="container">

<?php 
include 'database.php';

$query = "SELECT id, bankname FROM banks";
$stmt = $conn->prepare($query);
$stmt->execute();

?>

<table class="table">
		<thead>
			<tr>
				<th>REF</th>
				<th>BANK</th>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($row = $stmt->fetch()) {
        		        echo '<tr>
        					<td>'.$row['id'].'</td>
        					<td>'.$row['bankname'].'</td>
        				</tr>';
		}?>
		</tbody>
</table>
</div>

</body>
</html>
