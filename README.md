# WP-React-Bridge

_This README can also be read in [Spanish](README.es.md), [German](README.de.md), and [French](README.fr.md)._

## Description

**WP-React-Bridge** allows you to easily integrate React applications into your WordPress site. You can load your React application directly from the WordPress admin panel.

## Installation

1. **Install the React Plugin**

   - Download the plugin from the repository. It is the zip file named [react-plugin](https://github.com/pascualmanuel/WP-React-Bridge/blob/main/react-plugin.zip).
   - Upload the plugin to your WordPress installation.
   - Activate the plugin from the WordPress admin panel. Upon activation, the plugin will automatically create and activate a blank theme called **WP React Bridge Empty Theme**. Additionally, the plugin will generate a sample page that should appear on the homepage of your site to verify that the plugin is functioning correctly.

2. **Configure the React Project**

   - In the `package.json` file of your React project, add the following line at the end of the file:
     ```json
     "homepage": "/wp-content/plugins/react-plugin/build"
     ```
   - Build your React project by running `npm run build` or `yarn build`.

3. **Upload the Build to the Plugin**

   - In the WordPress admin panel, go to the WP-React-Bridge settings page.
   - Drag and drop the ZIP file containing the `build` folder of your React project.
   - This build will replace the current example build, displaying your React application. You can upload as many builds as necessary, and the most recent upload will overwrite the previous one.

4. **Troubleshooting**

   - If the site does not load the build, check that your hosting allows for large file uploads.
   - Ensure that the build structure is correct. The build must contain a `static` folder with subfolders `js`, `css`, and `media`. You can see an example of the correct structure in the [build-example](https://github.com/pascualmanuel/WP-React-Bridge/tree/main/build-example) repository.

## Hosting Requirements

- **Large File Upload Capacity:**

  - Ensure that your hosting supports the upload of large files, as the React build may be significant in size. Check the `upload_max_filesize` and `post_max_size` settings in the `php.ini` file.

- **PHP and WordPress:**

  - The plugin must be compatible with the PHP version used by your WordPress installation. Ensure you are using an up-to-date version of PHP and WordPress. Ideally, PHP 7.4 or higher and WordPress 5.8 or higher.

- **File and Folder Permissions:**

  - The server must allow writing to the directories where the React build files are stored. Verify that permissions for the `wp-content/plugins/` and `wp-content/themes/` directories are appropriate.

- **Server Resources:**

  - If the React build is large, your server should have sufficient resources (CPU and RAM) to handle the load. Shared hosting might have limitations compared to dedicated servers or VPS.

- **Compatibility with Plugins and Themes:**

  - Ensure that there are no conflicts with other plugins or themes that might interfere with loading scripts and styles. A test environment is useful for validating these configurations.

## Additional Note

- **Security:**

  - Ensure that the hosting has adequate security measures to protect both the plugin and the React application. This includes basic configurations such as firewalls and malware protection.

## Usage

Once you have uploaded and activated your React application, the content will be displayed on the page created by the plugin. You do not need to modify the WordPress theme, as the plugin will handle loading your React application.

## License

This plugin is available under the [MIT License](link-to-license).
