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
      <a class="navbar-brand" href="index.php">MathaRX</a>
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
	<h2>Please find search results below:</h2>
<?php 
include 'database.php';
$batchref = $_POST['batchref'];
$bankname = $_POST['bankname'];
$date = $_POST['date'];

$stmt = $conn->prepare("select hedgebids.id, fwddate, fwdrate, amtbid, bankname, couponamt from hedgebids, banks
               where banks.id = hedgebids.bankid and batchref = :batchref and fwddate = :fwddate
                and bankname like :bankname");
$name = "%".$bankname."%";
$stmt->bindParam(':batchref', $batchref);
$stmt->bindParam(':fwddate', $date);
$stmt->bindParam(':bankname', $name);
$stmt->execute();

while ($row = $stmt->fetch()) {
//     echo $row->id.'#'.$row->fwddate.'#'.$row->fwdrate.'#'.$row->amtbid.'#'.$row->bankname;
    echo '<a href="editbid.php?id='.$row['id'].'">'.$row['bankname'].' ---- '.$row['fwdrate'].' ---- '.$row['amtbid'].'</a><br>';
}



?>


</div>
</body>
</html>
