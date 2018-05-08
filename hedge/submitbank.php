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
      <li class="active"><a href="index.php">Home</a></li>
      <li><a href="#">Banks</a></li>
      <li><a href="runauction.php">Run Auction</a></li>
      <li><a href="#">Auction Results</a></li>
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
