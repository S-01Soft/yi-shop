define([], function() {
    var Action = {
        
        index: function() {
            Yi.vue.mixin(Mixins.table);
            var self;
            var columns = [
                { title: $lang('Id'), dataIndex: 'id', key: 'id', align: 'center', customRender: Yi.render.html, }, 
                { title: $lang('Keyword'), dataIndex: 'keyword', key: 'keyword', align: 'center', customRender: Yi.render.html, }, 
                { title: $lang('Hits'), dataIndex: 'hits', key: 'hits', align: 'center', customRender: Yi.render.html, }, 
                { title: $lang('weigh'), dataIndex: 'weigh', key: 'weigh', align: 'center', customRender: Yi.render.html, }, 
                { title: $lang('Status'), dataIndex: 'status', key: 'status', align: 'center', customRender: Yi.render.switch, }, 
                
                { title: $lang('Operate'),key: 'action$', align: 'center',scopedSlots: { customRender: 'action' },},
            ];
            var option = {
                template: '#app',
                data: function() {
                    var data = {
                        columns: Yi.event.listen(EventPrefix + 'Columns', columns),
                        data: [],
                        pagination: {
                            total: 0, page_size_options: ['10', '20', '30', '40', '50']
                        },
                        selectedRowKeys: [],
                        selectedRows: [],
                        loading: false,
                        query: {
                            page: 1, page_size: 10, order: "weigh DESC,hits DESC,id DESC",
                            where: {}
                        },
                    }
                    return Yi.event.listen(EventPrefix + 'Data', data);
                },
                mounted: function() {
                    self = this;
                    this.init();
                },
                methods: {
                    init: function() {                        
                        self.loading = true;
                        var params = Yi.event.listen(EventPrefix + 'InitParams',this.query);
                        this.$http.get(get_url('index'), {params: params}).then(function(data) {
                            self.loading = false;
                            data = Yi.event.listen(EventPrefix + 'Init', data);
                            
                            self.data = data.data;
                            self.pagination.total = data.total;
                            
                        }).catch(function() {
                            self.loading = false;
                        });
                    },
                }
            };
            return Yi.event.listen(EventPrefix + 'Option', option);
        },
        add: function() {
            Yi.vue.mixin(Mixins.form);
            var self;
            var option = {
                template: '#app',
                data: function() {
                    var data = {
                        form: {},
                        rules: {
                            keyword: [
                                {required: true, message: $lang("Keyword")},
                            ], 
                            hits: [
                                {required: true, message: $lang("Hits")},
                            ], 
                            weigh: [
                                {required: true, message: $lang("Weigh")},
                            ], 
                            status: [
                                {required: true, message: $lang("Status")},
                            ], 
                        },
                        btnLoading: false,
                        tabs: {"base":"Base"},
                        activeTab: 'base'
                    }
                    return Yi.event.listen(EventPrefix + 'Data', data);
                },
                mounted: function() {
                    self = this;
                    this.reset();
                },
                methods: {
                    reset: function() {
                        var form = {
                            keyword: '',
                            hits: 0,
                            weigh: 10,
                            status: 1,
                        };
                        this.form = Yi.event.listen(EventPrefix + 'ResetForm', form);
                    },
                }
            };
            return Yi.event.listen(EventPrefix + 'Option', option);
        },

        edit: function() {
            Yi.vue.mixin(Mixins.form);
            var self;
            var option = {
                template: '#app',
                data: function() {
                    var data = {
                        form: {
                            keyword: '',
                            hits: 0,
                            weigh: 10,
                            status: 1,
                        },
                        rules: {
                            keyword: [
                                {required: true, message: $lang("Keyword")},
                            ], 
                            hits: [
                                {required: true, message: $lang("Hits")},
                            ], 
                            weigh: [
                                {required: true, message: $lang("weigh")},
                            ], 
                            status: [
                                {required: true, message: $lang("Status")},
                            ], 
                        },
                        btnLoading: false,
                        id: '',
                        tabs: {"base":"Base"},
                        activeTab: 'base'
                    }
                    return Yi.event.listen(EventPrefix + 'Data', data);
                },
                mounted: function() {
                    self = this;
                    this.id = Yi.getQuery('id');
                    this.init();
                },
            };
            return Yi.event.listen(EventPrefix + 'Option', option);
        },
    };
    return Yi.event.listen(EventPrefix + 'Action', Action);
});