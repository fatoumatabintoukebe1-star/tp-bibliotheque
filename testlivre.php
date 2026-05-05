<?php
require_once 'connexion.php';
require_once 'Livre.php';

$database = new Database();
$db = $database->getConnexion();

$livre = new Livre($db);

$filtre_auteur = $_GET['auteur_id'] ?? null;
$filtre_categorie = $_GET['categorie_id'] ?? null;
$recherche = $_GET['search'] ?? null;

if (!empty($recherche)) {
    $result = $livre->search($recherche);
} elseif (!empty($filtre_auteur)) {
    $result = $livre->getByAuteur($filtre_auteur);
} elseif (!empty($filtre_categorie)) {
    $result = $livre->getByCategorie($filtre_categorie);
} else {
    $result = $livre->read();
}

$livres_data = $result->fetchAll(PDO::FETCH_ASSOC);

$total_livres = $livre->countAll();
$total_exemplaires = $livre->countExemplaires() ?? 0;

$auteurs_filtre = $db->query("SELECT id, nom, prenom FROM auteurs ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
$categories_filtre = $db->query("SELECT id, libelle FROM categories ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Catalogue des Livres</title>

<style>
body {
    font-family: Arial;
    background: #f4f6f8;
    padding: 20px;
}

.header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 20px;
}

.stats {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.stat {
    background: white;
    flex: 1;
    padding: 15px;
    text-align: center;
    border-radius: 10px;
}

.filters {
    background: white;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}

input, select {
    padding: 10px;
    margin: 5px;
    width: 30%;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.book-card {
    background: white;
    padding: 15px;
    border-radius: 10px;
    border-left: 5px solid #667eea;
}

.badge {
    padding: 5px 10px;
    border-radius: 10px;
    color: white;
    font-size: 12px;
}

.ok { background: #27ae60; }
.low { background: #f39c12; }
.no { background: #e74c3c; }
</style>
</head>

<body>

<div class="header">
    <h1>📚 Catalogue des Livres</h1>
</div>

<div class="stats">
    <div class="stat"><?= $total_livres ?><br>Livres</div>
    <div class="stat"><?= $total_exemplaires ?><br>Exemplaires</div>
    <div class="stat"><?= count($auteurs_filtre) ?><br>Auteurs</div>
    <div class="stat"><?= count($categories_filtre) ?><br>Catégories</div>
</div>

<div class="filters">
    <form method="GET">
        <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($recherche ?? '') ?>">
        
        <select name="auteur_id" onchange="this.form.submit()">
            <option value="">Tous les auteurs</option>
            <?php foreach($auteurs_filtre as $a): ?>
                <option value="<?= $a['id'] ?>" <?= ($filtre_auteur == $a['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['prenom'].' '.$a['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="categorie_id" onchange="this.form.submit()">
            <option value="">Toutes les catégories</option>
            <?php foreach($categories_filtre as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($filtre_categorie == $c['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="books-grid">
<?php foreach($livres_data as $row): 
    if($row['quantite'] <= 0) {
        $class = 'no';
        $text = 'Indisponible';
    } elseif($row['quantite'] <= 3) {
        $class = 'low';
        $text = 'Stock faible';
    } else {
        $class = 'ok';
        $text = 'Disponible';
    }
?>
    <div class="book-card">
        <h3><?= htmlspecialchars($row['titre']) ?></h3>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($row['auteur_prenom'].' '.$row['auteur_nom']) ?></p>
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($row['categorie_libelle']) ?></p>
        <p><strong>ISBN :</strong> <?= htmlspecialchars($row['isbn']) ?></p>
        <p><strong>Année :</strong> <?= htmlspecialchars($row['annee']) ?></p>
        <span class="badge <?= $class ?>"><?= $text ?> (<?= $row['quantite'] ?>)</span>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>