<?php if ($status == 'ok' ): ?>
<div class="alert alert-success"><?= $message ?></div>
<?php else: ?>
<div class="alert alert-danger"><?= $message ?></div>
<?php endif; ?>