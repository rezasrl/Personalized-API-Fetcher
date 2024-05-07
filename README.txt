Hello Saucal Team,

I've completed the test task as per the provided description. The objective was to develop a plugin capable of fetching data from https://httpbin.org/post based on the headers selected by the user in the "My Account" page and the "User Preferences" tab.

For instance, if a user sets a date in their preferences, they should see that date reflected in the Personalized API Fetcher section of the "My Account" page and under the "User Preferences" tab. Input data should be separated by commas.

Key points about the implementation:

1. **Boilerplate Usage**: Utilized the WordPress Plugin Boilerplate from https://github.com/saucal/WordPress-Plugin-Boilerplate.

2. **API Credentials**: Although the plugin provides an option in Settings -> General to add API credentials, we didn't utilize this feature as httpbin.org doesn't require API credentials.

3. **Namespace and Database Prefix**: Employed namespaces for function names, avoiding the need for a prefix for function names. However, used the "paf" prefix for some database-related data.

4. **Workflow**: Adhered to the Gitflow workflow. To get started, clone the `develop` branch, then run `composer install` and `npm install` to install the required modules.

5. **Gulp Integration**: Added a `gulpfile` for compiling CSS and JS files. Run `gulp compileAdminStyles`, `gulp compileFrontendStyles`, and `gulp processJS` to generate the necessary files.

Functionality Overview:

- **API Credentials**: Displayed in Settings -> General: (https://prnt.sc/PDxsbM6-sHz6).
- **User Preferences**: Users can set header names separated by commas in the "My Account" page: (https://prnt.sc/C8gF3ePP5jVv).
- **Widget Support**: Administrators can add the Personalized API Fetcher Widget in the widgets page: (https://prnt.sc/uiSEzFJ26sva).
- **Sample Output**: The final output resembles this: [Screenshot](https://prnt.sc/HHONBVbIVS0z).

This summary provides an overview of the implementation and functionality achieved in completing the test task.

Please let me know if any further clarification is needed.

Best regards,
Reza
