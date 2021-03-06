<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hedging Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">MathaRX</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="index.php">Home</a></li>
      <li><a href="banks.php">Banks</a></li>
      <li class="active"><a href="runauction.php">Run Auction</a></li>
      <li><a href="#">Auction Results</a></li>
    </ul>
  </div>
</nav>
<div class="container">

<form name="runauction" action="winbids.php" method="post" accept-charset="utf-8">
<select name="batchref" class="form-control">
<?php 
include 'database.php';

$query = "SELECT batchref FROM hedgebids GROUP BY batchref";

$stmt = $conn->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch()) {
    echo "<option value='" . $row['batchref'] ."'>" . $row['batchref'] ."</option>";
}

?>
</select>
<input type="submit" value="Run Auction">

</form>
</div>
</body>
</html>
