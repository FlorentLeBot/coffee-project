<?php

require("vendor/autoload.php");
if ($_SERVER['HTTP_HOST'] != "coffee-k6-lbf.herokuapp.com") {
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
}

$host = $_ENV["DB_HOST"] . ":" . $_ENV["DB_PORT"];
$dbname = $_ENV["DB_NAME"];
$username = $_ENV["DB_USERNAME"];
$password = $_ENV["DB_PASSWORD"];

$query = "SELECT name FROM waiter";
$query2 = "SELECT * FROM edible ";



try {
  $conn = new PDO(
    "mysql:host=$host;dbname=$dbname",
    $username,
    $password,
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
  );
  $stmt = $conn->query($query);
  $stmt2 = $conn->query($query2);
} catch (PDOException $e) {

  die("Impossible de se connecter à la base de données $dbname :" . $e->getMessage());
}






?>

<!DOCTYPE html>
<html>


<body>

  <p><?= "Hello world !"; ?></p>

  <h1>Liste des serveurs</h1>

  <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>

    <p><?php echo htmlspecialchars($row['name']); ?></p>

  <?php endwhile; ?>

  <h2>Liste des cafés</h2>

  <?php while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)): ?>

    <p><?php echo htmlspecialchars($row['name'] . " => " . $row['price']); ?></p>

  <?php endwhile; ?>


</body>

</html>