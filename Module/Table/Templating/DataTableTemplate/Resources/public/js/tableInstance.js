function RhinoReportDataTableInstance(id) {
    this.id = id;
}
RhinoReportDataTableInstance.prototype = {
    id: null,
    $container: null,
    data: null,
    /**
     * 
     * @type dataTable
     */
    myTable: null,
    init: function () {
        this.$container = $('#' + this.id);
        this.buildTable();
        this.initEvent();
        this.setListener();
    },
    buildTable: function () {
        var tableData = this.getData();
        var hiddenColumns = [];
        for (i = tableData.nbColumns; i < tableData.nbColumns + (tableData.nbColumns * tableData.groupHeadingLevel); i++) {
            hiddenColumns.push(i);
        }
        var groupIdIndex = [];
        for (i = 0; i < tableData.groupHeadingLevel; i++) {
            groupIdIndex.push(tableData.nbColumns * (i + 1));
        }

        this.myTable = this.$container.find('.dataTable').dataTable({
            'data': this.getStructuredData(),
            "aoColumnDefs": [{"bVisible": false, "aTargets": hiddenColumns}],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();

                for (y = 0; y < groupIdIndex.length; y++) {
                    var last = null;
                    var groupIndex = groupIdIndex[y];
                    api.column(groupIndex, {page: 'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            var $row = $('<tr class="group group_' + y + '"></tr>');

                            var colspan = 0;
                            // build row
                            for (c = 0; c < tableData.nbColumns; c++) {
                                if (colspan) {
                                    colspan--;
                                    continue;
                                }
                                var colIndex = groupIndex + c;
                                var colData = api.column(colIndex).data()[i];
                                //auto colspan
                                for (ac = 0; ac < 1; ac++) {
                                    if (api.column(3 + colspan + 1).data() [i] === null) {
                                        ac--;
                                        colspan++;
                                    }
                                }

                                var $td = $('<td>' + colData + '</td>');
                                $td.attr('data-colIndex', colIndex);
                                if (colspan) {
                                    $td.attr('colspan', colspan + 1);
                                }
                                $row.append($td);
                            }

                            $(rows).eq(i)
                                    .before(
                                            $row
                                            );

                            last = group;
                        }
                    });
                }
            }
        });

    },
    initEvent: function () {
        var $container = this.$container;
        // Order by the grouping
        $('#' + this.id + ' .dataTable tbody').on('click', 'tr.group td', function () {
            var table = $container.find('.dataTable').dataTable().api();
            var currentOrder = table.order()[0];
            var colIndex = $(this).attr('data-colIndex');
            if (currentOrder[0] === colIndex && currentOrder[1] === 'asc') {
                table.order([colIndex, 'desc']).draw();
            }
            else {
                table.order([colIndex, 'asc']).draw();
            }
        });
    },
    reloadDataTable: function () {
        var $container = $('#tableIng');
        var tableId = $container.find('.dataTable').attr('id');

        var table = $('#' + tableId).DataTable();

        table.clear();

        table.rows.add(this.getStructuredData());

        table.draw();
    },
    getStructuredData: function () {
        var transformedData = [];
        var tableData = this.getData();

        var rows = tableData.bodyRows;
        $.each(rows, function (key, item) {
            var newItem = [];
            for (i = 0; i < item.length - tableData.groupHeadingLevel; i++) {
                newItem.push(item[i]);
            }

            for (i = 0; i < tableData.groupHeadingLevel; i++) {
                for (y = 0; y < tableData.nbColumns; y++) {
                    var groupIndex = tableData.nbColumns + i;
                    var groupId = item[groupIndex];
                    newItem.push(tableData.groupHeadingRows[groupId][y]);
                }
            }

            transformedData[key] = newItem;
        });

        return transformedData;
    },
    writeData: function (data) {
        this.$container.find('.dataTableData').attr('data-load', JSON.stringify(data));
    },
    getDefaultOptions: function () {
        return {};
    },
    setListener: function () {
        var cls = this;
        DataEventDispatcher.addEventListener(this.id, function (data) {
            cls.writeData(data);
            cls.clearData();
            cls.reloadDataTable();
        });
    },
    getData: function () {
        if (this.data === null) {
            this.data = JSON.parse(this.$container.find('.dataTableData').attr('data-load'));
        }
        return this.data;
    },
    clearData: function () {
        this.data = null;
    }
};