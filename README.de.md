# # React Bridge WP

_Diese README-Datei kann auch auf [Englisch](README.md), [Spanisch](README.es.md) und [Französisch](README.fr.md) gelesen werden._

## Beschreibung

**React Bridge WP** ermöglicht eine einfache Integration von React-Anwendungen in Ihre WordPress-Website. Sie können Ihre React-Anwendung direkt über das WordPress-Admin-Panel laden.

## Installation

1. **Installieren Sie das React-Plugin**

   - Laden Sie das Plugin aus dem Repository herunter. Es handelt sich um die ZIP-Datei namens [react-plugin](https://github.com/pascualmanuel/WP-React-Bridge/blob/main/react-plugin.zip).
   - Laden Sie das Plugin in Ihre WordPress-Installation hoch.
   - Aktivieren Sie das Plugin im WordPress-Admin-Panel. Beim Aktivieren des Plugins wird automatisch ein leeres Theme mit dem Namen **React Bridge Empty Theme** erstellt und aktiviert. Außerdem wird das Plugin eine Beispielseite generieren, die auf der Startseite Ihrer Website angezeigt werden sollte, um zu überprüfen, ob das Plugin ordnungsgemäß funktioniert.

2. **Konfigurieren Sie das React-Projekt**

   - Fügen Sie in der `package.json`-Datei Ihres React-Projekts am Ende der Datei folgende Zeile hinzu:
     ```json
     "homepage": "/wp-content/plugins/react-plugin/build"
     ```
   - Erstellen Sie Ihr React-Projekt mit `npm run build` oder `yarn build`.

3. **Laden Sie das Build in das Plugin hoch**

   - Gehen Sie im WordPress-Admin-Panel zur Einstellungsseite von React Bridge WP.
   - Ziehen Sie die ZIP-Datei mit dem `build`-Ordner Ihres React-Projekts und lassen Sie sie fallen.
   - Dieses Build ersetzt das aktuelle Beispiel-Build und zeigt Ihre React-Anwendung an. Sie können beliebig viele Builds hochladen, wobei das zuletzt hochgeladene die vorherige Version überschreibt.

4. **Fehlerbehebung**

   - Wenn die Seite das Build nicht lädt, überprüfen Sie, ob Ihr Hosting große Datei-Uploads zulässt.
   - Stellen Sie sicher, dass die Struktur des Builds korrekt ist. Das Build muss einen `static`-Ordner enthalten, der die Unterordner `js`, `css` und `media` umfasst. Ein Beispiel für die korrekte Struktur finden Sie im Repository [build-example](https://github.com/pascualmanuel/WP-React-Bridge/tree/main/build-example).

## Hosting-Anforderungen

- **Kapazität für Große Dateiuploads:**

  - Stellen Sie sicher, dass Ihr Hosting das Hochladen großer Dateien unterstützt, da das React-Build erheblich groß sein kann. Überprüfen Sie die Einstellungen `upload_max_filesize` und `post_max_size` in der `php.ini`-Datei.

- **PHP und WordPress:**

  - Das Plugin muss mit der PHP-Version kompatibel sein, die Ihre WordPress-Installation verwendet. Stellen Sie sicher, dass Sie eine aktuelle Version von PHP und WordPress verwenden. Idealerweise PHP 7.4 oder höher und WordPress 5.8 oder höher.

- **Datei- und Ordnerberechtigungen:**

  - Der Server muss das Schreiben in die Verzeichnisse erlauben, in denen die Build-Dateien von React gespeichert werden. Überprüfen Sie, ob die Berechtigungen für die Verzeichnisse `wp-content/plugins/` und `wp-content/themes/` angemessen sind.

- **Server-Ressourcen:**

  - Wenn das React-Build groß ist, sollte Ihr Server über ausreichende Ressourcen (CPU und RAM) verfügen, um die Last zu bewältigen. Shared Hosting kann im Vergleich zu dedizierten Servern oder VPS Einschränkungen aufweisen.

- **Kompatibilität mit Plugins und Themes:**

  - Stellen Sie sicher, dass keine Konflikte mit anderen Plugins oder Themes bestehen, die das Laden von Scripts und Styles beeinträchtigen könnten. Eine Testumgebung ist nützlich, um diese Konfigurationen zu überprüfen.

## Zusätzlicher Hinweis

- **Sicherheit:**

  - Stellen Sie sicher, dass das Hosting über angemessene Sicherheitsmaßnahmen verfügt, um sowohl das Plugin als auch die React-Anwendung zu schützen. Dazu gehören grundlegende Konfigurationen wie Firewalls und Malware-Schutz.

## Verwendung

Sobald Sie Ihre React-Anwendung hochgeladen und aktiviert haben, wird der Inhalt auf der vom Plugin erstellten Seite angezeigt. Es ist nicht erforderlich, das WordPress-Theme zu ändern, da das Plugin das Laden Ihrer React-Anwendung übernimmt.

## Lizenz

Dieses Plugin ist unter der [MIT-Lizenz](link-to-license) verfügbar.
