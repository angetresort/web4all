==============================================
A&S-WEB4ALL - PLATEFORME DE GESTION DES STAGES ET ALTERNANCES
==============================================

1. DESCRIPTION GÉNÉRALE
========================
A&S-Web4All est une application web permettant aux étudiants de consulter les offres de stages et alternances proposées par des entreprises partenaires, et de soumettre leurs candidatures en ligne.

2. FONCTIONNALITÉS PRINCIPALES
===============================

2.1 ACCUEIL (index.php)
- Affichage de toutes les offres disponibles avec pagination (6 offres par page)
- Filtrage par entreprise
- Affichage de l'équipe informatique
- Boutons "Voir les entreprises" et "Candidater" pour chaque offre

2.2 MENTIONS LÉGALES (mentions-legales.php)
- Affichage des conditions générales et politique de confidentialité
- Checkbox d'acceptation obligatoire pour accéder au formulaire de candidature
- Boutons "Tout accepter" et "Tout refuser"

2.3 FORMULAIRE DE CANDIDATURE (formulaire.php)
- Affichage des informations de l'offre sélectionnée
- Champs du formulaire :
  * Nom (obligatoire, en majuscules)
  * Prénom (obligatoire)
  * Sexe (obligatoire : Masculin, Féminin, Autre)
  * Date de naissance (obligatoire)
  * Email (obligatoire, validation de format)
  * CV (obligatoire, fichiers acceptés : pdf, doc, docx, odt, rtf, jpg, png)
  * Lettre de motivation (obligatoire, mêmes formats que CV)
  * Message au recruteur (optionnel)
- Validation côté client (JavaScript) et serveur (PHP)
- Upload des fichiers dans le dossier "uploads/"
- Enregistrement des candidatures en base de données
- Redirection vers page de confirmation après succès

2.4 PAGE DE CONFIRMATION (merci.php)
- Message de remerciement après soumission réussie
- Lien pour retourner à l'accueil

3. ARCHITECTURE TECHNIQUE
==========================

3.1 BASE DE DONNÉES
- Serveur MySQL : Web4all_db
- Tables principales :
  * offres : id, titre, type_offre, description, competences, duree, entreprise_id
  * entreprises : id, nom, ...
  * candidatures : id, nom, prenom, sexe, date_naissance, email, cv_path, lettre_path, offre_id

3.2 FICHIERS PHP
- index.php : page d'accueil avec liste des offres
- formulaire.php : formulaire de candidature
- mentions-legales.php : conditions d'utilisation
- connexion.php : configuration centralisée de la connexion BD

3.3 FICHIERS STATIQUES
- style.css : feuille de styles
- images/ : dossier contenant les images (logo, équipe, etc.)
- uploads/ : dossier de stockage des CV et lettres (créé dynamiquement)

3.4 SÉCURITÉ
- Utilisation de PDO avec requêtes paramétrées (protection contre l'injection SQL)
- Validation et échappement des données (htmlspecialchars, filter_var)
- Vérification des types de fichiers uploadés
- Limite de taille des fichiers (2 Mo max)
- Gestion des erreurs PDO

4. FLUX UTILISATEUR
====================

Étape 1 : CONSULTATION DES OFFRES
- L'utilisateur accède à index.php
- Visualise les offres disponibles
- Peut filtrer par entreprise ou naviguer par pages

Étape 2 : SÉLECTION D'UNE OFFRE
- Clique sur le bouton "Candidater" d'une offre
- Est redirigé vers mentions-legales.php?offer=ID

Étape 3 : ACCEPTATION DES CONDITIONS
- Doit cocher la case d'acceptation
- Le bouton "Tout accepter" se déverrouille
- Est redirigé vers formulaire.php?offer=ID

Étape 4 : REMPLISSAGE DU FORMULAIRE
- Remplit tous les champs obligatoires
- Valide le format email
- Upload CV et lettre de motivation
- Clique "Soumettre ma candidature"

Étape 5 : ENREGISTREMENT EN BASE
- Les données et fichiers sont envoyés au serveur
- Validations côté serveur
- Enregistrement en base de données
- Redirection vers merci.php

Étape 6 : CONFIRMATION
- Message de remerciement
- Lien pour retourner à l'accueil

5. INSTALLATION ET DÉPLOIEMENT
================================

5.1 EN LOCAL (XAMPP)
- Copier le dossier Electives-Web dans c:\xampp\htdocs\
- Créer la base Web4all_db via phpMyAdmin
- Importer les tables et données
- Accéder via http://localhost/Electives-Web/

5.2 EN LIGNE (INFINITYFREE)
- Créer un compte sur infinityfree.net
- Uploader tous les fichiers dans le dossier htdocs/
- Créer une base MySQL via le panneau
- Importer la base de données SQL
- Mettre à jour connexion.php avec les identifiants InfinityFree
- Accéder via https://ton-sous-domaine.epizy.com/

5.3 CONFIGURATION
- Modifier connexion.php pour les identifiants BD
- Créer le dossier uploads/ et vérifier ses permissions
- Vérifier que images/ contient toutes les images nécessaires

6. TECHNOLOGIES UTILISÉES
===========================
- PHP 7+ : traitement serveur, gestion BD, uploads
- MySQL : stockage des données
- HTML5 : structure des pages
- CSS3 : mise en forme et responsive design
- JavaScript : validation côté client, interactions
- PDO : accès sécurisé à la base de données

7. FICHIERS IMPORTANTS À RETENIR
=================================
- connexion.php : TOUJOURS inclure ce fichier en début de chaque page PHP
- style.css : feuille de styles commune
- uploads/ : dossier d'upload (doit exister et être accessible en écriture)
- images/ : images du site (logo, équipe, etc.)

8. DÉPANNAGE
=============
- "Aucune offre sélectionnée" : vérifier que l'URL contient ?offer=ID
- Erreur BD : vérifier config.php avec les bons identifiants
- Images ne s'affichent pas : vérifier le chemin relatif images/nom.jpg
- Uploads ne fonctionnent pas : vérifier permissions du dossier uploads/
- Erreur 404 : vérifier que les fichiers .php sont dans le bon dossier

9. DESCRIPTION GÉNÉRALE
=======================
Lien:

==============================================
FIN DU DOCUMENTATION
==============================================