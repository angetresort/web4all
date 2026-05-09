<?php
$offerId = '';
if (isset($_GET['offer'])) {
    $offerId = $_GET['offer'];
} elseif (isset($_GET['offerId'])) {
    $offerId = $_GET['offerId'];
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description"
            content="Consultez les mentions légales et la politique de confidentialité de A&S-Web4All.">
        <title>Mentions Légales | A&S-Web4All</title>
        <link rel="stylesheet" href="/Style.css">
        <style>
        /* Style pour indiquer que le bouton est désactivé */
        .btn-accept:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .consent-checkbox-container {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #eee;
        }
    </style>
    </head>
    <body>
        <header>
            <h1>A&S-Web4All</h1>
        </header>

        <main class="container">
            <section class="legal-content">
                <h2>Mentions Légales & Confidentialité</h2>

                <div class="legal-text">
                    <h3>1. Édition du site</h3>
                    <p>Le site <strong>A&S-Web4All</strong> est édité dans le
                        cadre d'un projet pédagogique par l'équipe A&S, résidant
                        à Nancy, France.</p>

                    <h3>2. Hébergement</h3>
                    <p>Ce site est hébergé localement ou via une plateforme de
                        démonstration à des fins éducatives.</p>

                    <h3>3. Protection des données (RGPD)</h3>
                    <p>Conformément à la réglementation européenne, les
                        informations recueillies via le formulaire de
                        candidature (Nom, Email, CV) sont traitées avec la plus
                        grande confidentialité.</p>
                    <ul>
                        <li><strong>Finalité :</strong> Traitement exclusif de
                            votre candidature.</li>
                        <li><strong>Conservation :</strong> Les données sont
                            conservées pendant une durée maximale de 12
                            mois.</li>
                        <li><strong>Vos droits :</strong> Vous disposez d'un
                            droit d'accès, de rectification et de suppression de
                            vos données personnelles.</li>
                    </ul>

                    <h3>4. Propriété intellectuelle</h3>
                    <p>L'ensemble du contenu (textes, graphismes, logos) est la
                        propriété de A&S-Web4All, sauf mention contraire.</p>
                </div>

                <div class="consent-box">
                    <p><strong>Acceptez-vous que vos données soient traitées
                            pour postuler aux offres ?</strong></p>

                    <div class="consent-checkbox-container">
                        <input type="checkbox" id="acceptConditions">
                        <label for="acceptConditions">J'accepte les conditions
                            générales et de confidentialité de
                            l'entreprise</label>
                    </div>

                    <div class="decision-buttons">
                        <button id="btnAccept"
                            onclick="window.location.href='formulaire.php?offer=<?php echo htmlspecialchars($offerId, ENT_QUOTES, 'UTF-8'); ?>'"
                            class="btn-accept" disabled>TOUT ACCEPTER</button>
                        <button onclick="history.back()" class="btn-refuse">TOUT
                            REFUSER</button>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <p>All rights reserved A&S-Web4All 2026-2026</p>
        </footer>

        <script>
        const checkbox = document.getElementById('acceptConditions');
        const btnAccept = document.getElementById('btnAccept');

        // Gestion de l'activation du bouton en fonction de la checkbox
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                btnAccept.disabled = false;
            } else {
                btnAccept.disabled = true;
            }
        });
    </script>
    </body>
</html>