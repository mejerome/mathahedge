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

$batchref = $_POST["batchref"];

echo "<h2>The winners for the auction batch reference " . $batchref . " are shown below:";

$stmt = $conn->prepare("SELECT hedgebids.id, couponamt, fwdrate, amtbid, bankid, bankname, bidoption, batchref FROM hedgebids, banks
            WHERE hedgebids.bankid=banks.id AND batchref = :batchref ORDER BY fwdrate DESC" );

$stmt->bindParam(':batchref', $batchref);
$stmt->execute();

$limits = $conn->prepare("SELECT * FROM banks where id=?");


$updatelimit = $conn->prepare("UPDATE banks SET banklimit=? WHERE id=?");
 
?>
	<table class="data-table">
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
		$total 	= 0;
		$winamount = 0;
		$result = $stmt->fetch();
		$balance = $result['couponamt'];
		while ($row = $stmt->fetch()) {
		    $limits->execute([$row['bankid']]);
		    $limit = $limits->fetch();
		        if ($balance > $row['amtbid'] and $row['amtbid'] < $limit['banklimit']) {
		    	            		            
		            $winamount = $row['amtbid'];
        		        echo '<tr>
        					<td>'.$row['id'].'</td>
        					<td>'.$row['bankname'].'</td>
        					<td>'.$row['fwdrate'].'</td>
                            <td>'.$row['amtbid'].'</td>
                            <td>'.$row['bidoption'].'</td>
                            <td>'.$limit['banklimit'].'</td>
                            <td>'.$winamount.'</td>                
        				</tr>';
        		    $balance -= $winamount;
        		    $newlimit = $limit['banklimit'] - $winamount;
        		    $updatelimit->execute([$newlimit, $row['bankid']]);

		        } elseif ($balance > $row['amtbid'] and $row['amtbid'] > $limit['banklimit']) {
		            $winamount = $limit['banklimit'];
		            echo '<tr>
        					<td>'.$row['id'].'</td>
        					<td>'.$row['bankname'].'</td>
        					<td>'.$row['fwdrate'].'</td>
                            <td>'.$row['amtbid'].'</td>
                            <td>'.$row['bidoption'].'</td>
                            <td>'.$limit['banklimit'].'</td>
                            <td>'.$winamount.'</td>
        				</tr>';
		            $balance -= $winamount;
		            $newlimit = $limit['banklimit'] - $winamount;
		            $updatelimit->execute([$newlimit, $row['bankid']]);
		            
		            continue;
		        }	        
		        else {
		            $winamount = $balance;
		            echo '<tr>
        					<td>'.$row['id'].'</td>
        					<td>'.$row['bankname'].'</td>
        					<td>'.$row['fwdrate'].'</td>
                            <td>'.$row['amtbid'].'</td>
                            <td>'.$row['bidoption'].'</td>
                            <td>'.$limit['banklimit'].'</td>
                            <td>'.$winamount.'</td>
        				</tr>';
		            $newlimit = $limit['banklimit'] - $winamount;
		            $updatelimit->execute([$newlimit, $row['bankid']]);
		            
		            break;
		        }

		        $total += $row['amtbid'];
		}
		
		?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="4">COUPON AMOUNT</th>
				<th><?=number_format($result['couponamt'])?></th>
			</tr>
		</tfoot>
</table>
</div>

</body>
</html>