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

<div class="container" >
<form name="runauction" action="winreportby.php" method="post" accept-charset="utf-8">
<label>Batch Reference</label>
<select name="batchref" class="form-control">
<?php 
include 'database.php';

$query = "SELECT batchref FROM results GROUP BY batchref";

$stmt = $conn->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch()) {
    echo "<option value='" . $row['batchref'] ."'>" . $row['batchref'] ."</option>";
}

?>
</select>
<label>Report Type</label>
<select name="displayhow" class="form-control">
	<option value="bydate">Display by Coupon Date</option>
	<option value="bybank">Display by Bank</option>
</select><br>
<input type="submit"  value="Show Win Report">

</form>
</div>
</body>
</html>
