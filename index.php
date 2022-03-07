<?php







require("vendor/autoload.php");

use Carbon\Carbon;

if ($_SERVER['HTTP_HOST'] != "coffee-k6-lbf.herokuapp.com") {
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
}

$host = $_ENV["DB_HOST"] . ":" . $_ENV["DB_PORT"];
$dbname = $_ENV["DB_NAME"];
$username = $_ENV["DB_USERNAME"];
$password = $_ENV["DB_PASSWORD"];

$query = "SELECT name FROM waiter";

// $query2 = "SELECT * FROM edible ";


// Affichage des cafés à 1.3€

// $query2 = "SELECT name FROM edible WHERE price LIKE 1.3";


//$coffeeQuery = "SELECT name, price FROM edible WHERE FORMAT(price, 1) = 1.3";
$coffeeQuery = "SELECT name FROM edible";
// $execCoffeeQuery = $pdo->query($coffeeQuery);
// $coffees = $execCoffeeQuery->fetchAll();
// foreach ($coffees as $coffee){
//   print "<br>" . $coffee["name"] . $coffee["price"] . " €";
// }

// $orderCoffee = "SELECT price FROM ";





try {
  $conn = new PDO(
    "mysql:host=$host;dbname=$dbname",
    $username,
    $password,
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
  );
  $stmt = $conn->query($query);
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

  <?php // while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)): 
  ?>

  <p><?php //echo htmlspecialchars($row['name'] . " => " . $row['price'] . " €"); 
      ?></p>

  <?php //endwhile; 
  ?>

  <?php
  $result = $conn->query($coffeeQuery);
  foreach ($result as $row) {
    //echo $row['name'] . " => " . $row['price'] . " €" . "<br>";
    echo $row['name'] . "<br>";
  }
  ?>

  <h2>Les factures</h2>

  <?php


  $factureUne = "SELECT quantity, price FROM orderedible WHERE idOrder = 1";
  $execfactureUneReq = $conn->query($factureUne);
  $factures = $execfactureUneReq->fetchAll();
  $total = 0;

  foreach ($factures as $facture) {
    $total += $facture["price"] * $facture["quantity"];
  }
  print "La facture une = " . $total . " €";
  ?>


  <?php

  $ordersIdWaiter2 = "SELECT w.name, FORMAT(SUM(price * quantity),2) AS turnover 
                    FROM `order` AS o 
                    INNER JOIN `orderEdible` AS oe 
                    INNER JOIN `waiter` AS w
                    ON o.id = oe.idOrder 
                    WHERE o.idWaiter = 2;
                    AND w.id = o.idWaiter";
  $total = $conn->query($ordersIdWaiter2)->fetch(PDO::FETCH_OBJ);
  echo ("<br/>");
  print "La somme des commandes de "  . $total->name . " est de : " . $total->turnover . "€";
  echo ("<br/>");
  ?>


  <?php

  // $nameWaiterTable3 = "SELECT w.name 
  //                      FROM `waiter` AS w 
  //                      INNER JOIN `order` AS o          
  //                      ON o.idWaiter = w.id
  //                      WHERE o.idRestaurantTable = 3 ";

  $nameWaiterTable3 = "SELECT name 
                     FROM `waiter`
                     WHERE id IN (
                       SELECT `idWaiter`
                       FROM `order`
                       WHERE `idRestaurantTable` = 3
                     )";
  $waiters = $conn->query($nameWaiterTable3)->fetchAll();
  echo "Le nom du serveur de la table 3 : ";
  foreach ($waiters as $waiter) {
    echo  "<br/>"  . $waiter["name"];
  }


  ?>

  <?php
  // $mostConsumedCoffee = "SELECT SUM(oe.quantity) AS total
  //                         FROM `OrderEdible` AS oe
  //                         INNER JOIN `Edible` AS e
  //                         ON e.id = oe.idEdible
  //                         GROUP BY oe.idEdible
  //                         ORDER BY total DESC LIMIT 1;

  //                       SELECT e.name, SUM(oe.quantity) AS total 
  //                       FROM `OrderEdible` AS oe 
  //                       INNER JOIN `Edible` AS e 
  //                       ON e.id = oe.idEdible
  //                       GROUP BY oe.idEdible  
  //                       HAVING total = (
  //                         SELECT SUM(oe.quantity) AS total 
  //                         FROM `OrderEdible` AS oe 
  //                         INNER JOIN `Edible` AS e 
  //                         ON e.id = oe.idEdible 
  //                         GROUP BY oe.idEdible 
  //                         ORDER BY total DESC LIMIT 1";

  // $coffees = $conn->query($mostConsumedCoffee)->fetchAll();
  // echo "Les cafés les plus consommés sont les : ";
  // foreach ($coffees as $coffee) {
  //   echo  "<br/>"  . $coffee["name"];
  //}
  ?>

  <?php
  echo "<br/>";
  $infoOrderWaiter2 = "SELECT w.name AS waiter,o.createdAt AS creationDate,
                        FORMAT(SUM(oe.price * oe.quantity),2) AS turnover
                        FROM `order` AS o
                        INNER JOIN `waiter` AS w ON o.idWaiter = w.id
                        INNER JOIN `orderEdible` AS oe ON oe.idOrder = o.id
                        WHERE w.id=2
                        GROUP BY oe.idOrder";

  // Alternative :
  // SELECT name, createdAt, FORMAT(SUM(price), 2) AS facture 
  // FROM `Waiter`,`Order`, `OrderEdible` 
  // WHERE `Waiter`.id=`Order`.idWaiter 
  // AND `Order`.id=`OrderEdible`.idOrder AND idWaiter=2 GROUP BY `Order`.id;


  $orders = $conn->query($infoOrderWaiter2)->fetchAll();
  // var_dump($orders);
  echo "Les informations de commande du serveur 2 : ";
  foreach ($orders as $order) {
    $carbon = Carbon::parse($order["creationDate"]);
    echo  "<br/>"  . $order["waiter"] . " | " . $carbon->locale('fr')->diffForHumans() . " | " . $order["turnover"];
  }
  ?>





</body>

</html>