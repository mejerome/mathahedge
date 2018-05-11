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


<div>  

<?php 
include 'database.php';
$bankname = $_POST['bankname'];
$banklimit = $_POST['banklimit'];

$query = "insert into banks (bankname, banklimit) values (:bankname, :banklimit)";

$stmt = $conn->prepare($query);
$stmt->bindParam(':bankname', $bankname);
$stmt->bindParam(':banklimit', $banklimit);

$stmt->execute();

echo $bankname . "has been successfuly added to the auction list."
?>

</div>

</body>
</html>
