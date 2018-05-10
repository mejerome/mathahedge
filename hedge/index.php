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
      <li class="active"><a href="index.php">Home</a></li>
      <li><a href="banks.php">Banks</a></li>
      <li><a href="runauction.php">Run Auction</a></li>
      <li><a href="winresults.php">Auction Results</a></li>
    </ul>
  </div>
</nav>
  
<div class="container">
  <h1>WELCOME TO MATHA HEDGE AUCTION</h1>
  
	<h3>Upload Auction Sheet</h3>
	
	<form method="post" action="import_file.php" enctype="multipart/form-data">
  		<input type="file" name="file"/><br>
  		<input type="submit" name="submit_file" value="Upload"/>
   	</form>
</div>

<a href="test.php">Test Page</a>

</body>
</html>
