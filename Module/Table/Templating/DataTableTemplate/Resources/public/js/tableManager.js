$(document).ready(function () {
    RhinoReportDataTableManager.init();
});

RhinoReportDataTableManager = {
    dataTables: [],
    init: function () {
        var $dataTableBlocs = $('.myDataTable');
        $.each($dataTableBlocs, function (key, element) {
            var id = $(element).attr('id');
            RhinoReportDataTableManager.dataTables[id] = new RhinoReportDataTableInstance(id);
            RhinoReportDataTableManager.dataTables[id].init();
        });
    }
};