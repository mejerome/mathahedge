<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hedging Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<style type="text/css">
		body {
			font-size: 15px;
			color: #343d44;
			font-family: "segoe-ui", "open-sans", tahoma, arial;
			padding: 0;
			margin: 0;
		}
		table {
			margin: auto;
			font-family: "Lucida Sans Unicode", "Lucida Grande", "Segoe Ui";
			font-size: 12px;
		}

		h1 {
			margin: 25px auto 0;
			text-align: center;
			text-transform: uppercase;
			font-size: 17px;
		}

		table td {
			transition: all .5s;
		}
		
		/* Table */
		.data-table {
			border-collapse: collapse;
			font-size: 14px;
			min-width: 537px;
		}

		.data-table th, 
		.data-table td {
			border: 1px solid #e1edff;
			padding: 7px 7px;
		}
		.data-table caption {
			margin: 7px;
		}

		/* Table Header */
		.data-table thead th {
			background-color: #508abb;
			color: #FFFFFF;
			border-color: #6ea1cc !important;
			text-transform: uppercase;
		}

		/* Table Body */
		.data-table tbody td {
			color: #353535;
		}
		.data-table tbody td:first-child,
		.data-table tbody td:nth-child(4),
		.data-table tbody td:last-child {
			text-align: right;
		}

		.data-table tbody tr:nth-child(odd) td {
			background-color: #f4fbff;
		}
		.data-table tbody tr:hover td {
			background-color: #ffffa2;
			border-color: #ffff0f;
		}

		/* Table Footer */
		.data-table tfoot th {
			background-color: #e5f5ff;
			text-align: right;
		}
		.data-table tfoot th:first-child {
			text-align: left;
		}
		.data-table tbody td:empty
		{
			background-color: #ffcccc;
		}
	</style>
  
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Hedges</a>
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