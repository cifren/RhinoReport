$(document).ready(function() {
    $('#toggleAll').click(function(){
        if( $(this).hasClass('showAll') ){
            $('#toggleAll').nextAll('table').first().find('tr').show();
            $(this).removeClass('showAll').addClass('hideAll');
            $(this).children('i').removeClass('icon-chevron-right').addClass('icon-chevron-down');
        }else{
            $('#toggleAll').nextAll('table').first().find('tr.collapse-table:not(.level1)').hide();
            $(this).removeClass('hideAll').addClass('showAll');
            $(this).children('i').removeClass('icon-chevron-down').addClass('icon-chevron-right');
        }
        return false;
    });
});