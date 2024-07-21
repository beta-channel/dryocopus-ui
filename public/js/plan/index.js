$(function () {
    initDelete();
    initCopy();
});

function initDelete() {
    var $modal = $('#modal-delete');
    $modal.on('show.bs.modal', function (event) {
        var plan_id = $(event.relatedTarget).data('id');
        $modal.find('form').append('<input type="hidden" name="id_list[]" value="' + plan_id + '" />');
    });
    $modal.on('hidden.bs.modal', function () {
        $modal.find('input[name="id_list\[\]"]').remove();
    });
}

function initCopy() {

}
