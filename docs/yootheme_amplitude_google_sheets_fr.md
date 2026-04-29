# Méthode : Carte mondiale dynamique (YOOtheme Pro Map + YOOessential) depuis Google Sheets (Amplitude)

Ce guide explique comment alimenter **un élément Map YOOtheme Pro** via **YOOessential** avec comme source votre Google Sheet, afin d’afficher :

- les pays où se trouvent les clients,
- le nombre de clients par pays,
- une représentation visuelle proportionnelle au volume (taille/couleur des points).

> Source fournie : https://docs.google.com/spreadsheets/d/18OLBetiD-_hDtXKgiZEER8YbTcfN64r-FzWe7CkzMFE/edit?usp=sharing

---

## 1) Préparer le Google Sheet pour une consommation dynamique

Pour que YOOessential puisse lire le tableau en externe, publiez la feuille :

1. Ouvrir le Google Sheet.
2. **Fichier → Partager → Publier sur le Web**.
3. Publier l’onglet contenant les données clients.
4. Récupérer l’URL CSV de l’onglet (format type) :

```text
https://docs.google.com/spreadsheets/d/SPREADSHEET_ID/export?format=csv&gid=ONGLET_GID
```

### Structure recommandée des colonnes

Créez (ou vérifiez) un onglet agrégé avec une ligne par pays, par exemple :

- `country` : nom du pays (ex: France, Canada, Brazil)
- `country_code` : code ISO2 (ex: FR, CA, BR) — recommandé
- `clients` : nombre de clients
- `lat` : latitude (optionnel mais recommandé)
- `lng` : longitude (optionnel mais recommandé)

Exemple :

```csv
country,country_code,clients,lat,lng
France,FR,125,46.2276,2.2137
Canada,CA,98,56.1304,-106.3468
Brazil,BR,76,-14.2350,-51.9253
```

---

## 2) Configurer YOOessential (source externe CSV)

Dans Joomla/WordPress avec YOOtheme Pro + YOOessential :

1. Ouvrir **YOOessential → Sources** (ou Dynamic Sources selon votre version).
2. Ajouter une nouvelle source de type **CSV/URL**.
3. Coller l’URL CSV publiée de Google Sheets.
4. Définir la fréquence de rafraîchissement/caching (ex: 5 à 30 min).
5. Vérifier que les champs sont correctement détectés : `country`, `clients`, `lat`, `lng`, etc.

### Bonnes pratiques de robustesse

- Utiliser des nombres purs pour `clients` (pas de séparateur espace/texte).
- Éviter les accents/variantes dans les noms de pays si vous ne fournissez pas lat/lng.
- Préférer `lat/lng` pour éviter les erreurs de géocodage.

---

## 3) Créer la carte dans YOOtheme Pro (Map)

1. Ouvrir le Builder et ajouter un élément **Map**.
2. Activer le **contenu dynamique** via YOOessential sur la collection CSV.
3. Mapper les champs :
   - Position : `lat` + `lng` (ou `country` si géocodage auto disponible)
   - Titre du marker : `country`
   - Méta/description : `clients` (ex: `{{country}} : {{clients}} clients`)

---

## 4) Représentation graphique selon le nombre de clients

Pour rendre la carte lisible par volume :

### Option A — Taille des marqueurs (recommandée)

- Créer un champ calculé `marker_size` via une règle simple :
  - 1–25 clients → taille 12
  - 26–100 → taille 18
  - 101–300 → taille 24
  - 300+ → taille 30
- Mapper `marker_size` au style du marker (ou à des classes CSS si votre setup l’exige).

### Option B — Couleurs par classes

- Créer un champ `segment` (small/medium/large/xl).
- Appliquer une couleur par segment (ex: vert, jaune, orange, rouge).

### Option C — Heatmap (si disponible via extension/JS)

- Utiliser `clients` comme poids de densité.
- Conserver les markers pour le détail au survol.

---

## 5) Dynamique en continu (Amplitude → Sheet → Site)

Flux cible :

1. Amplitude met à jour les exports (manuel ou automatisé).
2. Le Google Sheet se met à jour.
3. YOOessential relit le CSV selon cache TTL.
4. La carte YOOtheme affiche automatiquement la nouvelle répartition.

---

## 6) Contrôle qualité avant mise en production

Checklist :

- [ ] L’URL CSV est publique et renvoie bien des données.
- [ ] Les colonnes attendues existent (`country`, `clients`, `lat`, `lng`).
- [ ] Les valeurs `clients` sont numériques.
- [ ] Les pays sans coordonnées sont traités (fallback).
- [ ] Le cache YOOtheme/YOOessential est purgé après réglages.
- [ ] Affichage responsive validé mobile/tablette.

---

## 7) Exemple de logique de transformation (si nécessaire)

Si votre feuille brute contient plusieurs lignes par pays, créez un onglet "Map_Data" :

- Colonne A : liste des pays uniques.
- Colonne B : somme des clients par pays (ex. `SUMIF`/tableau croisé).
- Colonnes C-D : lat/lng de référence par pays.

Ainsi, la Map consomme une table propre "1 pays = 1 point".

---

## 8) Remarques sur votre lien actuel

Le lien fourni est en mode `edit`. Pour un binding dynamique externe, utilisez la version **publiée en CSV** (`export?format=csv&gid=...`).

