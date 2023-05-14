$(function () {
    $(document).on('keydown.default-checkbox', '.default-checkbox__custom', function(event) {
        let pb = $(this).closest('.default-checkbox');
        if (event.keyCode === 32 || event.keyCode === 13) {// Space or Enter
            pb.find('input').trigger('click');
            return false;
        }
    });
});