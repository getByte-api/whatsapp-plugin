<table class="table table-bordered table-striped table-hover table-sm">
    <tbody>
    <?php foreach(\General\General\Classes\Helpers\LazyHelper::array_filter_recursive((array)$value) as $key => $item): ?>
    <?php if($item): ?>
    <tr>
        <td><?= $key ?></td>
        <td>
            <?php if(is_array($item) && $item): ?>
                <?= $this->makePartial('$/getbyte/whatsapp/controllers/messagelogs/_data_dump.php', ['value' => $item]) ?>
            <?php elseif(trim($item)): ?>
                <span style="word-break: break-word;"><?= $item ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
</table>
