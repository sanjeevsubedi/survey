<h3><?php echo $question; ?></h3>
<script>
    var pieData = [];
    var options = [];
    var pc = [];
    var no = [];
    var pieDataAdvanced = [];
</script>
<?php
//var_dump($stats);
//var_dump($total_users);
?>
<table>
    <?php if (!empty($stats)): ?>
        <tr>
            <th>Option</th>
            <th>Response Value</th>
        </tr>
        <?php foreach ($stats as $k => $stat): ?>
            <?php $pc = round((($stat->cnt / $total_users) * 100), 2); ?>
            <tr>
                <td><?php echo $stat->opt_name; ?></td>
                <td>
                    <?php echo $pc . ' %'; ?>
                    (<?php echo $stat->cnt . ' people out of ' . $total_users ?>)
                </td>
            </tr>
            <script>
                var item= ['<?php echo $stat->opt_name; ?>',   <?php echo $pc; ?>];
                pieData.push(item);
                options.push('<?php echo $stat->opt_name; ?>');
                no.push(<?php echo $stat->cnt; ?>);
                pc.push(<?php echo $pc; ?>);
                                                                                                                                                                                                                                                    
                var pieItem = {
                    name: '<?php echo $stat->opt_name; ?>',
                    y: <?php echo $pc; ?>,
                    color: Highcharts.getOptions().colors[<?php echo $k; ?>] // Jane's color
                }
                pieDataAdvanced.push(pieItem);
                                                                                                                                                                                                                                                    
            </script>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<div id="pie-chart" style="width:100%; height:400px;"></div>
<hr />
<div id="chart" style="width:100%; height:400px;"></div>


<script>
    console.log(pieData);
    jQuery('#pie-chart').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Response pie-chart for "<?php echo $question; ?>"'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
                type: 'pie',
                name: 'Response share',
                data:pieData,
                showInLegend: true
            }]
    });
                                        
    $('#chart').highcharts({
        chart: {
        },
        title: {
            text: 'Response bar diagram for "<?php echo $question; ?>"'
        },
        xAxis: {
            categories: options
        },
        tooltip: {
            formatter: function() {
                var s;
                if (this.point.name) { // the pie chart
                    s = ''+
                        this.point.name +': '+ this.y +'%';
                } else {
                    s = ''+
                        this.x  +': '+ this.y;
                }
                return s;
            }
        },/*
        labels: {
            items: [{
                    html: 'Response share',
                    style: {
                        left: '40px',
                        top: '8px',
                        color: 'black'
                    }
                }]
        },*/
        series: [{
                type: 'column',
                name: 'options',
                data: no
            }/*,  {
                type: 'pie',
                name: 'Response Share',
                data: pieDataAdvanced,
                center: [300, 80],
                size: 100,
                showInLegend: false,
                dataLabels: {
                    enabled: false
                }
            }*/]
    });
</script>
