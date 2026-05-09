<?php
require_once 'connexion.php';

$offerId = '';
if (!empty($_GET['offer'])) {
    $offerId = $_GET['offer'];
} elseif (!empty($_GET['offerId'])) {
    $offerId = $_GET['offerId'];
} elseif (!empty($_GET['offer_id'])) {
    $offerId = $_GET['offer_id'];
} elseif (!empty($_GET['offre_id'])) {
    $offerId = $_GET['offre_id'];
} elseif (!empty($_POST['offer'])) {
    $offerId = $_POST['offer'];
} elseif (!empty($_POST['offerId'])) {
    $offerId = $_POST['offerId'];
} elseif (!empty($_POST['offer_id'])) {
    $offerId = $_POST['offer_id'];
} elseif (!empty($_POST['offre_id'])) {
    $offerId = $_POST['offre_id'];
}

$offerInfo = null;
if ($offerId !== '') {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=Web4all_db;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('
            SELECT o.*, e.nom AS entreprise_nom
            FROM offres o
            LEFT JOIN entreprises e ON e.id = o.entreprise_id
            WHERE o.id = :id
            LIMIT 1
        ');
        $stmt->execute([':id' => $offerId]);
        $offerInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $offerInfo = null;
    }
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $sexe = trim($_POST['sexe'] ?? '');
    $dateNaissance = trim($_POST['dateNaissance'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $offerId = trim($_POST['offerId'] ?? $_POST['offer_id'] ?? $_POST['offre_id'] ?? $offerId);

    if ($offerId === '') {
        $error = 'Aucune offre sélectionnée. Retournez à la page des offres et cliquez sur "Postuler" pour choisir une offre.';
    } elseif ($nom === '' || $prenom === '' || $sexe === '' || $dateNaissance === '' || $email === '') {
        $error = 'Merci de remplir les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse e-mail invalide.';
    } elseif (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Veuillez télécharger votre CV.';
    } elseif (!isset($_FILES['lettre']) || $_FILES['lettre']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Veuillez télécharger votre lettre de motivation.';
    } else {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowed = ['pdf', 'doc', 'docx', 'odt', 'rtf', 'jpg', 'jpeg', 'png'];
        $cvName = basename($_FILES['cv']['name']);
        $lettreName = basename($_FILES['lettre']['name']);
        $cvExt = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));
        $lettreExt = strtolower(pathinfo($lettreName, PATHINFO_EXTENSION));

        if (!in_array($cvExt, $allowed) || !in_array($lettreExt, $allowed)) {
            $error = 'Format de fichier non autorisé.';
        } else {
            $cvPath = 'uploads/' . time() . '_cv_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $cvName);
            $lettrePath = 'uploads/' . time() . '_lettre_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $lettreName);

            if (
                move_uploaded_file($_FILES['cv']['tmp_name'], __DIR__ . '/' . $cvPath) &&
                move_uploaded_file($_FILES['lettre']['tmp_name'], __DIR__ . '/' . $lettrePath)
            ) {
                try {
                    $pdo = new PDO('mysql:host=localhost;dbname=Web4all_db;charset=utf8', 'root', '');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $pdo->prepare('INSERT INTO candidatures (nom, prenom, sexe, date_naissance, email, cv_path, lettre_path, offre_id) VALUES (:nom, :prenom, :sexe, :date_naissance, :email, :cv_path, :lettre_path, :offre_id)');
                    $stmt->execute([
                        ':nom' => $nom,
                        ':prenom' => $prenom,
                        ':sexe' => $sexe,
                        ':date_naissance' => $dateNaissance,
                        ':email' => $email,
                        ':cv_path' => $cvPath,
                        ':lettre_path' => $lettrePath,
                        ':offre_id' => $offerId
                    ]);

                    $success = 'Votre candidature a été envoyée avec succès.';
                } catch (PDOException $e) {
                    $error = 'Erreur serveur : ' . $e->getMessage();
                }
            } else {
                $error = 'Impossible d\'enregistrer les fichiers.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler | A&S-Web4All</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>A&S-Web4All</h1>
    </header>
    <main class="container">
        <form id="applyForm" class="form-card" method="POST" enctype="multipart/form-data">
            <h2>Dossier de Candidature</h2>

            <?php if ($offerInfo): ?>
                <div class="offer-details" style="margin-bottom:20px;padding:10px;border:1px solid #ccc;border-radius:5px;">
                    <h3><?php echo htmlspecialchars($offerInfo['titre']); ?></h3>
                    <p><strong>Type :</strong> <?php echo htmlspecialchars($offerInfo['type_offre']); ?></p>
                    <p><strong>Description :</strong><br><?php echo nl2br(htmlspecialchars($offerInfo['description'])); ?></p>
                    <?php if (!empty($offerInfo['competences'])): ?>
                        <p><strong>Compétences :</strong> <?php echo htmlspecialchars($offerInfo['competences']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($offerInfo['duree'])): ?>
                        <p><strong>Durée :</strong> <?php echo htmlspecialchars($offerInfo['duree']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($offerInfo['entreprise_nom'])): ?>
                        <p><strong>Entreprise :</strong> <?php echo htmlspecialchars($offerInfo['entreprise_nom']); ?></p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="offer-details" style="margin-bottom:20px;padding:10px;border:1px solid #ccc;border-radius:5px;">
                    <p>Aucune offre sélectionnée.</p>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php elseif ($success): ?>
                <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <?php if ($offerId === ''): ?>
                <p style="color:red;">Aucune offre sélectionnée. Ouvrez le formulaire depuis la page des offres.</p>
            <?php endif; ?>

            <input type="hidden" name="offerId" value="<?php echo htmlspecialchars($offerId, ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group-row">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" placeholder="Nom" required>
            </div>
            <div class="form-group-row">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group-row">
                <label for="sexe">Sexe</label>
                <select id="sexe" name="sexe" required>
                    <option value="">Sélectionnez</option>
                    <option value="Masculin">Masculin</option>
                    <option value="Feminin">Feminin</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="form-group-row">
                <label for="dateNaissance">Date de naissance</label>
                <input type="date" id="dateNaissance" name="dateNaissance" title="Date de naissance" required>
            </div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email (ex: nom@mail.com)" required>
            <div class="file-upload">
                <label for="cv">Télécharger votre CV</label>
                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx,.odt,.rtf,.jpg,.png" required>
            </div>
            <div class="file-upload">
                <label for="lettre">Télécharger votre lettre de motivation</label>
                <input type="file" id="lettre" name="lettre" accept=".pdf,.doc,.docx,.odt,.rtf,.jpg,.png" required>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn-main" <?php echo $offerId === '' ? 'disabled' : ''; ?>>SOUMETTRE MA CANDIDATURE</button>
                <button type="button" class="btn-main" onclick="window.location.href='index.php'">RETOUR AUX RECHERCHES</button>
            </div>
        </form>
    </main>

    <footer>
        <p>All rights reserved A&S-Web4All 2026-2026</p>
    </footer>

    <script>
        // Mise en majuscule du NOM après la saisie (événement blur)
        const inputNom = document.getElementById('nom');
        inputNom.addEventListener('blur', function() {
            this.value = this.value.toUpperCase();
        });

        // Gestion de la soumission du formulaire
        const form = document.getElementById('applyForm');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Bloque l'envoi pour vérification

            const nom = document.getElementById('nom').value.trim();
            const prenom = document.getElementById('prenom').value.trim();
            const sexe = document.getElementById('sexe').value.trim();
            const dateNaissance = document.getElementById('dateNaissance').value.trim();
            const email = document.getElementById('email').value.trim();
            const cvFile = document.getElementById('cv').files[0];
            const lettreFile = document.getElementById('lettre').files[0];

            // Vérification des champs obligatoires (Nom, Prénom, Sexe, Date de naissance, Email)
            if (!nom || !prenom || !sexe || !dateNaissance || !email) {
                alert("Erreur : Les champs Nom, Prénom, Sexe, Date de naissance et Email sont obligatoires.");
                return;
            }

            // Vérification du format Email avec Expression Régulière
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Veuillez saisir une adresse e-mail valide.");
                return;
            }

            // Vérification du poids des fichiers (2 Mo max)
            const MAX_SIZE = 2 * 1024 * 1024; // 2 Mo en octets

            if (cvFile && cvFile.size > MAX_SIZE) {
                alert("Le CV dépasse la limite autorisée de 2 Mo.");
                return;
            }

            if (lettreFile && lettreFile.size > MAX_SIZE) {
                alert("La lettre de motivation dépasse la limite autorisée de 2 Mo.");
                return;
            }

            // Si tout est ok, soumettre le formulaire
            form.submit();
        });
    </script>
</body>

</html>