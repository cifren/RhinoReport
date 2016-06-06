function RhinoReportBarInstance(id) {
    this.id = id;
}
RhinoReportBarInstance.prototype = {
    id: null,
    $container: null,
    data: null,
    myChart: null,
    init: function () {
        this.$container = $('#' + this.id);
        this.buildChart();
        this.setListener();
    },
    buildChart: function () {
        var ctx = this.$container.find('.canvas').get(0).getContext("2d");
        this.myChart = new Chart(ctx).Bar(this.getStructuresData(), this.getDefaultOptions());
        this.$container.find('.legend').innerHTML = this.myChart.generateLegend();
    },
    reloadChart: function () {
        this.myChart.destroy();
        this.buildChart();
    },
    writeData: function (data) {
        this.$container.find('.barData').attr('data-load', JSON.stringify(data));
    },
    getStructuresData: function () {
        return {
            labels: this.getLabels(),
            datasets: this.getDataSets()
        };
    },
    getLabels: function () {
        var labelsObj = this.getData().labels;
        var labels = [];
        for (var index in labelsObj) {
            labels.push(labelsObj[index]);
        }

        return labels;
    },
    getDataSets: function () {
        var datasets = this.getData().datasets;
        var structuredDatasets = [];
        $.each(datasets, function (index, value) {
            var dataset = {};
            dataset.label = value.label;
            dataset.data = value.dataset;
            var options = value.options;
            if(options){
                $.each(options, function (index, value) {
                    dataset[index] = value;
                });
            }
            structuredDatasets.push(dataset);
        });
        return structuredDatasets;
    },
    getDefaultOptions: function () {
        return {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - If there is a stroke on each bar
            barShowStroke: true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth: 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing: 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing: 1,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
        };
    },
    setListener: function () {
        var cls = this;
        DataEventDispatcher.addEventListener(this.id, function (data) {
            cls.writeData(data);
            cls.clearData();
            cls.reloadChart();
        });
    },
    getData: function () {
        if (this.data === null) {
            this.data = JSON.parse(this.$container.find('.barData').attr('data-load'));
        }
        return this.data;
    },
    clearData: function () {
        this.data = null;
    }
};