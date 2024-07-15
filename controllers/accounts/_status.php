<?php if($record->status == 'CONNECTED'): ?>
    <span class="badge rounded-pill text-bg-success text-white">CONNECTED</span>
<?php else: ?>
    <span class="badge rounded-pill text-bg-danger text-white"><?= $record->status ?></span>
<?php endif; ?>
