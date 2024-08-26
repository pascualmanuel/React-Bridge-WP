=== React Integration Bridge ===
Contributors: pascualmanuel
Tags: react, integration, react bridge, react plugin
Requires at least: 5.8
Tested up to: 6.3
Requires PHP: 7.4
Stable tag: 1.0.0
License: MIT
License URI: https://opensource.org/licenses/MIT

React Integration Bridge allows you to easily integrate React applications into your WordPress site. You can load your React application directly from the WordPress admin panel.

== Description ==

**React Integration Bridge** provides a seamless way to integrate React applications into WordPress. By using this plugin, you can upload your React builds and have them automatically displayed within your WordPress site. The plugin manages the integration process, enabling you to focus on your React application development without worrying about compatibility issues.

== Installation ==

1. **Install the Plugin**
   - Download the plugin from the repository.
   - Upload the plugin to your WordPress installation via the admin panel.
   - Activate the plugin. Upon activation, a blank theme called "React Bridge Empty Theme" will be created and activated automatically.

2. **Configure Your React Project**
   - Add the following line in the `package.json` file of your React project:
     ```json
     "homepage": "/wp-content/plugins/react-bridge/build"
     ```
   - Build your React project using `npm run build` or `yarn build`.

3. **Upload the React Build**
   - Navigate to the React Bridge WP settings page in the WordPress admin panel.
   - Upload the ZIP file containing the `build` folder of your React project.
   - The plugin will replace the current build with the uploaded one, allowing you to see your React application on your WordPress site.

== Frequently Asked Questions ==

= How do I use this plugin with my React application? =
Upload your React build ZIP file via the plugin settings page in the WordPress admin panel. The plugin will handle the integration, displaying your React application within your WordPress site.

= What should I do if the build doesn’t load? =
Ensure that your hosting environment allows large file uploads and that your build structure is correct. The `build` folder should contain a `static` folder with `js`, `css`, and `media` subfolders.

= What are the hosting requirements? =
Your hosting must support large file uploads, have PHP 7.4 or higher, and WordPress 5.8 or higher. Ensure that file permissions for the `wp-content/plugins/` and `wp-content/themes/` directories are appropriate.

= What happens if I upload more than one build? =
If you upload multiple builds, the latest uploaded build will always overwrite the previous one. Only one build is stored and used at any given time.

= Can I use this plugin with other themes? =
No, the plugin is designed to work with React Bridge Empty Theme.

= How can I deactivate the blank template? =
To deactivate the blank template, simply deactivate the React Integration Bridge plugin. To use another template, activate a different WordPress theme.


== Changelog ==

= 1.0.0 =
* Initial release of React Integration Bridge.

== Author ==
Labba Studio - Manuel  
[https://www.labba.studio/](https://www.labba.studio/)

== Donate ==
If you find this plugin useful and would like to support further development, please consider making a donation. [Donate here](https://www.labba.studio/donate).

== License ==
This plugin is licensed under the MIT License. See the `LICENSE` file for more information.
