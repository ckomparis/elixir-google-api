# Package CKOM Balise GPT

## Important
La plateforme Git ne prend pas en charge les binaires versionnés.
Le ZIP installable Joomla 6 doit être **généré localement** avec le script de build.

## Si vous n'arrivez pas à télécharger
Le package est généré localement (les binaires ZIP ne sont pas versionnés dans le repo).

Après build, vous trouverez:

- `release/pkg_ckom_gpttags-0.2.1.zip`
- checksum: `release/pkg_ckom_gpttags-0.2.1.zip.sha256`

Pour le générer localement:

```bash
bash scripts/build_ckom_package.sh
```

## Installation
1. Dans Joomla 6, aller dans **System > Install > Extensions**.
2. Importer `pkg_ckom_gpttags-0.2.1.zip`.
3. Activer le plugin **System - SensoGPT**.
4. Configurer:
   - Start tag: `{gpt}`
   - End tag: `{/gpt}`

## Test rapide
Dans un contenu rendu en frontend:

```html
{gpt}Bonjour tout le monde{/gpt}
<img src="/demo.jpg" alt="{gpt}chat noir{/gpt}">
```

Avec `target_language=en`, la sortie prototype devient:
- `[en] Bonjour tout le monde`
- `alt="[en] chat noir"`
