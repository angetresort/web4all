<?php
require_once 'connexion.php';

// Connexion à la base de données


$selectedCompany = isset($_GET['company']) ? $_GET['company'] : null;
$itemsPerPage = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description"
            content="A&S-Web4All : Gestion des stages et alternances.">
        <title>Accueil | A&S-Web4All</title>
        <link rel="stylesheet" href="style.css">
        <style>
        /* --- HEADER --- */
        header {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        body {
            text-align: center;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* --- BOUTON MENU (BARRES + TEXTE) --- */
        .burger-container {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 5px;
            z-index: 1100;
        }

        .burger-text { font-weight: bold; color: #333; }
        .burger-bars { display: flex; flex-direction: column; justify-content: space-between; width: 25px; height: 18px; }
        .burger-bars span { width: 100%; height: 3px; background-color: #333; border-radius: 2px; }

        /* --- NAVIGATION LATERALE --- */
        .nav-links {
            position: fixed;
            top: 0;
            right: -320px;
            height: 100vh;
            width: 280px;
            background: #ffffff;
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            z-index: 2000;
            transition: right 0.3s ease;
            padding: 20px;
            display: block;
        }

        .nav-links.active {
            right: 0;
        }

        .close-menu {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 2rem;
            background: none;
            border: none;
            cursor: pointer;
        }

        .nav-links ul {
            list-style: none;
            padding: 80px 0 0 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 1.2rem;
            padding: 10px;
            width: 100%;
            text-align: center;
            display: block;
        }

        .nav-links a:hover { color: #0056b3; }

        /* --- CSS AJOUTÉ : BARRES DE RECHERCHE SUR UNE LIGNE --- */
        .search-bar-container {
            display: flex;
            justify-content: center; /* Centre les barres */
            align-items: center;
            gap: 20px;             /* Espace entre les menus */
            padding: 20px;
            background-color: #f4f7f6;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .search-bar-container select {
            padding: 10px 15px;
            border-radius: 25px;   /* Design arrondi */
            border: 1px solid #ccc;
            background-color: white;
            font-size: 1rem;
            cursor: pointer;
            outline: none;
            min-width: 180px;      /* Largeur minimum égale pour tous */
            transition: border-color 0.3s;
        }

        .search-bar-container select:hover {
            border-color: #0056b3;
        }

        /* --- FIN CSS AJOUTÉ --- */

        #backToTop {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            padding: 10px 15px;
            background: #0056b3;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 900;
        }

        /* --- CSS POUR LES CARTES D'ENTREPRISES ET OFFRES --- */
        .company-grid, .job-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .company-card, .job-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .company-card h3, .job-card h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .company-card p, .job-card p {
            margin-bottom: 15px;
            color: #666;
        }

        .company-card button,
        .job-card button {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 14px 0;
            background: linear-gradient(135deg, #4b3cff, #7a4bff);
            color: #fff;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            transition: background 0.25s ease, transform 0.2s ease;
        }

        .company-card button:hover,
        .job-card button:hover {
            background: linear-gradient(135deg, #392dd9, #6640e4);
            transform: translateY(-1px);
        }

        .offres-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
            text-align: center;
        }

        .offres-header h2 {
            margin: 0;
        }

        .back-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: min(280px, 100%);
            padding: 14px 22px;
            background: linear-gradient(135deg, #4b3cff, #7a4bff);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: background 0.25s ease, transform 0.2s ease;
            margin-top: 1rem;
        }

        .back-button:hover {
            background: linear-gradient(135deg, #392dd9, #6640e4);
            transform: translateY(-1px);
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        .pagination a {
            padding: 10px 15px;
            background: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background: #003d82;
        }
    </style>
    
    </head>
    <body>

        <header>
            <div class="header-container">
                <h1>A&S-Web4All</h1>

                <button class="burger-container" id="burgerBtn">
                    <span class="burger-text"><h2>MENU</h2></span>
                    <div class="burger-bars">
                        <span></span><span></span><span></span>
                    </div>
                </button>

                <nav id="navMenu" class="nav-links">
                    <button class="close-menu" id="closeBtn">✕</button>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="formulaire.php">Postuler</a></li>
                        <li><a href="mentions-legales.php">Légal</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <main>
            <section class="hero">
                <h2>Propulsez votre carrière avec A&S-Web4All</h2>
                <p>L'excellence en informatique commence ici.</p>
                <a href="#entreprises" class="btn-main">Voir les entreprises</a>
            </section>

            <div class="team-image-container">
                <img src="/Electives-Web/images/Equipe.png" alt="Équipe informatique de six membres" class="team-image">
            </div>

            <?php
            if (!$selectedCompany) {
                ?>
                <div class="search-bar-container">
                    <select id="filterType" onchange="filterJobs()">
                        <option value="all">Type (Stage/Alternance)</option>
                        <option value="Stage">Stage</option>
                        <option value="Alternance">Alternance</option>
                    </select>

                    <select id="filterLieu" onchange="filterJobs()">
                        <option value="all">Ville (Toutes)</option>
                        <option value="Nancy">Nancy</option>
                        <option value="Paris">Paris</option>
                        <option value="Lyon">Lyon</option>
                        <option value="Remote">À distance</option>
                    </select>

                    <select id="filterDuree" onchange="filterJobs()">
                        <option value="all">Durée (Toutes)</option>
                        <option value="court">2-3 mois (Stage)</option>
                        <option value="long">6 mois+ (Alternance)</option>
                    </select>
                </div>
                <?php
                // Afficher les entreprises avec pagination
                $totalStmt = $pdo->query('SELECT COUNT(*) FROM entreprises');
                $totalCompanies = $totalStmt->fetchColumn();
                $totalPages = ceil($totalCompanies / $itemsPerPage);

                echo '<section id="entreprises" class="company-grid">';
                $stmt = $pdo->prepare('SELECT * FROM entreprises LIMIT ? OFFSET ?');
                $stmt->bindValue(1, $itemsPerPage, PDO::PARAM_INT);
                $stmt->bindValue(2, $offset, PDO::PARAM_INT);
                $stmt->execute();
                while ($entreprise = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="company-card">';
                    echo '<h3>' . htmlspecialchars($entreprise['nom']) . '</h3>';
                    echo '<p>' . htmlspecialchars($entreprise['secteur']) .'</p>';
                    echo '<p>' . htmlspecialchars($entreprise['taille']) .' employés </p>';
                    echo '<p>' . htmlspecialchars($entreprise['ville']) . '</p>';
                    echo '<button onclick="window.location.href=\'?company=' . urlencode($entreprise['nom']) . '\'">Voir les offres</button>';
                    echo '</div>';
                }
                echo '</section>';

                // Pagination pour entreprises
                echo '<div class="pagination">';
                if ($page > 1) {
                    echo '<a href="?page=' . ($page - 1) . '">Précédent</a>';
                }
                if ($page < $totalPages) {
                    echo '<a href="?page=' . ($page + 1) . '">Suivant</a>';
                }
                echo '</div>';
            } else {
                ?>
                <div class="search-bar-container">
                    <select id="filterType" onchange="filterJobs()">
                        <option value="all">Type (Stage/Alternance)</option>
                        <option value="Stage">Stage</option>
                        <option value="Alternance">Alternance</option>
                    </select>

                    <select id="filterLieu" onchange="filterJobs()">
                        <option value="all">Ville (Toutes)</option>
                        <option value="Nancy">Nancy</option>
                        <option value="Paris">Paris</option>
                        <option value="Lyon">Lyon</option>
                        <option value="Remote">À distance</option>
                    </select>

                    <select id="filterDuree" onchange="filterJobs()">
                        <option value="all">Durée (Toutes)</option>
                        <option value="court">2-3 mois (Stage)</option>
                        <option value="long">6 mois+ (Alternance)</option>
                    </select>
                </div>
                <?php
                // Récupérer l'ID de l'entreprise
                $stmtCompany = $pdo->prepare('SELECT id FROM entreprises WHERE nom = ? LIMIT 1');
                $stmtCompany->execute([$selectedCompany]);
                $company = $stmtCompany->fetch(PDO::FETCH_ASSOC);

                if ($company) {
                    $companyId = $company['id'];

                    // Afficher les offres avec pagination
                    $totalStmt = $pdo->prepare('SELECT COUNT(*) FROM offres WHERE entreprise_id = ?');
                    $totalStmt->execute([$companyId]);
                    $totalOffers = $totalStmt->fetchColumn();
                    $totalPages = ceil($totalOffers / $itemsPerPage);

                    echo '<div class="offres-header">';
                    echo '<h2>Offres de ' . htmlspecialchars($selectedCompany) . '</h2>';
                    echo '<button class="back-button" onclick="window.location.href=\'index.php\'">Retour aux entreprises</button>';
                    echo '</div>';

                    echo '<section id="offres" class="job-grid">';
                    $stmt = $pdo->prepare('SELECT * FROM offres WHERE entreprise_id = ? LIMIT ? OFFSET ?');
                    $stmt->bindValue(1, $companyId, PDO::PARAM_INT);
                    $stmt->bindValue(2, $itemsPerPage, PDO::PARAM_INT);
                    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
                    $stmt->execute();

                    while ($offre = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="job-card">';
                        echo '<h3>' . htmlspecialchars($offre['titre']) . '</h3>';
                        echo '<p>' . htmlspecialchars($offre['type_offre']) . '</p>';
                        if (!empty($offre['duree'])) {
                            echo '<p>' . htmlspecialchars($offre['duree']) . '</p>';
                        }
                        echo '<button onclick="window.location.href=\'mentions-legales.php?offer=' . urlencode($offre['id']) . '\'">Candidater</button>';
                        echo '</div>';
                    }
                    echo '</section>';

                    // Pagination pour offres
                    echo '<div class="pagination">';
                    if ($page > 1) {
                        echo '<a href="?company=' . urlencode($selectedCompany) . '&page=' . ($page - 1) . '">Précédent</a>';
                    }
                    if ($page < $totalPages) {
                        echo '<a href="?company=' . urlencode($selectedCompany) . '&page=' . ($page + 1) . '">Suivant</a>';
                    }
                    echo '</div>';
                } else {
                    echo '<p>Entreprise non trouvée.</p>';
                }
            }
            ?>
        </main>

        <button id="backToTop">↑</button>

        <footer><p>All rights reserved A&S-Web4All 2026</p></footer>

        <script>
        const burgerBtn = document.getElementById('burgerBtn');
        const closeBtn = document.getElementById('closeBtn');
        const navMenu = document.getElementById('navMenu');

        burgerBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            navMenu.classList.add('active');
        });

        closeBtn.addEventListener('click', () => {
            navMenu.classList.remove('active');
        });

        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && e.target !== burgerBtn) {
                navMenu.classList.remove('active');
            }
        });

        const btt = document.getElementById('backToTop');
        window.onscroll = () => btt.style.display = (window.scrollY > 300) ? "block" : "none";
        btt.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });

        function filterJobs() {
            const typeValue = document.getElementById('filterType').value.toLowerCase();
            const lieuValue = document.getElementById('filterLieu').value.toLowerCase();
            const dureeValue = document.getElementById('filterDuree').value;
            
            const cards = document.querySelectorAll('.job-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                
                const typeMatch = (typeValue === 'all' || text.includes(typeValue));
                const lieuMatch = (lieuValue === 'all' || text.includes(lieuValue));
                
                let dureeMatch = true;
                if (dureeValue === 'court') {
                    dureeMatch = text.includes('2-3 mois');
                } else if (dureeValue === 'long') {
                    dureeMatch = text.includes('6 mois');
                }
                
                if (typeMatch && lieuMatch && dureeMatch) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>
    </body>
</html>