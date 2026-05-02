<?php 
 class Database {
 private $host = "localhost";
 private $db_name = "bibliotheque";
 private $username = "root";
 private $password = "";
 private static $connexion = null;

 public static function getConnexion() {
if (self::$connexion === null) {
try {
 self::$connexion = new PDO("mysql:host=localhost;
dbname=bibliotheque;charset=utf8", "root", "");
 self::$connexion->setAttribute(PDO::ATTR_ERRMODE,
PDO::ERRMODE_EXCEPTION);
 } catch (PDOException $e) {
 die("Erreur de connexion : " . $e->getMessage());
 }
 }
 return self::$connexion;
 }
}
?>
