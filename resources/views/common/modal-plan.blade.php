<x-modal id="plan-modal" data-bs-backdrop="static" data-bs-keyboard="false">
    <form method="post" action="{{ route('plan.create') }}">
        @csrf
        <div class="modal-body p-0">
            <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                <h5 class="mb-0">プラン追加</h5>
            </div>
            <div class="row g-3 p-3">
                <div class="col-12">
                    <x-form-input label="プラン名" name="plan_name" placeholder="プラン名を指定してください" :required="true" maxlength="20" />
                </div>
                <div class="col-12">
                    <div class="form-group fill">
                        <label class="form-label text-primary f-w-600 mb-2 required">スケジュール</label>
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr class="table-light">
                                <th class="plan-time">時間帯</th>
                                <th class="plan-interval">実行間隔</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="plan-time"><input type="text" name="time" placeholder="00:00 ~ 00:00" /></td>
                                <td class="plan-interval">
                                    <input type="text" name="interval_from" placeholder="60" maxlength="4" />
                                    ~
                                    <input type="text" name="interval_to" placeholder="120" maxlength="4" />
                                    &nbsp;秒
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button id="plan-add-row" class="btn btn-sm btn-warning px-3" type="button">行追加</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">キャンセル</button>
            <button id="plan-add-submit" type="button" class="btn btn-sm btn-primary px-5">確定</button>
        </div>
    </form>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var $modal = $('#plan-modal');
        var $plan_name = $modal.find('input[name="plan_name"]');
        var $schedules = $modal.find('table tbody');
        $modal.find('input[name="time"]').mask('00:00 ~ 00:00');
        $('#plan-add-row').click(function () {
            var $row = $schedules.find('tr').first().clone();
            $row.find('input[name="time"]').mask('00:00 ~ 00:00');
            $row.find('input').val('');
            $row.find('td').removeClass('is-invalid');
            $schedules.append($row);
        });
        $('#plan-add-submit').click(function () {
            $modal.find('button').prop('disabled', true);
            $modal.find('.is-invalid').removeClass('is-invalid');
            $modal.find('.invalid-feedback').remove();

            var plan_name = $plan_name.val();
            if (plan_name === '') {
                $plan_name.addClass('is-invalid').after('<label class="invalid-feedback">プラン名を指定してください！</label>')
            }

            var schedules = [];
            $schedules.find('tr').each(function (i) {
                var schedule = {};

                var time = $(this).find('input[name="time"]').val();
                var range = time.split(' ~ ');
                if (range.length === 2) {
                    var time_from = range[0];
                    var time_to = range[1];
                    var regex = /^((2[0-3])|([01]\d):[0-5]\d)|(24:00)$/;
                    if (regex.test(time_from) && regex.test(time_to)) {
                        if (parseInt(time_to.replace(':', '')) > parseInt(time_from.replace(':', ''))) {
                            schedule.from = time_from;
                            schedule.to = time_to;
                        }
                    }
                }
                if (schedule.from === undefined) {
                    $(this).find('td.plan-time').addClass('is-invalid');
                }

                var interval_form = $(this).find('input[name="interval_from"]').val();
                var interval_to = $(this).find('input[name="interval_to"]').val();

                // 固定時間
                if (interval_form === '') {
                    interval_form = interval_to;
                }
                if (interval_to === '') {
                    interval_to = interval_form;
                }

                interval_form = parseInt(interval_form);
                interval_to = parseInt(interval_to);
                if (!isNaN(interval_form) && !isNaN(interval_to) && interval_form > 0 && interval_to > 0 && interval_form <= interval_to) {
                    schedule.interval = [interval_form, interval_to];
                } else {
                    $(this).find('td.plan-interval').addClass('is-invalid');
                }

                if (schedule.from !== undefined && schedule.to !== undefined && schedule.interval !== undefined) {
                    schedule.index = i;
                    schedules.push(schedule);
                }
            });

            // スケジュール競合検証
            if (schedules.length > 1) {
                schedules.sort((a, b) => {
                    return parseInt(a.from.replace(':', '')) - parseInt(b.from.replace(':', ''));
                });
                schedules.reduce((previous, current) => {
                    if (parseInt(current.from.replace(':', '')) < parseInt(previous.to.replace(':', ''))) {
                        $schedules.find('tr').eq(current.index).find('td.plan-time').addClass('is-invalid');
                    }
                    return current;
                });
            }

            // 送信
            if ($modal.find('.is-invalid').length === 0) {
                var data = {
                    name: plan_name,
                    content: JSON.stringify(schedules.map(s => {
                        return {
                            time: [s.from, s.to],
                            interval: s.interval
                        }
                    })),
                };

                var $form = $(this).closest('form');

                @if(isset($mode) && $mode === 'ajax')
                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: data,
                    dataType: 'json',
                }).done(function (plan) {
                    $modal.trigger('plan.created', [plan]);
                    $modal.modal('hide');
                }).fail(function (jqXHR) {
                    notify('プランの作成が失敗しました！\n画面更新の上もう一度操作してください！', 'error');
                }).always(function () {
                    $modal.find('button').prop('disabled', false);
                });
                @else
                $form.find('input:not(:hidden)').prop('disabled', true);
                $.each(data, function (name, value) {
                    if (name === 'content') {
                        value = value.replace(/"/g, '&quot;');
                    }
                    $form.append('<input type="hidden" name="' + name + '" value="' + value + '" />');
                });
                $form.submit();
                @endif
            } else {
                $modal.find('button').prop('disabled', false);
            }
        });
        $modal.on('hidden.bs.modal', function () {
            $modal.find('.is-invalid').removeClass('is-invalid');
            $modal.find('.invalid-feedback').remove();
            $plan_name.val('');
            var $row = $schedules.find('tr').first().clone();
            $row.find('input[name="time"]').mask('00:00 ~ 00:00');
            $row.find('input').val('');
            $schedules.empty().append($row);
        });
    });
</script>
