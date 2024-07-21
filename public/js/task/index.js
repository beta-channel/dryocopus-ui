$(function () {
    initSelect('select[name="plan"]');
    initPlan();
    initDelete();
    initOperation();
});

function initPlan() {
    var $modal = $('#modal-plan');
    var $plan_name = $modal.find('h5');
    var $plan = $modal.find('ul.list-group');
    $modal.on('show.bs.modal', function (event) {
        var plan_name = $(event.relatedTarget).text().trim();
        var plan_content = $(event.relatedTarget).data('plan');
        $plan_name.text(plan_name);
        plan_content.forEach(function (plan) {
            $plan.append('<li class="list-group-item d-flex flex-between-center hover-primary">' +
                '<span>' + plan.time[0] + ' ~ ' + plan.time[1] + '</span>' +
                '<span>間隔 <span class="fw-bold">' + plan.interval[0] + ' ~ ' + plan.interval[1] + '</span> 秒/回</span>' +
                '</li>');
        });
    });
    $modal.on('hidden.bs.modal', function () {
        $plan_name.empty();
        $plan.empty();
    });
}

function initDelete() {
    var $modal = $('#modal-delete');
    $modal.on('show.bs.modal', function (event) {
        var task_id = $(event.relatedTarget).data('id');
        $modal.find('form').append('<input type="hidden" name="id_list[]" value="' + task_id + '" />');
    });
    $modal.on('hidden.bs.modal', function () {
        $modal.find('input[name="id_list\[\]"]').remove();
    });
}

function initOperation() {
    $('.task-operation').on('click', 'a.task-exe', function () {
        $(this).closest('.btn-group').find('a.dropdown-item').addClass('disabled').attr('onclick', 'return false;');
        $('td.task-operation button.dropdown-toggle').prop('disabled', true);

        var id = $(this).data('id');
        var $status = $(this).closest('tr').find('.task-status .badge');
        var $container = $(this).closest('.task-operation-container');
        var $icon = $(this).find('svg');
        var $loading = $('<span class="spinner-border spinner-border-sm text-primary me-2" style="width: 1rem; height: 1rem;"></span>');
        $.ajax({
            url: this.href,
            type: 'post',
            data: {'id_list[]': id},
            dataType: 'json',
            context: this,
            beforeSend: function () {
                $icon.hide();
                $icon.after($loading);
            }
        }).done(function (res) {
            var status = res[id];

            var disableOperate = function () {
                var $edit = $container.find('a.task-edit');
                if ($edit.length > 0) {
                    var edit_page_url = $edit.attr('href');
                    var $disabled_edit = $('<span class="dropdown-item task-edit disabled" data-bs-toggle="tooltip" data-bs-placement="right" title="タスク有効中" data-href="' + edit_page_url + '">編集</span>');
                    $edit.after($disabled_edit).remove();
                    new bootstrap.Tooltip($disabled_edit);
                }
                var $delete = $container.find('a.task-delete');
                if ($delete.length > 0) {
                    var $disabled_delete = $('<span class="dropdown-item text-danger task-delete disabled" data-bs-toggle="tooltip" data-bs-placement="right" title="タスク有効中">削除</span>');
                    $delete.after($disabled_delete).remove();
                    new bootstrap.Tooltip($disabled_delete);
                }
            }

            switch (status) {
                case 'startup':
                    $status.removeClass().addClass('badge rounded-pill badge-soft-danger').html('<i class="fas fa-hourglass-half me-2"></i>起動中');

                    $(this).remove();
                    $container.prepend('<span class="dropdown-item fw-bold disabled"><i class="fas fa-play me-2"></i>起動中</span>');

                    disableOperate();
                    break;

                case 'running':
                    $status.removeClass().addClass('badge rounded-pill bg-danger').text('実行中');

                    $(this).remove();
                    $container.prepend('<a class="dropdown-item fw-bold task-exe" href="' + task_stop_url + '" data-id="' + id + '"><i class="fas fa-stop text-danger me-2"></i>停止</a>');

                    disableOperate();
                    break;

                case 'preparing':
                    $status.removeClass().addClass('badge rounded-pill badge-soft-warning').text('準備中');

                    $(this).remove();
                    $container.prepend('<a class="dropdown-item fw-bold task-exe" href="' + task_stop_url + '" data-id="' + id + '"><i class="fas fa-stop text-danger me-2"></i>停止</a>');

                    disableOperate();
                    break;

                case 'stopping':
                    $status.removeClass().addClass('badge rounded-pill badge-soft-secondary').html('<i class="fas fa-hourglass-half me-2"></i>停止中');

                    $(this).remove();
                    $container.prepend('<span class="dropdown-item fw-bold disabled"><i class="fas fa-stop me-2"></i>停止中</span>');

                    disableOperate();
                    break;

                case 'stopped':
                    $status.removeClass().addClass('badge rounded-pill bg-secondary').text('停止');

                    var $row = $(this).closest('tr');
                    var has_plan = $row.find('td.task-plan').data('plan') !== '';
                    var is_preparing = $row.find('td.task-schedule').data('preparing');
                    $(this).remove();
                    if (is_preparing) {
                        if (has_plan) {
                            $container.prepend('<a class="dropdown-item fw-bold task-exe" href="' + task_active_url + '" data-id="' + id + '"><i class="fas fa-bolt text-warning me-2"></i>有効にする</a>');
                        } else {
                            $container.prepend('<span class="dropdown-item fw-bold disabled" data-bs-toggle="tooltip" data-bs-placement="right" title="プランを指定してください"><i class="fas fa-bolt me-2"></i>有効にする</span>');
                        }
                    } else {
                        if (has_plan) {
                            $container.prepend('<a class="dropdown-item fw-bold task-exe" href="' + task_startup_url + '" data-id="' + id + '"><i class="fas fa-play text-success me-2"></i>実行開始</a>');
                        } else {
                            $container.prepend('<span class="dropdown-item fw-bold disabled" data-bs-toggle="tooltip" data-bs-placement="right" title="プランを指定してください"><i class="fas fa-play me-2"></i>実行開始</span>');
                        }
                    }

                    var $edit = $container.find('span.task-edit');
                    if ($edit.length > 0) {
                        var edit_page_url = $edit.data('href');
                        $edit.after('<a class="dropdown-item task-edit" href="' + edit_page_url + '">編集</a>').remove();
                    }
                    var $delete = $container.find('span.task-delete');
                    if ($delete.length > 0) {
                        $delete.after('<a class="dropdown-item text-danger task-delete" href="#modal-delete" data-bs-toggle="modal" data-id="' + id + '">削除</a>').remove();
                    }

                    break;
            }
        }).fail(function (jqXHR) {
            notify('エラーが発生しました！', 'error')
        }).always(function () {
            $loading.remove();
            $icon.show();
            $('td.task-operation a.dropdown-item.disabled').removeClass('disabled').removeAttr('onclick');
            $('td.task-operation button.dropdown-toggle').prop('disabled', false);
        });

        return false;
    });

    // 起動

    // 準備

    // 停止
}
