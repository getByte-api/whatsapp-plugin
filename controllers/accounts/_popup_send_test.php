<div class="modal-header">
    <button type="button" class="close" data-dismiss="popup">&times;</button>
    <h4 class="modal-title">
        Enviar mensagem de teste
    </h4>
</div>
<form class="form-elements" role="form" data-request="onSendTest" data-popup-load-indicator>
    <div class="modal-body">
        <input type="hidden" name="account_id" value="<?= $account_id ?>">
<!--        message type file, text or image-->
        <div class="form-group dropdown-field span-full">
            <label>Tipo de mensagem</label>
            <select class="form-control custom-select" name="message_type">
                <option value="text">Texto</option>
                <option value="image">Imagem</option>
                <option value="document">Documento</option>
            </select>
        </div>
        <div class="form-group span-full">
            <label>Telefone</label>
            <input
                type="text"
                name="phone"
                class="form-control"
                placeholder="Digite o telefone para enviar a mensagem ex: 5511999999999">
        </div>
        <div class="form-group span-full">
            <label>Link do arquivo ou imagem</label>
            <input
                type="text"
                name="file_url"
                class="form-control"
                placeholder="Insira o link do arquivo ou da imagem se for o caso">
        </div>
        <div class="form-group span-full">
            <label>Mensagem</label>
            <textarea
                name="message"
                class="form-control"
                rows="5"
                placeholder="Digite a mensagem que deseja enviar"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <!-- Popup action buttons go here -->
        <button
            type="submit"
            class="btn btn-primary oc-icon-send"
            data-load-indicator="Enviando">
            Enviar
        </button>
        <button
            type="button"
            class="btn btn-default"
            data-dismiss="popup">
            <?= e(trans('backend::lang.relation.close')) ?>
        </button>
    </div>
</form>
