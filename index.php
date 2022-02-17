<?php



// // function bdd()
// // {
// //     try {
// //         $bdd = new PDO("mysql:dbname=abclight;host=localhost", "root", "");
// //     } catch (PDOException $e) {
// //         echo "Connexion impossible: " . $e->getMessage();
// //     }
// //     return $bdd;
// // }

$host = 'mysql-69231-0.cloudclusters.net:17774';
$dbname = 'abclight';
$username = 'admin';
$password = 'DO2KbDQN';

$query = "SELECT name FROM waiter";

try {
  $conn = new PDO(
    "mysql:host=$host;dbname=$dbname",
    $username,
    $password,
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
  );
  // var_dump($conn);
  $stmt = $conn->query($query);
} catch (PDOException $e) {

  die("Impossible de se connecter à la base de données $dbname :" . $e->getMessage());
}

?>

<!DOCTYPE html>
<html>

<head></head>

<body>

  <p><?= "Hello world !"; ?></p>
  
  <h1>Liste des serveurs</h1>

  <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>



    <p><?php echo htmlspecialchars($row['name']); ?></p>

  <?php endwhile; ?>

</body>

</html>