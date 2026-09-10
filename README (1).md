# Site web ATS — Association pour le Développement Social

Site vitrine + espace d'administration pour ATS, une association créée par des jeunes pour soutenir le développement social d'un quartier.

## Aperçu

- **Accueil** — présentation de l'association, fond bleu marine, style classe
- **À propos** — histoire de l'association
- **Bureau** — 4 pôles présentés en boxes (Administration, Services Financiers, Services Organisationnels, Coordination)
- **Projets** — cartes cliquables ouvrant une vidéo (reboisement, sensibilisation, nettoyage, etc.)
- **Contact** — formulaire relié à une base de données + liens Facebook et TikTok
- **Admin** — espace protégé par mot de passe pour consulter les messages reçus

## Structure du projet

```
/
├── index.html              Page principale du site
├── style.css                Styles du site
├── script.js                 Interactions (menu, lightbox vidéo, formulaire)
├── contact.php                Traite le formulaire (enregistre en base + envoie un email)
├── config.php                  Identifiants de connexion à la base (NE JAMAIS envoyer sur Git)
├── config.example.php           Modèle de config.php, sans vraies infos (celui-ci va sur Git)
├── database.sql                  Script SQL complet (avec CREATE DATABASE) — hébergement classique
├── database-infinityfree.sql      Script SQL sans CREATE DATABASE — pour InfinityFree
├── .gitignore                      Empêche config.php d'être envoyé sur Git
├── videos/                          Vidéos des projets (reboisement.mp4, etc.)
└── admin/
    ├── login.php                     Page de connexion admin
    ├── admin.php                      Liste des messages reçus
    ├── logout.php                      Déconnexion
    └── admin-style.css                  Styles de l'espace admin
```

## Installation en local (WAMP)

1. Copier tout le dossier dans `C:\wamp64\www\ADS`
2. Démarrer WAMP (icône verte)
3. Aller sur `http://localhost/phpmyadmin/`, créer une base nommée `ats_association`, importer `database.sql`
4. Copier `config.example.php` en `config.php`, remplir `DB_USER` (souvent `root`), `DB_PASS` (souvent vide sous WAMP), et changer `ADMIN_PASSWORD`
5. Ouvrir `http://localhost/ADS/`

## Mise en ligne (hébergement)

1. Créer la base de données MySQL chez l'hébergeur (via son panneau, ex: cPanel)
2. Importer le bon fichier SQL selon l'hébergeur :
   - Hébergement classique (cPanel, etc.) → `database.sql`
   - InfinityFree → `database-infinityfree.sql`
3. Envoyer tous les fichiers du projet sauf `config.example.php` et `.gitignore` (pas obligatoires en ligne, mais sans danger si envoyés)
4. Remplir `config.php` avec les vrais identifiants MySQL fournis par l'hébergeur
5. Dans `contact.php`, remplacer l'adresse email de réception (`$destinataire`)
6. Se connecter à `/admin/login.php` avec le mot de passe défini dans `config.php`

## À compléter

- [ ] Remplacer les vidéos de démonstration par les vraies vidéos des projets dans `videos/`
- [ ] Mettre à jour les vrais liens Facebook et TikTok dans `index.html`
- [ ] Relire et ajuster le texte de la section À propos
- [ ] Changer `ADMIN_PASSWORD` avant la mise en ligne définitive

## Technologies utilisées

HTML, CSS, JavaScript (vanilla), PHP, MySQL
