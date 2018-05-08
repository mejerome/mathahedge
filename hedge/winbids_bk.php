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
include 'database.php';
$batchref = $_POST['batchref'];
$query = "SELECT fwddate, couponamt, fwdrate, amtbid, bankname, bidoption FROM hedgebids, banks
               WHERE bank.id=hedgebids.bankid AND batchref=:batchref";
$stmt = $conn->prepare($query);
$stmt->bindParam(':batchref', $batchref);
$limits = $conn->prepare("SELECT * FROM banks where id=?");
$updatelimit = $conn->prepare("UPDATE banks SET banklimit=? WHERE id=?");
$stmt->execute();

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
</table>
</div>
</body>
</html>
