$(document).ready(function() {
    $("#report_table").collapseTable();
        
    var xthf = []; 
    xAddEventListener(window, 'load',
        function() {
            //table class 'header-fixed', class 'top-bar' is header at the top of the page, menu etc...
            xthf[0] = new xTableHeaderFixed('header-fixed', window, xHeight('top-bar'));
        }, false
    ); 
});
