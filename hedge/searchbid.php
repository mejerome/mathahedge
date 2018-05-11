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
<h3>Search  Bid Details</h3> 
<p>You  may search either by Bank Name and Coupon Date</p> 
<form  method="post" action="searchresult.php?go"  id="searchform">
<strong>Batch Reference: </strong><select name="batchref">
<?php 
include 'database.php';
$query = "SELECT batchref FROM hedgebids GROUP BY batchref";
$stmt = $conn->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch()) {
    echo "<option value='" . $row['batchref'] ."'>" . $row['batchref'] ."</option>";
}
?>
</select><br>
    <strong>Bank Name: </strong><input  type="text" name="bankname"><br>
   <strong>Coupon Date: </strong><input type="date" name="date" ><br>
    <input  type="submit" name="submit" value="Search"> 
</form>
</div>
</body>
</html>
