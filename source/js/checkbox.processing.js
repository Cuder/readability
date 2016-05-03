function updateButton() {
    if ($('.group1:checked').length == 0) {
        $('.group2')
            .prop('disabled', true)
            .prop('checked', false);
    } else {
        $('.group2').prop('disabled', false);
    }
}
$('.group1').change(function () {
    updateButton();
});
updateButton();
