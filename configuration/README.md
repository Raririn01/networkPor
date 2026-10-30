# Configuration files

These are sanitized copies of the Local development configuration templates from `conf/`.

- `local/nginx/`: web server, routing, MIME types, compression and access restrictions.
- `local/mysql/`: MySQL settings. Replace the password placeholder with your own local credential.
- `local/php/`, `local/php-8.2.29/`, `local/php-8.2.35/`: PHP and PHP-FPM templates present in the project. Their presence does not establish which version is currently active.

Local renders the `{{...}}` Handlebars placeholders. These are not standalone configuration files; use them in the appropriate Local environment. Development settings such as error display and upload limits need review before any production use.

This directory is not an export of the live Docker/Apache server configuration. Production credentials, WordPress salts, private keys and database contents are excluded.
