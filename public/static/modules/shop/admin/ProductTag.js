define([], function() {
    
    var Action = {
        index: function() {
            Yi.vue.mixin(Mixins.table);
            var self;
            var columns = [
                { title: 'id', dataIndex: 'id', key: 'id', align: 'center', customRender: Yi.render.html, }, 
                { title: '名称', dataIndex: 'title', key: 'title', align: 'center', customRender: Yi.render.html, }, 
                { title: '描述', dataIndex: 'desc', key: 'desc', align: 'center', customRender: Yi.render.html, }, 
                { title: '排序', dataIndex: 'weigh', key: 'weigh', align: 'center', customRender: Yi.render.html, }, 
                { title: '创建时间', dataIndex: 'created_at', key: 'created_at', align: 'center', customRender: Yi.render.date, }, 
                { title: '更新时间', dataIndex: 'updated_at', key: 'updated_at', align: 'center', customRender: Yi.render.date, }, 
                
                { title: '操作',key: 'action$', align: 'center',scopedSlots: { customRender: 'action' },},
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
                        query: __.merge({
                            page: 1, page_size: 10, order: "id DESC",
                            where: {}
                        }, Yi.getQuery()),
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
                        var params = Yi.event.listen(EventPrefix + 'InitParams', this.query);
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
                            title: [
                                {"required":true,"message":"请输入名称","trigger":"blur"},
                            ], 
                            desc: [
                                {"required":true,"message":"请输入描述","trigger":"blur"},
                            ], 
                            weigh: [
                                {"required":true,"message":"请输入排序","trigger":"blur"},
                            ], 
                        },
                        btnLoading: false,
                        tabs: {"base":"基本"},
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
                            title: '',
                            desc: '',
                            weigh: 10000,
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
                            title: '',
                            desc: '',
                            weigh: 10000,
                        },
                        rules: {
                            title: [
                                {"required":true,"message":"请输入名称","trigger":"blur"},
                            ], 
                            desc: [
                                {"required":true,"message":"请输入描述","trigger":"blur"},
                            ], 
                            weigh: [
                                {"required":true,"message":"请输入排序","trigger":"blur"},
                            ], 
                        },
                        btnLoading: false,
                        id: '',
                        tabs: {"base":"基本"},
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
        select: function() { 
            Yi.vue.mixin(Mixins.table);
            Yi.vue.mixin(Mixins.select);
            var self;           
            var columns = [
                { title: 'id', dataIndex: 'id', key: 'id', align: 'center', customRender: Yi.render.html, }, 
                { title: '名称', dataIndex: 'title', key: 'title', align: 'center', customRender: Yi.render.html, }, 
                { title: '描述', dataIndex: 'desc', key: 'desc', align: 'center', customRender: Yi.render.html, }, 
                { title: '排序', dataIndex: 'weigh', key: 'weigh', align: 'center', customRender: Yi.render.html, }, 
                { title: '创建时间', dataIndex: 'created_at', key: 'created_at', align: 'center', customRender: Yi.render.date, }, 
                { title: '更新时间', dataIndex: 'updated_at', key: 'updated_at', align: 'center', customRender: Yi.render.date, }, 
                
                { title: '操作',key: 'action$',scopedSlots: { customRender: 'action' },},
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
                            page: 1, page_size: 10, order: "id DESC"
                        },
                        multiple: false
                    }
                    return Yi.event.listen(EventPrefix + 'Data', data);
                },
                mounted: function() {
                    self = this;
                    this.init();
                    this.multiple = Yi.getQuery('multiple');
                },
                methods: {
                    init: function() {                        
                        self.loading = true;
                        var params = Yi.event.listen(EventPrefix + 'InitParams', this.query);
                        this.$http.get(get_url('select'), {params: params}).then(function(data) {
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
    };

    return Yi.event.listen(EventPrefix + 'Action', Action);
});