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
      <li><a href="index.php">Home</a></li>
      <li><a href="banks.php">Banks</a></li>
      <li><a href="runauction.php">Run Auction</a></li>
      <li class="active"><a href="winresults.php">Auction Results</a></li>
    </ul>
  </div>
</nav>
<div class="container">
<?php 
include 'database.php';
$batchref = $_POST['batchref'];
$report = $_POST['displayhow'];



if ($report = 'bydate') {

    echo $report;
    die;
    
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
    
} else {
    echo $report.'It worked';
    die;
    
        
    //sql parameters
    $dstmt = $conn->prepare("select bankid, batchref from results where batchref=:batchref  group by bankid, batchref order by bankid");
    $dstmt->bindParam(':batchref', $batchref);
    $dstmt->execute();
    
    while ($drow = $dstmt->fetch(PDO::FETCH_OBJ)) {
        $stbank = $conn->prepare("select bidid, fwddate, couponamt, bankname, rate, amtbid, winamt, batchref from results, banks
                        where banks.id=results.bankid and batchref = :batchref and bankid = :bankid order by bankid");
        $stbank->bindParam(':batchref', $drow->batchref);
        $stbank->bindParam(':bankid', $drow->bankid);
        $stbank->execute();
        
        echo '<h2>'.$drow->bankid.'-----'.$drow->batchref.'</h2>';
    }
}
?>
</div>
</body>
</html>
