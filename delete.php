<?php
declare(strict_types=1);

/**
 * Delete a contact. POST only, so a link or prefetch can never remove a row.
 */

require_once __DIR__ . '/bootstrap.php';

require_post();
csrf_verify();

$id = (int) ($_POST['id'] ?? 0);

if ($id > 0 && contact_delete($id)) {
    flash('success', 'Contact deleted.');
} else {
    flash('error', 'That contact no longer exists.');
}

redirect('index.php');
