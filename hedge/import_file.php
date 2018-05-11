<!DOCTYPE html>
<html>
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

<?php
include 'database.php';

$today = date('Ymd');
$rand = strtoupper(substr(uniqid(sha1(time())),0,4));
$batchref = $today.$rand;




$query = ("INSERT INTO hedgebids (fwddate, couponamt, fwdrate, amtbid, bankid, bidoption, batchref)
        VALUES (:fwddate, :couponamt, :fwdrate, :amtbid, :bankid, :option, :batchref)");

$stmt = $conn->prepare($query);

if (isset($_POST["submit_file"])) {
    $file = $_FILES["file"]["tmp_name"];
    $handle = fopen($file, "r");
    fgetcsv($handle);//Adding this line will skip the reading of th first line from the csv file and the reading process will begin from the second line onwards
    $row = 1;
    while (($csv = fgetcsv($handle, 1000, ",")) !== false) {
        $stmt->bindParam(':fwddate', $csv[0]);
        $stmt->bindParam(':couponamt', $csv[1]);
        $stmt->bindParam(':fwdrate', $csv[2]);
        $stmt->bindParam(':amtbid', $csv[3]);
        $stmt->bindParam(':bankid', $csv[4]);
        $stmt->bindParam(':option', $csv[5]);
        $stmt->bindParam(':batchref', $batchref);
        $stmt->execute();
        $row++;
        
    }
    echo $row. " total bids have been imported.";
} 
?>

</body>
</html>