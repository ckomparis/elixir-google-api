# Plugin Joomla 6 `sensogpt` pour YOOtheme Pro

## Objectif
Créer un plugin Joomla 6 capable de traduire en temps réel le rendu des contenus YOOtheme Pro en utilisant des balises configurables :

- **Début**: `{gpt}`
- **Fin**: `{/gpt}`

Le plugin doit aussi traiter les textes dynamiques et les attributs `alt` des images pour améliorer le SEO.

## Portée fonctionnelle

### 1) Détection des segments à traduire
- Supporter des balises **configurables** en paramètres du plugin :
  - `start_tag` (défaut: `{gpt}`)
  - `end_tag` (défaut: `{/gpt}`)
- Détecter les segments dans:
  - le contenu statique,
  - les champs dynamiques,
  - les structures imbriquées (ex: items d'accordéon, sliders, listes).

### 2) Intégration YOOtheme Pro
Le traitement doit couvrir les éléments listés par besoin :

#### Basic
Alert, Code, Countdown, Divider, Headline, Html, Icon, Image, Newsletter, Overlay, Panel, Quotation, Sublayout, Text, To Top, Video.

#### Multiple items
Accordion, Button, Description List, Gallery, Grid, List, Map, Nav, Overlay Slider, Panel Slider, Popover, Slideshow, Social, Subnav, Switcher, Table.

#### System
Breadcrumbs, Menu, Module, Module Position, Pagination, Search, Search Ordering.

### 3) Images et SEO
- Traduire/adapter le texte présent dans `alt` des images quand encadré par les balises.
- Option de sécurité pour **ne pas** modifier les URLs, `src`, `srcset`, dimensions.

### 4) Pipeline de traduction temps réel
- Hook de rendu Joomla/YOOtheme pour intercepter le HTML final ou les structures avant rendu.
- Normalisation des segments (trim, unicode, nettoyage des entités HTML).
- Appel au moteur de traduction (service GPT) avec :
  - langue source auto (ou fixe),
  - langue cible configurable,
  - ton/style configurable.
- Remplacement dans la sortie avec conservation stricte du markup non ciblé.

### 5) Performance
- Cache applicatif (clé = hash du segment + langue + contexte).
- TTL configurable.
- Déduplication des requêtes dans une même page.
- Timeout et stratégie fallback (afficher original si erreur).

### 6) Sécurité
- Nettoyage des entrées.
- Journalisation sans exposer de données sensibles.
- Protection contre les boucles de re-traitement des balises.
- Respect des ACL Joomla pour configuration.

## Architecture proposée

- **Type**: Plugin Joomla 6 (groupe recommandé: `system` ou `content` selon le point d’accroche retenu).
- **Fichiers principaux**:
  - `plugins/system/sensogpt/sensogpt.php` (bootstrap + hooks)
  - `plugins/system/sensogpt/sensogpt.xml` (manifest + params)
  - `plugins/system/sensogpt/src/Service/TranslatorService.php`
  - `plugins/system/sensogpt/src/Service/TagParser.php`
  - `plugins/system/sensogpt/src/Service/YoothemeElementWalker.php`
  - `plugins/system/sensogpt/language/*`

## Paramètres de configuration

- `start_tag` (string, défaut `{gpt}`)
- `end_tag` (string, défaut `{/gpt}`)
- `target_language` (ex: `fr-FR`, `en-GB`)
- `source_language` (`auto` par défaut)
- `translate_alt_attributes` (bool)
- `cache_enabled` (bool)
- `cache_ttl_seconds` (int)
- `api_timeout_ms` (int)
- `debug_logging` (bool)

## Règles de traitement

1. Lire le rendu/structure.
2. Extraire uniquement les portions entre balises.
3. Ignorer contenu interdit (scripts/styles).
4. Traduire en lot si possible.
5. Réinjecter aux positions exactes.
6. Supprimer ou conserver balises selon option (`strip_tags_after_translation`).

## Cas limites à couvrir

- Balises incomplètes (`{gpt}` sans fermeture).
- Balises imbriquées.
- HTML invalide dans segment.
- Champs dynamiques vides.
- Pages multilingues (éviter double traduction).

## Plan d’implémentation (MVP)

1. Parser balises configurable.
2. Hook de rendu global Joomla.
3. Traduction texte brut + maintien HTML.
4. Cache mémoire/disque.
5. Support `alt` images.
6. Journalisation + écran de configuration.

## Tests recommandés

- Tests unitaires parser balises.
- Tests d’intégration sur pages YOOtheme (au moins 1 page par famille d’éléments).
- Tests de charge (nombre d’éléments élevé).
- Tests SEO sur balises `alt`.

## Références utiles

- UIkit docs (inspiration composants): https://getuikit.com/docs/introduction
- YOOtheme Pro Joomla intro: https://yootheme.com/support/yootheme-pro/joomla/introduction
