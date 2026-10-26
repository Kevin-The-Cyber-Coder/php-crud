<?php
/**
 * Shared page head. Expects $title (string).
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? APP_NAME); ?> &middot; <?= e(APP_NAME); ?></title>
    <link rel="stylesheet" href="<?= e(url('assets/style.css')); ?>">
</head>
<body>
<header class="site-header">
    <div class="wrap">
        <a class="brand" href="<?= e(url('index.php')); ?>"><?= e(APP_NAME); ?></a>
        <a class="button button-primary" href="<?= e(url('create.php')); ?>">+ New contact</a>
    </div>
</header>

<main class="wrap">
<?php $flash = flash(); ?>
<?php if ($flash !== null): ?>
    <div class="alert alert-<?= e($flash['type']); ?>" role="status">
        <?= e($flash['message']); ?>
    </div>
<?php endif; ?>
