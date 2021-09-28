# AmoCRM PHP

https://nova-amocrm.notion.site/web-PHP-7437bf8a765440ba854963a70a9b1054


## Usage
### Configuration
0. Before running project make sure to configure `config.php` file
    ```bash
    LOGS_PATH # Logging file path

    AMOCRM_SUBDOMAIN # AmoCRM subdomain (https://<subdomain>.amocrm.ru)

    REDIRECT_URI # Widget redirect URI (Must point on the auth.php file)

    CLIENT_ID # Widget Client ID. Integration ID may be used for developing purposes or just use one from the authentication.json (see below)
    CLIENT_SECRET # Widget Client Secert

    # DO NOT try to configure constants below right away. Authorization will be mantioned later
    AUTHENTICATION_CODE # User authentication code
    ACCESS_TOKEN # API access token
    REFRESH_TOKEN # API refresh token
    ```

1. Configure your widget's redirect URI so it hits the `auth.php`. Enable (or re-enable if already enabled) your widget, it'll hit the `auth.php` on your webserver and `authentication.json` file will appear in the project root folder.
1. Configure `AUTHENTICATION_CODE`, `ACCESS_TOKEN`, `REFRESH_TOKEN` and `CLIENT_ID` (if haven't done yet) constants using values from the `authentication.json` file.
2. Aaaand that's it, hit `index.php` freely and see how everything's working.

### Generating `autoload.php`
1. Install [Composer](https://getcomposer.org)
2. Navigate to the project folder
3. Execute the following command:
    ```bash
    composer dump-autoload
    ```
4. New `autoload.php` file will appear in the `vendor` folder


## Project structure
```bash
|- AmoCRM/ # AmoCRM interaction library
|- vendor/ # Composer vendor files
|- .htaccess # Apache configuration
|- compose.json # Composer configuration
|- config.php # Project configuration
|- auth.php # Authentication script for generating tokens
|- index.php # Main file, used for the task
|- loggin.php # Nothing much, just useful logging functions
```