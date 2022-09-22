define([], function() {
    var Action = {
        index: function() {
            Yi.vue.mixin(Mixins.table);
            var self;
            var columns = [
                { title: 'id', dataIndex: 'id', key: 'id', customRender: Yi.render.html, }, 
                { title: '名称', dataIndex: 'name', key: 'name', customRender: Yi.render.html, }, 
                { title: '符号', dataIndex: 'code', key: 'code', customRender: Yi.render.html, }, 
                { title: '是否默认', dataIndex: 'is_default', key: 'is_default', customRender: Yi.render.switch, }, 
                
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
                            name: [
                                {"required":true,"message":"请输入名称","trigger":"blur"},
                            ], 
                            code: [
                                {"required":true,"message":"请输入符号","trigger":"blur"},
                            ], 
                            is_default: [
                                {"required":true,"message":"请输入是否默认","trigger":"blur"},
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
                            name: '',
                            code: '',
                            is_default: 0,
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
                            name: '',
                            code: '',
                            is_default: 0,
                        },
                        rules: {
                            name: [
                                {"required":true,"message":"请输入名称","trigger":"blur"},
                            ], 
                            code: [
                                {"required":true,"message":"请输入符号","trigger":"blur"},
                            ], 
                            is_default: [
                                {"required":true,"message":"请输入是否默认","trigger":"blur"},
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
                { title: 'id', dataIndex: 'id', key: 'id', customRender: Yi.render.html, }, 
                { title: '名称', dataIndex: 'name', key: 'name', customRender: Yi.render.html, }, 
                { title: '符号', dataIndex: 'code', key: 'code', customRender: Yi.render.html, }, 
                { title: '是否默认', dataIndex: 'is_default', key: 'is_default', customRender: Yi.render.switch, }, 
                
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