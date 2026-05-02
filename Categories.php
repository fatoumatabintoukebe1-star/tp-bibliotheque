<?php 
require_once 'connexion.php';

class Categories {

private $conn;
private $table = "categories";

public $id;
public $libelle;

public function __construct($db){
    $this->conn = $db;
}

public function create () {
    $query = "INSERT INTO " . $this->table . " (libelle) VALUES (:libelle)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":libelle", $this->libelle);
    return $stmt->execute();
}

public function read () {
    $query = "SELECT * FROM " . $this->table . " ORDER BY libelle";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

public function update () {
    $query = "UPDATE " . $this->table . " SET libelle = :libelle WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":libelle", $this->libelle);
    $stmt->bindParam(":id", $this->id);
    return $stmt->execute();
}
public function delete() {
    $query = "DELETE FROM " . $this->table . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $this->id);
    return $stmt->execute();
}
}
?>