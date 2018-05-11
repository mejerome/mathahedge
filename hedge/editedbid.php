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
<h3>Edit Bid Details</h3> 
<form  method="post" action="editedbid.php"  id="editform">
<?php 
include 'database.php';

$id = $_POST['id'];
$fwdrate = $_POST['fwdrate'];
$amtbid = $_POST['amtbid'];

$stmt = $conn->prepare("UPDATE hedgebids SET fwdrate = :fwdrate, amtbid = :amtbid
                            WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':fwdrate', $fwdrate);
$stmt->bindParam(':amtbid', $amtbid);
$stmt->execute();
echo 'The Foward Rate and Bid Amount for Bid Reference Number '.$id.' has been set to '.$fwdrate.' and '.$amtbid.' respectively.';

?>
</form>
</div>
</body>
</html>
