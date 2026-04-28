# Générer directement votre Google Sheet (Zoo Essential + YOOtheme Pro)

Vous m'avez demandé de **générer le Google Sheet** : le fichier `docs/create_google_sheet.gs` le fait automatiquement.

## Méthode rapide (automatique)
1. Ouvrez [https://script.google.com](https://script.google.com).
2. Créez un nouveau projet Apps Script.
3. Copiez/collez le contenu de `docs/create_google_sheet.gs`.
4. Exécutez une première fois `onOpen()` puis rafraîchissez Google Sheets.
5. Dans le menu **Catalogue Europe**, cliquez **Créer ma sheet**.
6. Autorisez les permissions Google si demandé.
7. Ouvrez l'URL du fichier créée dans les logs (`Logger.log`).

Le script crée :
- un onglet `data_raw` avec toutes vos données source,
- un onglet `data_web` avec `slug`, `region`, `pays`, `type`, `nom`, `title`, `description`,
- les filtres et formules pour un usage dynamique.

## Fichiers fournis
- `docs/catalogue_compositeurs_editeurs.csv` : source brute.
- `docs/data_web.csv` : export web déjà calculé.
- `docs/create_google_sheet.gs` : script qui génère la Google Sheet automatiquement.

## Mapping conseillé dans Zoo Essential
- `title` -> Titre de l'item
- `nom` -> Nom affiché
- `type` -> Taxonomie Type
- `pays` -> Taxonomie Pays
- `region` -> Taxonomie Région
- `description` -> Description
- `slug` -> Alias / URL key
