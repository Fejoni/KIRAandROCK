$(function () {

    $('.default-input').on('click', function(e){
        e.preventDefault();
        $(this).addClass('active');
        $(this).find('input')[0].focus();
    });

    $('.default-input input').on('blur', function(){
        if($.trim($(this).val()).length === 0){
            $(this).val('');
            $(this).closest('.default-input').removeClass('active');
        }
    });

    $('.default-textarea').on('click', function(e){
        e.preventDefault();
        $(this).addClass('active');
        $(this).find('textarea')[0].focus();
    });

    $('.default-textarea textarea').on('blur', function(){
        if($.trim($(this).val()).length === 0){
            $(this).val('');
            $(this).closest('.default-textarea').removeClass('active');
        }
    });
});
