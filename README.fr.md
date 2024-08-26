# WP-React-Bridge

_Ce fichier README peut également être lu en [espagnol](README.es.md), [allemand](README.de.md) et [anglais](README.md)._

## Description

Le **WP-React-Bridge** permet d'intégrer facilement des applications React sur votre site WordPress. Vous pouvez charger votre application React directement depuis le panneau d'administration de WordPress.

## Installation

1. **Installer le Plugin React**

   - Téléchargez le plugin depuis le dépôt. Il s'agit du fichier zip nommé [react-plugin](https://github.com/pascualmanuel/WP-React-Bridge/blob/main/react-plugin.zip).
   - Téléversez le plugin sur votre installation WordPress.
   - Activez le plugin depuis le panneau d'administration de WordPress. Lors de l'activation du plugin, un thème vide nommé **WP React Bridge Empty Theme** sera automatiquement créé et activé. De plus, le plugin générera une page exemple qui devrait s'afficher sur la page d'accueil du site pour vérifier que le plugin fonctionne correctement.

2. **Configurer le Projet React**

   - Dans le fichier `package.json` de votre projet React, ajoutez la ligne suivante à la fin du fichier :
     ```json
     "homepage": "/wp-content/plugins/react-plugin/build"
     ```
   - Construisez votre projet React en exécutant `npm run build` ou `yarn build`.

3. **Téléversez le Build sur le Plugin**

   - Dans le panneau d'administration de WordPress, allez à la page de configuration du WP-React-Bridge.
   - Glissez et déposez le fichier ZIP contenant le dossier `build` de votre projet React.
   - Ce build remplacera le build exemple actuel, affichant ainsi votre application React. Vous pouvez téléverser autant de builds que nécessaire, et le dernier téléversé écrasera le précédent.

4. **Dépannage**

   - Si le site ne charge pas le build, vérifiez que votre hébergement permet le téléversement de fichiers volumineux.
   - Assurez-vous que la structure du build est correcte. Le build doit contenir un dossier `static` incluant les sous-dossiers `js`, `css`, et `media`. Vous pouvez voir un exemple de la structure correcte dans le dépôt [build-example](https://github.com/pascualmanuel/WP-React-Bridge/tree/main/build-example).

## Exigences d'Hébergement

- **Capacité de Téléversement de Fichiers Volumineux:**

  - Assurez-vous que votre hébergement permet le téléversement de fichiers volumineux, car le build React peut être assez conséquent. Vérifiez les paramètres `upload_max_filesize` et `post_max_size` dans le fichier `php.ini`.

- **PHP et WordPress:**

  - Le plugin doit être compatible avec la version de PHP utilisée par votre installation WordPress. Assurez-vous d'utiliser une version à jour de PHP et WordPress. Idéalement, PHP 7.4 ou supérieur et WordPress 5.8 ou supérieur.

- **Permissions de Fichiers et Dossiers:**

  - Le serveur doit permettre l'écriture dans les répertoires où sont stockés les fichiers du build React. Vérifiez que les permissions pour les répertoires `wp-content/plugins/` et `wp-content/themes/` sont adéquates.

- **Ressources du Serveur:**

  - Si le build React est volumineux, votre serveur doit avoir suffisamment de ressources (CPU et RAM) pour gérer la charge. Les serveurs partagés peuvent avoir des limitations par rapport aux serveurs dédiés ou VPS.

- **Compatibilité avec les Plugins et Thèmes:**

  - Assurez-vous qu'il n'y a pas de conflits avec d'autres plugins ou thèmes qui pourraient interférer avec le chargement des scripts et des styles. Un environnement de test est utile pour valider ces configurations.

## Note Supplémentaire

- **Sécurité:**

  - Assurez-vous que l'hébergement dispose de mesures de sécurité adéquates pour protéger à la fois le plugin et l'application React. Cela inclut des configurations de base telles que des pare-feux et une protection contre les logiciels malveillants.

## Utilisation

Une fois que vous avez téléversé et activé votre application React, le contenu sera affiché sur la page créée par le plugin. Vous n'avez pas besoin de modifier le thème WordPress, car le plugin se chargera du chargement de votre application React.

## Licence

Ce plugin est disponible sous la [Licence MIT](link-to-license).
