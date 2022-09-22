define([], function() {
    var Action = {
        index: function() {
            var self;
            var option = {
                template: '#app',
                data: function() {
                    var date = new Date();
                    var Y = date.getFullYear();
                    var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1);
                    var D = date.getDate();
                    var now = new Date(Y + '-' + M + '-' + D).valueOf() + new Date().getTimezoneOffset() * 60 * 1000;
                    return {
                        data: {},
                        echart: {
                            init: false,
                            dataInit: false,
                            instance: null,
                            data: {}
                        },                        
                        form: {
                            start: (now - 6 * 24 * 60 * 60 * 1000) / 1000,
                            end: (now + 24 * 60 * 60 * 1000 - 1000) / 1000
                        }
                    };
                },
                mounted: function() {
                    self = this;
                    this.init();
                    this.initEchartData();
                    require(['echarts'], function(echarts) {
                        var Echart = echarts.init(document.getElementById('chart-section'));
                        self.echart.init = true;
                        self.echart.instance = Echart;
                    });
                    window.addEventListener('resize', function () {
                        self.echart.instance.resize();
                    });
                },
                watch: {
                    'echart.dataInit': function(v) {
                        if (v && this.echart.init) this.render();
                    },
                    'echart.init': function(v) {
                        if (v && this.echart.dataInit) this.render();
                    }
                },
                methods: {
                    init: function() {
                        this.$http.post('/shop/admin/index/index').then(function(data) {
                            self.data = data;
                        });
                    },
                    initEchartData: function(cb) {
                        this.$http.post('/shop/admin/index/getEchartData', this.form).then(function(data) {
                            self.echart.data = data;
                            self.echart.dataInit = true
                            typeof cb == 'function' && cb();
                        })
                    },
                    handleRefresh: function() {
                        this.initEchartData(function() {
                            self.render();
                        })
                    },
                    render: function() {
                        var data = this.echart.data;
                        var option = {
                            legend: {
                                data: ['订单数', '订单金额']
                            },
                            xAxis: [
                                {
                                    type: 'category',
                                    data: data.count.title
                                }
                            ],
                            yAxis: {
                                type: 'value'
                            },
                            series: [
                                {
                                    name: '订单数',
                                    type: 'bar',
                                    label: {
                                        show: true
                                    },
                                    data: data.count.value
                                },
                                {
                                    name: '订单金额',
                                    type: 'bar',
                                    label: {
                                        show: true
                                    },
                                    data: data.money.value
                                }
                            ]
                        };
                        self.echart.instance.setOption(option);
                    },
                }
            };
            return Yi.event.listen(EventPrefix + 'Option', option);
        }
    };    
    return Yi.event.listen(EventPrefix + 'Action', Action);
})