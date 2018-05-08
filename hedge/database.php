<?php



try {
    $conn = new PDO('pgsql:host=localhost;port=5432;dbname=mathahedge;user=postgres;password=');
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
//      echo "Database connection was successful.";
} catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
}

?>
