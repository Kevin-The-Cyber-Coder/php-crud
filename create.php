<?php
declare(strict_types=1);

/**
 * Create a contact: show the empty form (GET) or validate and insert (POST).
 */

require_once __DIR__ . '/bootstrap.php';

$data = ['name' => '', 'email' => '', 'phone' => '', 'notes' => ''];
$errors = [];

if (is_post()) {
    csrf_verify();

    [$data, $errors] = contact_validate($_POST);

    if ($errors === []) {
        contact_save(null, $data);
        flash('success', 'Contact created.');
        redirect('index.php');
    }
}

$title = 'New contact';
$formTitle = 'New contact';
$submitLabel = 'Save contact';
$action = url('create.php');
$cancelUrl = url('index.php');

require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/form.php';
require __DIR__ . '/partials/footer.php';
