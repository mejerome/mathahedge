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
<h3>Edit Bid Details</h3> 
<form  method="post" action="editedbid.php"  id="editform">
<?php 
include 'database.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT hedgebids.id, fwddate, bankid, fwdrate, amtbid, couponamt, bankname FROM hedgebids, banks
                            WHERE hedgebids.bankid = banks.id AND hedgebids.id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$record = $stmt->fetch(PDO::FETCH_OBJ);
?>
    <label for="id">Reference</label>
    <input type="text" id="id" name="id" readonly value="<?php echo $record->id;?>">
    <label for="bankname">Bank Name</label>
    <input type="text" id="bankname" name="bankname" readonly value="<?php echo $record->bankname;?>">
    <label for="fwdrate">Foward Rate</label>
    <input type="text" id="fwdrate" name="fwdrate" value="<?php echo $record->fwdrate;?>">
    <label for="amtbid">Bid Amount</label>
    <input type="text" id="amtbid" name="amtbid" value="<?php echo $record->amtbid;?>">
    <input type="submit" name="Update Record" value="update"/>
</form>
</div>
</body>
</html>
