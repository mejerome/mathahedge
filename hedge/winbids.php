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
	<a href="searchbid.php">Search and Update Bid</a><br>
</div>
<div class="container">

<?php
//include database connection
include 'database.php';

//store form data into variable
$batchref = $_POST['batchref'];

echo '	<form action="storewins.php" method="post">
        <input type="text" name="batchref" value="'.$batchref.'" readonly>
		<input type="submit" value="Store Auction Wins">
	</form>';
//sql parameters
$sql = "select fwddate, couponamt, batchref from hedgebids where batchref=:batchref  group by fwddate, couponamt, batchref order by fwddate";
$dstmt = $conn->prepare($sql);
$dstmt->bindParam(':batchref', $batchref);
$dstmt->execute();

//start date looping
while ($drow = $dstmt->fetch(PDO::FETCH_OBJ)) {
//     echo 'Coupon date='.$drow->fwddate.'     Coupon amount='.$drow->couponamt.'<br>';
    
    //query search for coupon date
    $query = "select hedgebids.id, fwddate, couponamt, bankname, fwdrate, amtbid, batchref from hedgebids, banks where banks.id=hedgebids.bankid and batchref=:batchref and fwddate=:fwddate order by fwdrate desc";
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
            echo	'<table class="table table-hover table-bordered"><thead><tr><th>Ref</th><th>Date</th><th>Bank</th><th>Forward Rate</th><th>Amount Bid</th><th>Awarded Amount</th>
			</tr></thead><tbody>';
				    
            //display in table
            echo '<tr>
                <td>'.$row['id'].'</td>
                <td>'.$row['fwddate'].'</td>
       			<td>'.$row['bankname'].'</td>
                <td>'.$row['fwdrate'].'</td>
                <td>'.number_format($row['amtbid'],2).'</td>
                <td>'.number_format($winamount,2).'</td>
          		</tr>';
            $balance -= $winamount;
            
        } else {
            $winamount = $balance;
            
            //display in table
            echo '<tr>
                <td>'.$row['id'].'</td>
                <td>'.$row['fwddate'].'</td>
       			<td>'.$row['bankname'].'</td>
                <td>'.$row['fwdrate'].'</td>
                <td>'.number_format($row['amtbid'],2).'</td>
                <td>'.number_format($winamount,2).'</td>
          		</tr> <tfoot><tr style="font-weight: bold; font-style: italic"><td colspan="5">Coupon Amount</td><td>'.number_format($drow->couponamt,2).'</td>
                </tr></tfoot>';         
            $balance -= $winamount;
            break;          
        }
       }
    }

?>
</div>
<div>
<a id="bottom" href="#top">Go back to the top..</a>
</div>
</body>
</html>
