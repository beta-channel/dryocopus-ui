$(function () {
    initDatePicker({
        minDate: new Date()
    });
    initPlan();
});

function initPlan() {
    var choices = initSelect('select[name="plan_id"]');
    var $modal = $('#plan-modal');
    $('#create-plan').click(function () {
        $modal.modal('show');
    });
    $modal.on('plan.created', function (event, plan) {
        choices.setChoices(
            [
                { value: plan.id, label: plan.name, selected: true, disabled: false },
            ],
            'value',
            'label',
            false
        );
    });
}
