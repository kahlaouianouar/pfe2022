(function($){
    var app;
    app = {
      init: function() {
        this._slider();
      }, 
      _slider: function(){
        $('.fade').slick({
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear'
          });
          Highcharts.setOptions({
            chart: {
                style:{
                        fontFamily:'Arial, Helvetica, sans-serif', 
                        fontSize: '2em',
                        color:'#f00'
                    }
            }
        });
        Highcharts.chart('container2', {

            title: {
              text: 'Highcharts pie chart'
            },
          
            xAxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
          
            series: [{
              type: 'pie',
              allowPointSelect: true,
              keys: ['name', 'y', 'selected', 'sliced'],
              data: [
                ['Apples', 29.9, false],
                ['Pears', 71.5, false],
                ['Oranges', 106.4, false],
                ['Plums', 129.2, false],
                ['Bananas', 144.0, false],
                ['Peaches', 176.0, false],
                ['Prunes', 135.6, true, true],
                ['Avocados', 148.5, false]
              ],
              showInLegend: true
            }]
          });
            $('#container1').highcharts({
                chart: {
                    type: 'column'
                },
                colors: [
                   '#ED5565',
                   '#5D9CEC', 
                   '#A0D468', 
                   '#FFCE54',  
                   '#48CFAD', 
                   '#AC92EC',
                   '#AAB2BD', 
                   '#D770AD', 
                   '#c42525', 
                   '#a6c96a'
                ],
                title: {
                    text: 'Example Chart',
                    style: {
                      color: '#555'
                    }
                },
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom',
                    borderWidth: 0,
                    backgroundColor: '#FFFFFF'
                },
                xAxis: {
                    categories: [
                        '2006',
                        '2007',
                        '2008',
                        '2009',
                        '2010',
                        '2011'
                    ]
                },
                yAxis: {
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    shared: false,
                    valueSuffix: 'points'
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.1
                    },
                series: {
                    groupPadding: .15
                }
                },
                series: [{
                    name: 'Data 1',
                    data: [3]
                }, {
                    name: 'Data 2',
                    data: [5]
                }, {
                    name: 'Data 3',
                    data: [7]
                }, {
                    name: 'Data 4',
                    data: [4]
                }, {
                    name: 'Data 5',
                    data: [3]
                }, {
                    name: 'Data 6',
                    data: [2.5]
                }, {
                    name: 'Data 7',
                    data: [1]
                }, {
                    name: 'Data 8',
                    data: [5]
                }]
            });
      },       
    };
    app.init();
  $(window).on('load',function(){

  });
  $(window).resize(function () {
 
  });       
  $(window).scroll(function() {
  });  
  }(jQuery));