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
      <li class="active"><a href="index.php">Home</a></li>
      <li><a href="banks.php">Banks</a></li>
      <li><a href="runauction.php">Run Auction</a></li>
      <li><a href="winresults.php">Auction Results</a></li>
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
