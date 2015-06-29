$(document).ready(function () {
    RhinoReportBarManager.init();
});

RhinoReportBarManager = {
    bars: [],
    init: function () {
        var $barBlocs = $('.myBar');
        $.each($barBlocs, function (key, element) {
            var id = $(element).attr('id');
            RhinoReportBarManager.bars[id] = new RhinoReportBarInstance(id);
            RhinoReportBarManager.bars[id].init();
        });
    }
};