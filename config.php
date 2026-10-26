<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application configuration
|--------------------------------------------------------------------------
| For XAMPP the defaults below work out of the box: user "root" with an
| empty password on 127.0.0.1:3306.
*/

// 'mysql'  -> MySQL/MariaDB from XAMPP. Import sql/schema.mysql.sql first.
// 'sqlite' -> zero-setup file database, created automatically in storage/.
define('DB_DRIVER', 'sqlite');

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'php_crud');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SQLITE_FILE', __DIR__ . '/storage/app.sqlite');

define('APP_NAME', 'Simple PHP CRUD');

// Rows per page on the list screen.
define('PER_PAGE', 5);
