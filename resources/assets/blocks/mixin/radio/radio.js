$(function () {
    $(document).on('keydown.default-radio', '.default-radio__custom', function(event) {
        let pb = $(this).closest('.default-radio');
        if (event.keyCode === 32 || event.keyCode === 13) {// Space or Enter
            pb.find('input').trigger('click');
            return false;
        }
    });
});