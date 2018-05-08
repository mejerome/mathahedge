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
      <li class="active"><a href="runauction.php">Run Auction</a></li>
      <li><a href="winresults.php">Auction Results</a></li>
    </ul>
  </div>
</nav>
<div class="container">
	<form action="storewins.php">
		<input type="submit" value="Store Auction">
	</form>
</div>

<div class="container">

<?php

//include database connection
include 'database.php';

//store form data into variable
$batchref = $_POST['batchref'];

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
</body>
</html>
