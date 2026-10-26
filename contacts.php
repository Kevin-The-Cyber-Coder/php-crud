<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

/**
 * All editable columns, in display order.
 */
const CONTACT_FIELDS = ['name', 'email', 'phone', 'notes'];

const CONTACT_MAX_LENGTHS = [
    'name'  => 100,
    'email' => 190,
    'phone' => 30,
    'notes' => 2000,
];

/** WHERE clause and bound values shared by the count and page queries. */
function contact_search_filter(string $search): array
{
    if ($search === '') {
        return ['', []];
    }

    $term = '%' . $search . '%';

    return [
        'WHERE name LIKE :q_name OR email LIKE :q_email OR phone LIKE :q_phone',
        [':q_name' => $term, ':q_email' => $term, ':q_phone' => $term],
    ];
}

function contacts_count(string $search = ''): int
{
    [$where, $params] = contact_search_filter($search);

    $statement = db()->prepare('SELECT COUNT(*) FROM contacts ' . $where);
    $statement->execute($params);

    return (int) $statement->fetchColumn();
}

/** One page of contacts, newest first. */
function contacts_page(string $search, int $page, int $perPage): array
{
    [$where, $params] = contact_search_filter($search);

    $statement = db()->prepare(
        'SELECT * FROM contacts ' . $where . ' ORDER BY id DESC LIMIT :limit OFFSET :offset'
    );

    foreach ($params as $name => $value) {
        $statement->bindValue($name, $value, PDO::PARAM_STR);
    }

    $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $statement->bindValue(':offset', max(0, ($page - 1) * $perPage), PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchAll();
}

function contact_find(int $id): ?array
{
    $statement = db()->prepare('SELECT * FROM contacts WHERE id = ?');
    $statement->execute([$id]);
    $contact = $statement->fetch();

    return is_array($contact) ? $contact : null;
}

/**
 * Trim and validate the submitted form data.
 *
 * @return array{0: array<string, string>, 1: array<string, string>}
 */
function contact_validate(array $input, ?int $ignoreId = null): array
{
    $data = [];

    foreach (CONTACT_FIELDS as $field) {
        $data[$field] = trim((string) ($input[$field] ?? ''));
    }

    $errors = [];

    if (text_length($data['name']) === 0) {
        $errors['name'] = 'Name is required.';
    } elseif (text_length($data['name']) > CONTACT_MAX_LENGTHS['name']) {
        $errors['name'] = 'Name may not be longer than ' . CONTACT_MAX_LENGTHS['name'] . ' characters.';
    }

    if (text_length($data['email']) === 0) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email must be a valid address, like jane@example.com.';
    } elseif (text_length($data['email']) > CONTACT_MAX_LENGTHS['email']) {
        $errors['email'] = 'Email may not be longer than ' . CONTACT_MAX_LENGTHS['email'] . ' characters.';
    } else {
        $sql = 'SELECT id FROM contacts WHERE email = ?';
        $params = [$data['email']];

        if ($ignoreId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }

        $statement = db()->prepare($sql . ' LIMIT 1');
        $statement->execute($params);

        if ($statement->fetchColumn() !== false) {
            $errors['email'] = 'Another contact already uses this email address.';
        }
    }

    if (text_length($data['phone']) > CONTACT_MAX_LENGTHS['phone']) {
        $errors['phone'] = 'Phone may not be longer than ' . CONTACT_MAX_LENGTHS['phone'] . ' characters.';
    }

    if (text_length($data['notes']) > CONTACT_MAX_LENGTHS['notes']) {
        $errors['notes'] = 'Notes may not be longer than ' . CONTACT_MAX_LENGTHS['notes'] . ' characters.';
    }

    return [$data, $errors];
}

/** Insert a new contact or update an existing one. Returns the contact id. */
function contact_save(?int $id, array $data): int
{
    if ($id === null) {
        $statement = db()->prepare(
            'INSERT INTO contacts (name, email, phone, notes) VALUES (?, ?, ?, ?)'
        );
        $statement->execute([$data['name'], $data['email'], $data['phone'], $data['notes']]);

        return (int) db()->lastInsertId();
    }

    $statement = db()->prepare(
        'UPDATE contacts SET name = ?, email = ?, phone = ?, notes = ? WHERE id = ?'
    );
    $statement->execute([$data['name'], $data['email'], $data['phone'], $data['notes'], $id]);

    return $id;
}

/** Delete one contact. Returns true when a row was removed. */
function contact_delete(int $id): bool
{
    $statement = db()->prepare('DELETE FROM contacts WHERE id = ?');
    $statement->execute([$id]);

    return $statement->rowCount() > 0;
}
