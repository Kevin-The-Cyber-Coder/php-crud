# php-crud

A small, complete CRUD (Create, Read, Update, Delete) example in plain PHP 8 +
PDO, with prepared statements, CSRF protection, server-side validation, search
and pagination. No frameworks, no build step.

Designed to run under XAMPP from `C:\xampp\htdocs\php-crud`.

## Requirements

- PHP 8.0 or newer with `pdo_mysql` (both ship with XAMPP)
- One of:
  - MySQL / MariaDB from XAMPP (default), or
  - SQLite (`pdo_sqlite`), if you just want to run it with no database setup

## Setup with MySQL (default)

1. Start **Apache** and **MySQL** in the XAMPP control panel.
2. Import the schema, either
   - phpMyAdmin → *Import* → choose `sql/schema.mysql.sql` → *Go*, or
   - command line:

     ```bat
     C:\xampp\mysql\bin\mysql.exe -u root < C:\xampp\htdocs\php-crud\sql\schema.mysql.sql
     ```

3. Open <http://localhost/php-crud/>.

The schema creates the `php_crud` database, the `contacts` table and seven
sample rows.

## Setup with SQLite (no database server)

1. In `config.php`, set `DB_DRIVER` to `'sqlite'`.
2. Open <http://localhost/php-crud/>.

The database file `storage/app.sqlite` and its table are created automatically
on the first request. Delete that file to start over.

## Configuration

All settings live in [config.php](config.php):

| Constant    | Purpose                                              |
|-------------|------------------------------------------------------|
| `DB_DRIVER` | `'mysql'` or `'sqlite'`                              |
| `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` | MySQL connection |
| `SQLITE_FILE` | Path of the SQLite database file                   |
| `PER_PAGE`  | Rows per page on the list screen                     |

## Files

| File                    | What it does                                            |
|-------------------------|---------------------------------------------------------|
| `index.php`             | List with search, pagination and delete buttons         |
| `create.php`            | New contact form + insert                               |
| `edit.php`              | Pre-filled form + update                                |
| `delete.php`            | Deletes a contact (POST only)                           |
| `partials/header.php`   | Page head, navigation, flash messages                   |
| `partials/footer.php`   | Page foot                                               |
| `partials/form.php`     | Form shared by create and edit                          |
| `bootstrap.php`         | Session + includes, the single entry point for setup    |
| `config.php`            | Configuration                                           |
| `db.php`                | PDO connection (MySQL or SQLite)                        |
| `functions.php`         | Escaping, URLs, redirects, flash messages, CSRF         |
| `contacts.php`          | Queries, validation and persistence for `contacts`      |
| `assets/style.css`      | Stylesheet                                              |
| `sql/schema.mysql.sql`  | MySQL schema and sample data                            |

## How it works

- **PDO + prepared statements** everywhere, so form input never becomes SQL.
- **Escaping**: every value printed into HTML goes through `e()`
  (`htmlspecialchars`).
- **CSRF**: each form carries a per-session token; `csrf_verify()` rejects a
  mismatched or missing token with HTTP 419.
- **POST + redirect + flash**: successful writes redirect to the list, so a
  refresh cannot resubmit the form.
- **Validation** happens in `contact_validate()`: required name, valid and
  unique email, length limits. Errors are shown next to the field and the
  submitted values are kept.
- **Pagination** uses `LIMIT`/`OFFSET` bound as integers.
