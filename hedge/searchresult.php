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
    echo '<a href="editbid.php?id='.$row['id'].'">'.$row['bankname'].'    ----     '.$row['fwdrate'].'     ----     '.$row['amtbid'].'</a><br>';
}

?>
</div>
</body>
</html>
