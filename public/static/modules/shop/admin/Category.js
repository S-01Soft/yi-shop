define([], function() {
    var Action = {
        index: function() {
            Yi.vue.mixin(Mixins.table);
            var self;
            var columns = [
                { title: 'id', dataIndex: 'id', key: 'id', customRender: Yi.render.html, }, 
                { title: '分类名称', dataIndex: 'name', key: 'name', customRender: Yi.render.html, }, 
                { title: '图标', dataIndex: 'image', key: 'image', customRender: Yi.render.image, }, 
                { title: '排序', dataIndex: 'sort', key: 'sort', customRender: Yi.render.html, }, 
                { title: '启用', dataIndex: 'status', key: 'status', customRender: Yi.render.switch, }, 
                { title: '创建时间', dataIndex: 'created_at', key: 'created_at', customRender: Yi.render.date, }, 
                { title: '更新时间', dataIndex: 'updated_at', key: 'updated_at', customRender: Yi.render.date, }, 
                { title: '上级分类', dataIndex: 'parent_name', key: 'parent.name', customRender: Yi.render.html},
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
                        this.$http.get(get_url('tree_list?type=1'), {params: params}).then(function(data) {
                            self.loading = false;
                            data = Yi.event.listen(EventPrefix + 'Init', data);
                            
                            self.data = data;
                            
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
                            parent_id: [
                                {"required":true,"message":"请输入上级分类","trigger":"blur"},
                            ], 
                            name: [
                                {"required":true,"message":"请输入分类名称","trigger":"blur"},
                            ], 
                            sort: [
                                {"required":true,"message":"请输入排序","trigger":"blur"},
                            ], 
                            status: [
                                {"required":true,"message":"请输入启用","trigger":"blur"},
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
                            parent_id: 0,
                            name: '',
                            image: '',
                            sort: 1000,
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
                            parent_id: 0,
                            name: '',
                            image: '',
                            sort: 1000,
                            status: 1,
                        },
                        rules: {
                            parent_id: [
                                {"required":true,"message":"请输入上级分类","trigger":"blur"},
                            ], 
                            name: [
                                {"required":true,"message":"请输入分类名称","trigger":"blur"},
                            ], 
                            sort: [
                                {"required":true,"message":"请输入排序","trigger":"blur"},
                            ], 
                            status: [
                                {"required":true,"message":"请输入启用","trigger":"blur"},
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
                { title: '分类名称', dataIndex: 'name', key: 'name', customRender: Yi.render.html, }, 
                { title: '图标', dataIndex: 'image', key: 'image', customRender: Yi.render.image, }, 
                { title: '排序', dataIndex: 'sort', key: 'sort', customRender: Yi.render.html, }, 
                { title: '启用', dataIndex: 'status', key: 'status', customRender: Yi.render.switch, }, 
                { title: '创建时间', dataIndex: 'created_at', key: 'created_at', customRender: Yi.render.date, }, 
                { title: '更新时间', dataIndex: 'updated_at', key: 'updated_at', customRender: Yi.render.date, }, 
                { title: '上级分类', dataIndex: 'parent_name', key: 'parent.name', customRender: Yi.render.html},
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

    return Action;
});