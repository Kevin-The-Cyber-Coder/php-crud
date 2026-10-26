<?php
declare(strict_types=1);

/**
 * Edit a contact: load it (GET), then validate and update (POST).
 */

require_once __DIR__ . '/bootstrap.php';

$id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
$contact = $id > 0 ? contact_find($id) : null;

if ($contact === null) {
    flash('error', 'That contact no longer exists.');
    redirect('index.php');
}

$data = [];

foreach (CONTACT_FIELDS as $field) {
    $data[$field] = (string) ($contact[$field] ?? '');
}

$errors = [];

if (is_post()) {
    csrf_verify();

    [$data, $errors] = contact_validate($_POST, $id);

    if ($errors === []) {
        contact_save($id, $data);
        flash('success', 'Contact updated.');
        redirect('index.php');
    }
}

$title = 'Edit contact';
$formTitle = 'Edit contact #' . $id;
$submitLabel = 'Save changes';
$action = url('edit.php?id=' . $id);
$cancelUrl = url('index.php');

require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/form.php';
require __DIR__ . '/partials/footer.php';
