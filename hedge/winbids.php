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
      <li><a href="#">Auction Results</a></li>
    </ul>
  </div>
</nav>
<div class="container">
	<table class="table">

    	<thead>
			<tr>
				<th>REF</th>
				<th>BANK</th>
				<th>RATE</th>
				<th>BID AMOUNT</th>
				<th>OPTION</th>
				<th>LIMIT</th>
				<th>AWARDED AMOUNT</th>
			</tr>
		</thead>
		<tbody>

<?php

//include database connection
include 'database.php';

//reset bank limits
$conn->query("UPDATE banks SET banklimit='1000000000' WHERE id>0");


//store form data into variable
$batchref = $_POST['batchref'];

//sql parameters
$sql = "select fwddate, couponamt, batchref from hedgebids where batchref=:batchref  group by fwddate, couponamt, batchref order by fwddate";
$dstmt = $conn->prepare($sql);
$dstmt->bindParam(':batchref', $batchref);
$dstmt->execute();

    
//start date looping
while ($drow = $dstmt->fetch(PDO::FETCH_OBJ)) {

    echo '<tr><td><span style="font-weight:bold">'. $drow->fwddate . '</span></td><td><span style="font-weight:bold">'.number_format($drow->couponamt,2). ' is done.</span></td></tr>';
//     echo 'Bid wins for Coupon date '. $drow->fwddate . ' and Coupon amount ' .number_format($drow->couponamt) . ' done.<br>';
    
    
    //set parameters for a particular date
    $query = "SELECT hedgebids.id, fwddate, couponamt, fwdrate, amtbid, bankname, bankid, bidoption, batchref FROM hedgebids, banks
               WHERE banks.id=hedgebids.bankid AND batchref=:batchref AND fwddate=:fwddate order by fwdrate desc, bidoption desc ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':fwddate', $drow->fwddate);
    $stmt->bindParam(':batchref', $batchref);
    $limits = $conn->prepare("SELECT * FROM banks where id=?");
    $updatelimit = $conn->prepare("UPDATE banks SET banklimit=? WHERE id=?");
    $stmt->execute();
    
    $bidwin = $conn->prepare("INSERT INTO results (bidid, bankid, rate, amtbid, bidoption, winamt, batchref, fwddate, couponamt)
                            VALUES (:bidid, :bankid, :rate, :amtbid, :bidoption, :winamt, :batchref, :fwddate, :couponamt)");
    
    $bankcheck = $conn->prepare("select bankid from results where fwddate=:fdate and batchref=:bref");
    $bankcheck->bindParam(':bref', $drow->batchref);
    $total 	= 0;
    $bank = 0;
    $winamount = 0;
    $balance = $drow->couponamt;
    while ($row = $stmt->fetch()) {
        $limits->execute([$row['bankid']]);
        $limit = $limits->fetch();
  
        
        // skip row if bank has already won
        $bankcheck->bindParam(':fdate', $row['fwddate']);
        $bankcheck->execute();
        $bcheck = $bankcheck->fetchAll();
        if (in_array($row['bankid'], $bcheck)) {
            continue;
        } else {
        // if amtbid is less than limit and amtbid is less than balance
        if ( $row['amtbid'] <= $limit['banklimit'] and $row['amtbid'] <= $balance) {
            
            $winamount = $row['amtbid'];
            $newlimit = $limit['banklimit'] - $winamount;
            $updatelimit->execute([$newlimit, $row['bankid']]);
            echo '<tr>
        			<td>'.$row['id'].'</td>
        			<td>'.$row['bankname'].'</td>
        			<td>'.$row['fwdrate'].'</td>
                    <td>'.number_format($row['amtbid'],2).'</td>
                    <td>'.$row['bidoption'].'</td>
                    <td>'.number_format($newlimit,2).'</td>
                    <td>'.number_format($winamount,2).'</td>
        		  </tr>';
//             if ($winamount > 0 ) {
//                 $bidwin->bindParam(':bidid', $row['id']);
//                 $bidwin->bindParam(':bankid', $row['bankid']);
//                 $bidwin->bindParam(':rate', $row['fwdrate']);
//                 $bidwin->bindParam(':amtbid', $row['amtbid']);
//                 $bidwin->bindParam(':bidoption', $row['bidoption']);
//                 $bidwin->bindParam(':winamt', $winamount);
//                 $bidwin->bindParam(':batchref', $row['batchref']);
//                 $bidwin->bindParam(':fwddate', $row['fwddate']);
//                 $bidwin->bindParam(':couponamt', $drow->couponamt);
//                 $bidwin->execute();
//             }
            $balance -= $winamount;
        
         // else if amtbid is greater than limit & amtbid is less than balance
        } elseif ($row['amtbid'] > $limit['banklimit']  and $row['amtbid'] <= $balance) {
            $winamount = $limit['banklimit'];
            $newlimit = $limit['banklimit'] - $winamount;
            $updatelimit->execute([$newlimit, $row['bankid']]);
            
            echo '<tr>
        			<td>'.$row['id'].'</td>
        			<td>'.$row['bankname'].'</td>
        			<td>'.$row['fwdrate'].'</td>
                    <td>'.number_format($row['amtbid'],2).'</td>
                    <td>'.$row['bidoption'].'</td>
                    <td>'.number_format($newlimit,2).'</td>
                    <td>'.number_format($winamount,2).'</td>
        		</tr>';
//             if ($winamount > 0 ) {
//                 $bidwin->bindParam(':bidid', $row['id']);
//                 $bidwin->bindParam(':bankid', $row['bankid']);
//                 $bidwin->bindParam(':rate', $row['fwdrate']);
//                 $bidwin->bindParam(':amtbid', $row['amtbid']);
//                 $bidwin->bindParam(':bidoption', $row['bidoption']);
//                 $bidwin->bindParam(':winamt', $winamount);
//                 $bidwin->bindParam(':batchref', $row['batchref']);
//                 $bidwin->bindParam(':fwddate', $row['fwddate']);
//                 $bidwin->bindParam(':couponamt', $drow->couponamt);
//                 $bidwin->execute();
//             }
            
            $balance -= $winamount;
         
        // else if amtbid is less than limit & amtbid is greater than balance    
        } elseif ($row['amtbid'] >  $balance and $balance <= $limit['banklimit']) {
            $winamount = $balance;
            $newlimit = $limit['banklimit'] - $winamount;
            $updatelimit->execute([$newlimit, $row['bankid']]);
            echo '<tr>
        			<td>'.$row['id'].'</td>
        			<td>'.$row['bankname'].'</td>
        			<td>'.$row['fwdrate'].'</td>
                    <td>'.number_format($row['amtbid'],2).'</td>
                    <td>'.$row['bidoption'].'</td>
                    <td>'.number_format($newlimit,2).'</td>
                    <td>'.number_format($winamount,2).'</td>
        		</tr>';
//             if ($winamount > 0 ) {
//                 $bidwin->bindParam(':bidid', $row['id']);
//                 $bidwin->bindParam(':bankid', $row['bankid']);
//                 $bidwin->bindParam(':rate', $row['fwdrate']);
//                 $bidwin->bindParam(':amtbid', $row['amtbid']);
//                 $bidwin->bindParam(':bidoption', $row['bidoption']);
//                 $bidwin->bindParam(':winamt', $winamount);
//                 $bidwin->bindParam(':batchref', $row['batchref']);
//                 $bidwin->bindParam(':fwddate', $row['fwddate']);
//                 $bidwin->bindParam(':couponamt', $drow->couponamt);
//                 $bidwin->execute();
//             }      
            $balance -= $winamount;
            
                break;
        } elseif ($row['amtbid'] > $balance and $balance > $limit['banklimit']) {

            $winamount = $limit['banklimit'];
            $newlimit = $limit['banklimit'] - $winamount;
            $updatelimit->execute([$newlimit, $row['bankid']]);
            echo '<tr>
        			<td>'.$row['id'].'</td>
        			<td>'.$row['bankname'].'</td>
        			<td>'.$row['fwdrate'].'</td>
                    <td>'.number_format($row['amtbid'],2).'</td>
                    <td>'.$row['bidoption'].'</td>
                    <td>'.number_format($newlimit,2).'</td>
                    <td>'.number_format($winamount,2).'</td>
        		</tr>';
            
//             if ($winamount > 0 ) {
//                 $bidwin->bindParam(':bidid', $row['id']);
//                 $bidwin->bindParam(':bankid', $row['bankid']);
//                 $bidwin->bindParam(':rate', $row['fwdrate']);
//                 $bidwin->bindParam(':amtbid', $row['amtbid']);
//                 $bidwin->bindParam(':bidoption', $row['bidoption']);
//                 $bidwin->bindParam(':winamt', $winamount);
//                 $bidwin->bindParam(':batchref', $row['batchref']);
//                 $bidwin->bindParam(':fwddate', $row['fwddate']);
//                 $bidwin->bindParam(':couponamt', $drow->couponamt);
//                 $bidwin->execute();   
//             }
            $balance -= $winamount;
            
        } else {  
            echo '<tr><td><span style="font-weight:bold">Testing</span></td><td><span style="font-weight:bold">'.number_format($drow->couponamt,2). '</span></td></tr>';           
                   
        }      
//         $total += $row['amtbid'];
        
        }
    }}

?>
</tbody>
</table>
</div>
</body>
</html>
