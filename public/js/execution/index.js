$(function () {
    initPlan();
})

function initPlan() {
    var $modal = $('#modal-plan');
    var $plan = $modal.find('ul.list-group');
    $modal.on('show.bs.modal', function (event) {
        var plan_content = $(event.relatedTarget).data('plan');
        plan_content.forEach(function (plan) {
            $plan.append('<li class="list-group-item d-flex flex-between-center hover-primary">' +
                '<span>' + plan.time[0] + ' ~ ' + plan.time[1] + '</span>' +
                '<span>間隔 <span class="fw-bold">' + plan.interval[0] + ' ~ ' + plan.interval[1] + '</span> 秒/回</span>' +
                '</li>');
        });
    });
    $modal.on('hidden.bs.modal', function () {
        $plan.empty();
    });
}
