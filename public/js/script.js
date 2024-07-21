'use strict';

$(function () {
    ajaxSetup();
});

function ajaxSetup() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        error: function (jqXHR) {
            switch (jqXHR.status) {
                case 401:
                    $('#auth-modal').modal('show');
                    break;

                case 419:
                    $('#csrf-modal').modal('show');
                    break;

                default:
                    if (jqXHR.message) {
                        notify(jqXHR.message, 'error');
                    }
            }
        }
    });
}

function notify(message, type='success') {
    if (type === 'error') {
        type = 'danger';
    }

    var $template = $(`
        <div class="toast align-items-center text-white bg-` + type + ` border-0 mb-3" role="alert" data-bs-autohide="true" aria-live="assertive" aria-atomic="true">
            <div class="d-flex justify-content-between">
                <div class="toast-body">` + message.replace(/\n/g, '<br>') + `</div>
                <button class="btn-close btn-close-white flex-shrink-0 me-2" type="button" data-bs-dismiss="toast" aria-label="Close" style="margin-top: 0.75rem;"></button>
            </div>
        </div>
    `);
    $template.on('show.bs.toast', function () {
        $container.show();
    });
    $template.on('hidden.bs.toast', function () {
        $(this).remove();
        if ($container.find('.toast').length === 0) {
            $container.hide();
        }
    });

    var toast = new bootstrap.Toast($template.get(0))
    var $container = $('#notify-box')
    $container.append($template);
    toast.show();
}

function initSelect(target) {
    return new Choices(target, {
        silent: true,
        searchResultLimit: 100,
        placeholder: true,
        shouldSort: false,
        searchPlaceholderValue: '検索',
        noResultsText: 'データなし',
        itemSelectText: '',
        classNames: {
            containerOuter: 'choices',
        }
    });
}

function initDatePicker(options) {
    moment.defineLocale('ja', {
        months : '一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月'.split('_'),
        monthsShort : '1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月'.split('_'),
        weekdays : '日曜日_月曜日_火曜日_水曜日_木曜日_金曜日_土曜日'.split('_'),
        weekdaysShort : '日曜_月曜_火曜_水曜_木曜_金曜_土曜'.split('_'),
        weekdaysMin : '日_月_火_水_木_金_土'.split('_'),
        longDateFormat : {
            LT : 'Ah時m分',
            LTS : 'Ah時m分s秒',
            L : 'YYYY-MM-DD',
            LL : 'YYYY年M月D日',
            LLL : 'YYYY年M月D日Ah時m分',
            LLLL : 'YYYY年M月D日Ah時m分 dddd'
        },
        meridiemParse: /午前|午後/i,
        isPM : function (input) {
            return input === '午後';
        },
        meridiem : function (hour, minute, isLower) {
            if (hour < 12) {
                return '午前';
            } else {
                return '午後';
            }
        },
        calendar : {
            sameDay : '[今日] LT',
            nextDay : '[明日] LT',
            nextWeek : '[来週]dddd LT',
            lastDay : '[昨日] LT',
            lastWeek : '[前週]dddd LT',
            sameElse : 'L'
        },
        dayOfMonthOrdinalParse: /\d{1,2}(日|月|週)/,
        ordinal: function (number, period) {
            switch (period) {
                case 'd':
                case 'D':
                case 'DDD':
                    return number + '日';
                case 'M':
                    return number + '月';
                case 'w':
                case 'W':
                    return number + '週';
                default:
                    return number;
            }
        },
        relativeTime : {
            future : '%s後',
            past : '%s前',
            s : '数秒',
            m : '1分',
            mm : '%d分',
            h : '1時間',
            hh : '%d時間',
            d : '1日',
            dd : '%d日',
            M : '1ヶ月',
            MM : '%dヶ月',
            y : '1年',
            yy : '%d年'
        },
        week: {
            // GB/T 7408-1994《数据元和交换格式·信息交换·日期和时间表示法》与ISO 8601:1988等效
            dow: 1, // Monday is the first day of the week.
            doy: 4  // The week that contains Jan 4th is the first week of the year.
        }
    });

    var $date_picker = $('.datepicker');
    $date_picker.attr('autocomplete', 'off').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        autoUpdateInput: false,
        showDropdowns: true,
        ...options
    });
    $date_picker.on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });

    var $date_range_picker = $('.date-range-picker').attr('autocomplete', 'off');
    $date_range_picker.daterangepicker({
        autoApply: true,
        autoUpdateInput: false,
        showDropdowns: true,
        ...options
    });

    $date_range_picker.on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' ~ ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $date_range_picker.on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
}
