<?php 
require_once 'connexion.php';
class Livre {
private $conn;
private $table = "livres";

public $id;
public $titre;
public $isbn;
public $annee;
public $quantite;
public $auteur_id;
public $categorie_id;

public function __construct($db){
    $this->conn = $db;
}
public function create() {
    $query = "INSERT INTO " . $this->table . " 
                  (titre, isbn, annee, quantite, auteur_id, categorie_id) 
                  VALUES (:titre, :isbn, :annee, :quantite, :auteur_id, :categorie_id)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":titre", $this->titre);
    $stmt->bindParam(":isbn", $this->isbn);
    $stmt->bindParam(":annee", $this->annee);
    $stmt->bindParam(":quantite", $this->quantite);
    $stmt->bindParam(":auteur_id", $this->auteur_id);
    $stmt->bindParam(":categorie_id", $this->categorie_id);
        
    return $stmt->execute();
}

public function read() {
    $query = "  SELECT l.*, a.nom as auteur_nom, a.prenom, c.libelle as categorie_libelle 
                FROM " . $this->table . " l
                LEFT JOIN auteurs a ON l.auteur_id = a.id
                LEFT JOIN categories c ON l.categorie_id = c.id
                 ORDER BY l.titre";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
public function update(){
    $query = "UPDATE " . $this->table . " 
            SET titre = :titre, isbn = :isbn, annee = :annee, 
            quantite = :quantite, auteur_id = :auteur_id, categorie_id = :categorie_id 
            WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":titre", $this->titre);
    $stmt->bindParam(":isbn", $this->isbn);
    $stmt->bindParam(":annee", $this->annee);
    $stmt->bindParam(":quantite", $this->quantite);
    $stmt->bindParam(":auteur_id", $this->auteur_id);
    $stmt->bindParam(":categorie_id", $this->categorie_id);
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