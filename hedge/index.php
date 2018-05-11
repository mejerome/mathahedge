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
  
<div class="container-fluid">
  <h1>WELCOME TO MATHA HEDGE AUCTION</h1>
  
	<h3>Upload Auction Sheet</h3>
	
	<form method="post" action="import_file.php" enctype="multipart/form-data">
  		<input type="file" name="file"/><br>
  		<input type="submit" name="submit_file" value="Upload"/>
   	</form>

<a href="test.php">Test Page</a><br>
<a href="searchbid.php">Edit A Bid..</a>

</div>


</body>
</html>
