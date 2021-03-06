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
        var $container = this.$container;
        $.fn.dataTable.TableTools.buttons.export_to_xls = $.extend(
                true,
                {},
                $.fn.dataTable.TableTools.buttonBase,
                {
                    "sNewLine": "<br>",
                    "sButtonText": "Copy to element",
                    "target": "",
                    "fnClick": function (button, conf) {
                        var url = $container.find('.dataTableData').attr('data-export');
                        var args = window.location.search.substring(1);
                        if (args) {
                            window.open(url + "?" + args + "&format=xls");
                        }
                    }
                }
        );
        this.buildTable();
        this.initEvent();
        this.setListener();
    },
    buildTable: function () {
        this.myTable = this.$container.find('.dataTable').dataTable(this.getConfigTable());
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
        var $container = $('#' + this.id);
        var tableId = $container.find('.dataTable').attr('id');

        var config = this.getConfigTable();
        config.bDestroy = true;

        var table = $('#' + tableId).DataTable(config);

        table.clear();

        table.rows.add(this.getStructuredData());

        table.draw();
    },
    getConfigTable: function () {
        var tableData = this.getData();

        var groupIdIndex = [];
        for (i = 0; i < tableData.groupHeadingLevel; i++) {
            groupIdIndex.push(tableData.nbColumns * (i + 1));
        }

        var config = {
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "aButtons": [
                    "print",
                    {
                        "sExtends": "collection",
                        "sButtonText": "Export",
                        "aButtons": [{
                                "sExtends": "export_to_xls",
                                "sButtonText": "Export to xls"
                            }]
                    }
                ]
            },
            "iDisplayLength": 50,
            "lengthMenu": [[50, 100, 1000, -1], [50, 100, 1000, "All"]],
            'data': this.getStructuredData(),
            'aoColumns': this.getColumnNames(),
            "aoColumnDefs": [{"bVisible": false, "aTargets": this.getHiddenColumns()}],
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
        }

        return config;
    },
    getHiddenColumns: function () {
        var hiddenColumns = [];
        var tableData = this.getData();
        var i;

        for (i = tableData.nbColumns; i < tableData.nbColumns + (tableData.nbColumns * tableData.groupHeadingLevel); i++) {
            hiddenColumns.push(i);
        }

        return hiddenColumns;
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
    },
    getColumnNames: function () {
        var columnNames = [];
        var columnData = this.getData().head;
        $.each(columnData, function (index, value) {
            var col = {"sTitle": value};
            columnNames.push(col);
        });

        return columnNames;
    }
};