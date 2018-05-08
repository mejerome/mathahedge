<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hedging Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">MathaRX</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="index.php">Home</a></li>
      <li class="active"><a href="banks.php">Banks</a></li>
      <li><a href="runauction.php">Run Auction</a></li>
      <li><a href="winresults.php">Auction Results</a></li>
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
