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
      <li><a href="index.php">Home</a></li>
      <li><a href="banks.php">Banks</a></li>
      <li class="active"><a href="runauction.php">Run Auction</a></li>
      <li><a href="winresults.php">Auction Results</a></li>
    </ul>
  </div>
</nav>

<div class="container">

<?php
//include database connection
include 'database.php';

//store form data into variable
$batchref = $_POST['batchref'];

//delete already stored wins for this batch reference
$delete = $conn->prepare("DELETE FROM results WHERE batchref = :batchref");
$delete->bindParam(':batchref', $batchref);
$delete->execute();

//sql parameters
$sql = "select fwddate, couponamt, batchref from hedgebids where batchref=:batchref  group by fwddate, couponamt, batchref order by fwddate";
$dstmt = $conn->prepare($sql);
$dstmt->bindParam(':batchref', $batchref);
$dstmt->execute();
$update = $conn->prepare("INSERT INTO results (bidid, bankid, rate, amtbid, bidoption, winamt, batchref, fwddate, couponamt)
                VALUES (:bidid, :bankid, :rate, :amtbid, :bankoption, :winamt, :batchref, :fwddate, :couponamt)");

//start date looping
while ($drow = $dstmt->fetch(PDO::FETCH_OBJ)) {
//     echo 'Coupon date='.$drow->fwddate.'     Coupon amount='.$drow->couponamt.'<br>';
    
    //query search for coupon date
    $query = "select hedgebids.id, fwddate, couponamt, bankname, bankid, fwdrate, amtbid, bidoption, batchref from hedgebids, banks where banks.id=hedgebids.bankid and batchref=:batchref and fwddate=:fwddate order by fwdrate desc";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':batchref', $drow->batchref);
    $stmt->bindParam(':fwddate', $drow->fwddate);
    $stmt->execute();
    $balance = $drow->couponamt;
    $winamount = 0;
     
    //start a loop to get winners for each fwddate
    while ($row = $stmt->fetch()) {
        if ($row['amtbid'] <= $balance) {
            $winamount = $row['amtbid'];
            
            if ($winamount > 0) {
            //write to table
            $update->bindParam(':bidid', $row['id']);
            $update->bindParam(':bankid', $row['bankid']);
            $update->bindParam(':rate', $row['fwdrate']);
            $update->bindParam(':amtbid', $row['amtbid']);
            $update->bindParam(':bankoption', $row['bidoption']);
            $update->bindParam(':winamt', $winamount);
            $update->bindParam(':batchref', $row['batchref']);
            $update->bindParam(':fwddate', $row['fwddate']);
            $update->bindParam(':couponamt', $row['couponamt']);
            $update->execute();
            $balance -= $winamount;
            }
        } else {
            $winamount = $balance;
            
            if ($winamount > 0) {
            //write to result table
            $update->bindParam(':bidid', $row['id']);
            $update->bindParam(':bankid', $row['bankid']);
            $update->bindParam(':rate', $row['fwdrate']);
            $update->bindParam(':amtbid', $row['amtbid']);
            $update->bindParam(':bankoption', $row['bidoption']);
            $update->bindParam(':winamt', $winamount);
            $update->bindParam(':batchref', $row['batchref']);
            $update->bindParam(':fwddate', $row['fwddate']);
            $update->bindParam(':couponamt', $row['couponamt']);
            $update->execute();
            $balance -= $winamount;
            break;          
            }}
       }
    }
echo 'Win bids for Batch Reference '.$batchref.' has been successfully stored.';   
?>

</div>
</body>
</html>
