
Highcharts.chart('container', {

    chart: {
        type: 'column'
    },

    title: {
        text: 'Daily Applicaiton Counts'
    },

    xAxis: {
        allowDecimals: false,
        data: [<?php echo join($col_date, ',') ?>],
        type:'date',
        labels: {
 					formatter: function () {
 						return '<?php echo $currentMonthNameShort . " " ?>' + (this.value +1);
 					}
 				}

    },

    yAxis: {
        allowDecimals: false,
        min: 0,
        title: {
            text: 'Applications'
        }
    },

    tooltip: {
        formatter: function () {
            return '<b><?php echo $currentMonthNameShort . " " ?>' + (this.x +1) + '</b><br/>' +
                this.series.name + ': ' + this.y + '<br/>' +
                'Total: ' + this.point.stackTotal;
        }
    },

    plotOptions: {
        column: {
            stacking: 'normal'
        },
        plotOptions: {
        series: {
            groupPadding: 0
        }
    },
    },

    series: [{
        name: 'Applyweb',
        data: [<?php echo join($col_aw, ',') ?>],
        stack: 'current',
        color: '#F19A9B',
        pointPadding: 0.05,
        groupPadding:0.1,
        maxPointWidth:100,
        minPointWidth:5,
        borderWidth: 1
    }, {
        name: 'Coalition',
        data: [<?php echo join($col_coal, ',') ?>],
        stack: 'current',
        color: '#8F3192',
        pointPadding: 0.05,
        groupPadding:0.1,
        maxPointWidth:100,
        minPointWidth:5,
        borderWidth: 1
    }, {
        name: 'Applyweb (Prev)',
        data: [<?php echo join($col_py_aw, ',') ?>],
        stack: 'previous year',
        color: '#D54D88',
        pointPadding: 0.05,
        groupPadding:0.1,
        maxPointWidth:100,
        minPointWidth:5,
        borderWidth: 1
    }, {
        name: 'Coalition (Prev)',
        data: [<?php echo join($col_py_coal, ',') ?>],
        stack: 'previous year',
        color: '#461765',
        pointPadding: 0.05,
        groupPadding:0.1,
        maxPointWidth:100,
        minPointWidth:5,
        borderWidth: 1
    }]
});
