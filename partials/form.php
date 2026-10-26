<?php
/**
 * Contact form shared by create.php and edit.php.
 *
 * Expects: $data (array), $errors (array), $action (string),
 * $submitLabel (string), $formTitle (string), $cancelUrl (string).
 */
?>
<div class="card card-form">
    <h1><?= e($formTitle); ?></h1>

    <?php if ($errors !== []): ?>
        <div class="alert alert-error" role="alert">
            Please fix the highlighted fields and try again.
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e($action); ?>" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">

        <div class="field">
            <label for="name">Name <span class="required">*</span></label>
            <input id="name" name="name" type="text" required maxlength="<?= CONTACT_MAX_LENGTHS['name']; ?>"
                   value="<?= e($data['name']); ?>"<?= isset($errors['name']) ? ' aria-invalid="true"' : ''; ?>>
            <?php if (isset($errors['name'])): ?>
                <p class="error"><?= e($errors['name']); ?></p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="email">Email <span class="required">*</span></label>
            <input id="email" name="email" type="email" required maxlength="<?= CONTACT_MAX_LENGTHS['email']; ?>"
                   value="<?= e($data['email']); ?>"<?= isset($errors['email']) ? ' aria-invalid="true"' : ''; ?>>
            <?php if (isset($errors['email'])): ?>
                <p class="error"><?= e($errors['email']); ?></p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="text" maxlength="<?= CONTACT_MAX_LENGTHS['phone']; ?>"
                   value="<?= e($data['phone']); ?>"<?= isset($errors['phone']) ? ' aria-invalid="true"' : ''; ?>>
            <?php if (isset($errors['phone'])): ?>
                <p class="error"><?= e($errors['phone']); ?></p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="4" maxlength="<?= CONTACT_MAX_LENGTHS['notes']; ?>"
                      <?= isset($errors['notes']) ? 'aria-invalid="true"' : ''; ?>><?= e($data['notes']); ?></textarea>
            <?php if (isset($errors['notes'])): ?>
                <p class="error"><?= e($errors['notes']); ?></p>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button class="button button-primary" type="submit"><?= e($submitLabel); ?></button>
            <a class="button button-quiet" href="<?= e($cancelUrl); ?>">Cancel</a>
        </div>
    </form>
</div>
