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
<?php 
include 'database.php';
$batchref = $_POST['batchref'];
$report = $_POST['displayhow'];

if ($report == 'bydate') {
    
    //sql parameters
    $dstmt = $conn->prepare("select fwddate, couponamt, batchref from results where batchref=:batchref  group by fwddate, couponamt, batchref order by fwddate");
    $dstmt->bindParam(':batchref', $batchref);
    $dstmt->execute();
    
    //start date looping
    while ($drow = $dstmt->fetch(PDO::FETCH_OBJ)) {
        
        //query search for coupon date
        $query = "select bidid, fwddate, couponamt, bankname, rate, amtbid, winamt, batchref from results, banks 
                where banks.id=results.bankid and batchref=:batchref and fwddate=:fwddate order by rate desc";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':batchref', $drow->batchref);
        $stmt->bindParam(':fwddate', $drow->fwddate);
        $stmt->execute();
    echo	'<table class="table table-hover table-bordered"><thead><tr><th>Ref</th><th>Date</th><th>Bank</th><th>Forward Rate</th><th>Amount Bid</th><th>Awarded Amount</th>
			</tr></thead><tbody>';
        
        //start a loop to get winners for each fwddate
        while ($row = $stmt->fetch()) {               
                //display in table
                echo '<tr>
                <td>'.$row['bidid'].'</td>
                <td>'.$row['fwddate'].'</td>
       			<td>'.$row['bankname'].'</td>
                <td>'.$row['rate'].'</td>
                <td>'.number_format($row['amtbid'],2).'</td>
                <td>'.number_format($row['winamt'],2).'</td>';
            }
            echo '</tr> <tfoot><tr style="font-weight: bold; font-style: italic"><td colspan="5">Coupon Amount</td><td>'.number_format($drow->couponamt,2).'</td>
            </tr></tfoot>';            
    }    
} elseif ($report == 'bybank') {
        
    //sql parameters
    $dstmt = $conn->prepare("select bankid, batchref from results where batchref=:batchref  group by bankid, batchref order by bankid");
    $dstmt->bindParam(':batchref', $batchref);
    $dstmt->execute();
    
    while ($drow = $dstmt->fetch(PDO::FETCH_OBJ)) {
        $bank = $conn->prepare("select id, bankname from banks where id = :id");
        $bank->bindParam(':id', $drow->bankid);
        $bank->execute();
        $bnk = $bank->fetch();
        
        $stbank = $conn->prepare("select bidid, fwddate, couponamt, bankname, rate, amtbid, winamt, batchref from results, banks
                        where banks.id=results.bankid and batchref = :batchref and bankid = :bankid order by bankid");
        $stbank->bindParam(':batchref', $drow->batchref);
        $stbank->bindParam(':bankid', $drow->bankid);
        $stbank->execute();
        
        echo	'<table class="table table-hover table-bordered"><thead><caption>'.$bnk['bankname'].'</caption>
                <tr><th>Ref</th><th>Date</th><th>Bank</th><th>Forward Rate</th><th>Amount Bid</th><th>Awarded Amount</th>
			</tr></thead><tbody>';
        while ($row = $stbank->fetch()) {
            //display in table
            echo '<tr>
                <td>'.$row['bidid'].'</td>
                <td>'.$row['fwddate'].'</td>
       			<td>'.$row['bankname'].'</td>
                <td>'.$row['rate'].'</td>
                <td>'.number_format($row['amtbid'],2).'</td>
                <td>'.number_format($row['winamt'],2).'</td>
                </tr>';
        }
   }
    }
?>
</div>
</body>
</html>