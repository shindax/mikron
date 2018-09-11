var v = [{
            name: 'Microsoft Internet Explorer',
            y: 56.33
        }, {
            name: 'Chrome',
            y: 24.03,
            sliced: true,
            selected: true
        }, {
            name: 'Firefox',
            y: 10.38
        }, {
            name: 'Safari',
            y: 4.77
        }, {
            name: 'Opera',
            y: 0.91
        }, {
            name: 'Proprietary or Undetectable',
            y: 0.2
        }]

var tmp_data = [
        { name: 'Name' , y: 16.2 },
        { name: 'Name2', y: 16.2 },
        { name: 'Name3', y: 16.2 },
        { name: 'Name4', y: 16.2 },
        { name: 'Name5', y: 16.2 },
        { name: 'Name6', y: 16.2 }
        ];


// Actions after full page loading
$( function()
{
 
 var tmp_data2 = [];
 var i ;
 
        for( i = 0 ; i < 6 ; i ++ )
            tmp_data2[i] = { name: 'Name', y: 16.2 };
 
      console.log( tmp_data  );
      console.log( tmp_data2 );      
 
 
    chart( tmp_data2 );

});

function chart( indata )
{
  Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Browser market shares January, 2015 to May, 2015'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: 
    {
        pie: {
            animation: false,        
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: indata
    }]
});

}

