<?php
declare(strict_types=1);

/**
 * List contacts with optional search and pagination.
 */

require_once __DIR__ . '/bootstrap.php';

$search = trim((string) ($_GET['q'] ?? ''));
$page = max(1, (int) ($_GET['page'] ?? 1));

$total = contacts_count($search);
$pageCount = max(1, (int) ceil($total / PER_PAGE));
$page = min($page, $pageCount);
$contacts = contacts_page($search, $page, PER_PAGE);

$firstRow = $total === 0 ? 0 : (($page - 1) * PER_PAGE) + 1;
$lastRow = min($total, $page * PER_PAGE);

$pageLink = static function (int $target) use ($search): string {
    $query = 'page=' . $target;

    if ($search !== '') {
        $query .= '&q=' . urlencode($search);
    }

    return url('index.php?' . $query);
};

$title = 'Contacts';
require __DIR__ . '/partials/header.php';
?>
<section class="toolbar">
    <form class="search" method="get" action="<?= e(url('index.php')); ?>">
        <input
            type="search"
            name="q"
            value="<?= e($search); ?>"
            placeholder="Search name, email or phone"
            aria-label="Search contacts">
        <button class="button" type="submit">Search</button>
        <?php if ($search !== ''): ?>
            <a class="button button-quiet" href="<?= e(url('index.php')); ?>">Clear</a>
        <?php endif; ?>
    </form>

    <p class="count">
        <?php if ($total === 0): ?>
            No contacts found<?= $search !== '' ? ' for "' . e($search) . '"' : ''; ?>.
        <?php else: ?>
            Showing <?= $firstRow; ?>&ndash;<?= $lastRow; ?> of <?= $total; ?> contact<?= $total === 1 ? '' : 's'; ?>.
        <?php endif; ?>
    </p>
</section>

<div class="card">
    <table>
        <thead>
            <tr>
                <th scope="col" class="col-id">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col" class="col-date">Created</th>
                <th scope="col" class="col-actions">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($contacts === []): ?>
            <tr>
                <td colspan="6" class="empty">
                    Nothing here yet &mdash;
                    <a href="<?= e(url('create.php')); ?>">add your first contact</a>.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td class="col-id"><?= (int) $contact['id']; ?></td>
                    <td>
                        <strong><?= e($contact['name']); ?></strong>
                        <?php if (trim((string) $contact['notes']) !== ''): ?>
                            <span class="notes"><?= e($contact['notes']); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><a href="mailto:<?= e($contact['email']); ?>"><?= e($contact['email']); ?></a></td>
                    <td><?= $contact['phone'] !== '' ? e($contact['phone']) : '&mdash;'; ?></td>
                    <td class="col-date"><?= e(format_datetime($contact['created_at'])); ?></td>
                    <td class="col-actions">
                        <a class="button button-small" href="<?= e(url('edit.php?id=' . (int) $contact['id'])); ?>">Edit</a>
                        <form class="inline" method="post" action="<?= e(url('delete.php')); ?>"
                              onsubmit="return confirm('Delete <?= e($contact['name']); ?>?');">
                            <input type="hidden" name="id" value="<?= (int) $contact['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                            <button class="button button-small button-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($pageCount > 1): ?>
    <nav class="pagination" aria-label="Pagination">
        <?php if ($page > 1): ?>
            <a class="button" href="<?= e($pageLink($page - 1)); ?>" rel="prev">&larr; Previous</a>
        <?php endif; ?>

        <span class="page-indicator">Page <?= $page; ?> of <?= $pageCount; ?></span>

        <?php if ($page < $pageCount): ?>
            <a class="button" href="<?= e($pageLink($page + 1)); ?>" rel="next">Next &rarr;</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
