-- Simple PHP CRUD - MySQL/MariaDB schema
-- Import this file once in phpMyAdmin (or the mysql CLI).
-- Re-importing drops the table and re-seeds the sample rows.

CREATE DATABASE IF NOT EXISTS php_crud
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE php_crud;

DROP TABLE IF EXISTS contacts;

CREATE TABLE contacts (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(190) NOT NULL,
    phone      VARCHAR(30)  NOT NULL DEFAULT '',
    notes      TEXT         NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY contacts_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO contacts (name, email, phone, notes) VALUES
    ('Ada Lovelace',   'ada@example.com',   '+44 20 7946 0101', 'Wrote the first algorithm intended for a machine.'),
    ('Grace Hopper',   'grace@example.com', '+1 202 555 0142',  'Coined "debugging" after a moth in a relay.'),
    ('Alan Turing',    'alan@example.com',  '+44 20 7946 0202', 'Formalised computation and algorithms.'),
    ('Katherine Johnson', 'katherine@example.com', '+1 202 555 0177', 'Calculated trajectories for early space flights.'),
    ('Linus Torvalds', 'linus@example.com', '+358 9 1234567',   'Started the Linux kernel in 1991.'),
    ('Margaret Hamilton', 'margaret@example.com', '+1 617 555 0199', 'Led the Apollo flight software team.'),
    ('Edsger Dijkstra', 'edsger@example.com', '+31 20 123 4567', 'Shortest-path algorithm and structured programming.');
