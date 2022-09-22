define([], function() {
    var Action = {
        index: function() {
            Yi.vue.mixin(Mixins.table);
            var self;
            var columns = [
                { title: 'id', dataIndex: 'id', key: 'id', customRender: Yi.render.html, }, 
                { title: '标题', dataIndex: 'title', key: 'title', customRender: Yi.render.html, }, 
                { title: '描述', dataIndex: 'description', key: 'description', customRender: Yi.render.html, }, 
                { title: '图片', dataIndex: 'image', key: 'image', customRender: Yi.render.image, }, 
                { title: '上架', dataIndex: 'on_sale', key: 'on_sale', customRender: Yi.render.switch, }, 
                { title: '销售数量', dataIndex: 'sold_count', key: 'sold_count', customRender: Yi.render.html, }, 
                { title: '评价数量', dataIndex: 'review_count', key: 'review_count', customRender: Yi.render.html, }, 
                { title: '创建时间', dataIndex: 'created_at', key: 'created_at', customRender: Yi.render.date, }, 
                { title: '创建者', dataIndex: 'create_user', key: 'create_user', customRender: Yi.render.html, }, 
                { title: '商品类型', dataIndex: 'product_type', key: 'product_type', customRender: Yi.render.html, enums: { 1: '实物商品', 2: '虚拟商品' },  customRender: Yi.render.enum, }, 
                { title: '分类', dataIndex: 'category_name', key: 'category.name', customRender: Yi.render.html},
                { title: '单位', dataIndex: 'unit_name', key: 'unit.name', customRender: Yi.render.html},
                { title: '服务', dataIndex: 'serviceTagsC_title', key: 'service_tags_c.title', customRender: Yi.render.html},
                { title: '创建者', dataIndex: 'createUserC_nickname', key: 'create_user_c.nickname', customRender: Yi.render.html},
                { title: '运费模板', dataIndex: 'deliveryTpl_title', key: 'delivery_tpl.title', customRender: Yi.render.html},
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
                                {"required":true,"message":"请输入标题","trigger":"blur"},
                            ], 
                            description: [
                                {"required":true,"message":"请输入描述","trigger":"blur"},
                            ], 
                            image: [
                                {"required":true,"message":"请输入图片","trigger":"blur"},
                            ], 
                            content: [
                                {"required":true,"message":"请输入内容","trigger":"blur"},
                            ], 
                            on_sale: [
                                {"required":true,"message":"请输入上架","trigger":"blur"},
                            ], 
                            rating: [
                                {"required":true,"message":"请输入折扣","trigger":"blur"},
                            ], 
                            sold_count: [
                                {"required":true,"message":"请输入销售数量","trigger":"blur"},
                            ], 
                            review_count: [
                                {"required":true,"message":"请输入评价数量","trigger":"blur"},
                            ], 
                            price: [
                                {"required":true,"message":"请输入销售价","trigger":"blur"},
                            ], 
                            score_persent: [
                                {"required":true,"message":"请输入积分抵现比例","trigger":"blur"},
                            ], 
                            product_type: [
                                {"required":true,"message":"请输入商品类型","trigger":"blur"},
                            ], 
                        },
                        btnLoading: false,
                        tabs: {"base":"基础","adv":"属性\/库存"},
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
                            category_id: '',
                            title: '',
                            description: '',
                            seo_title: '',
                            seo_keywords: '',
                            seo_description: '',
                            image: '',
                            content: '',
                            on_sale: 1,
                            rating: 0,
                            unit_id: 0,
                            service_tags: [],
                            delivery_tpl_id: 0,
                            stock_type: 0,
                            score_persent: 0,
                            product_type: 1,
                            auto_receive_time: 0,
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
                            category_id: '',
                            title: '',
                            description: '',
                            seo_title: '',
                            seo_keywords: '',
                            seo_description: '',
                            image: '',
                            content: '',
                            on_sale: 1,
                            rating: 0,
                            unit_id: '',
                            service_tags: [],
                            delivery_tpl_id: 1,
                            stock_type: 0,
                            score_persent: 0,
                            product_type: 1,
                            auto_receive_time: 0,
                        },
                        rules: {
                            title: [
                                {"required":true,"message":"请输入标题","trigger":"blur"},
                            ], 
                            description: [
                                {"required":true,"message":"请输入描述","trigger":"blur"},
                            ], 
                            image: [
                                {"required":true,"message":"请输入图片","trigger":"blur"},
                            ], 
                            content: [
                                {"required":true,"message":"请输入内容","trigger":"blur"},
                            ], 
                            on_sale: [
                                {"required":true,"message":"请输入上架","trigger":"blur"},
                            ], 
                            rating: [
                                {"required":true,"message":"请输入折扣","trigger":"blur"},
                            ], 
                            sold_count: [
                                {"required":true,"message":"请输入销售数量","trigger":"blur"},
                            ], 
                            review_count: [
                                {"required":true,"message":"请输入评价数量","trigger":"blur"},
                            ], 
                            price: [
                                {"required":true,"message":"请输入销售价","trigger":"blur"},
                            ], 
                            score_persent: [
                                {"required":true,"message":"请输入积分抵现比例","trigger":"blur"},
                            ], 
                            product_type: [
                                {"required":true,"message":"请输入商品类型","trigger":"blur"},
                            ], 
                        },
                        btnLoading: false,
                        id: '',
                        tabs: {"base":"基础","adv":"属性\/库存"},
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
                { title: '标题', dataIndex: 'title', key: 'title', customRender: Yi.render.html, }, 
                { title: '描述', dataIndex: 'description', key: 'description', customRender: Yi.render.html, }, 
                { title: '图片', dataIndex: 'image', key: 'image', customRender: Yi.render.image, }, 
                { title: '上架', dataIndex: 'on_sale', key: 'on_sale', customRender: Yi.render.switch, }, 
                { title: '销售数量', dataIndex: 'sold_count', key: 'sold_count', customRender: Yi.render.html, }, 
                { title: '评价数量', dataIndex: 'review_count', key: 'review_count', customRender: Yi.render.html, }, 
                { title: '创建时间', dataIndex: 'created_at', key: 'created_at', customRender: Yi.render.date, }, 
                { title: '创建者', dataIndex: 'create_user', key: 'create_user', customRender: Yi.render.html, }, 
                { title: '商品类型', dataIndex: 'product_type', key: 'product_type', customRender: Yi.render.html, enums: { 1: '实物商品', 2: '虚拟商品' },  customRender: Yi.render.enum, }, 
                { title: '分类', dataIndex: 'category_name', key: 'category.name', customRender: Yi.render.html},
                            { title: '单位', dataIndex: 'unit_name', key: 'unit.name', customRender: Yi.render.html},
                            { title: '服务', dataIndex: 'serviceTagsC_title', key: 'service_tags_c.title', customRender: Yi.render.html},
                            { title: '创建者', dataIndex: 'createUserC_nickname', key: 'create_user_c.nickname', customRender: Yi.render.html},
                            { title: '运费模板', dataIndex: 'deliveryTpl_id', key: 'delivery_tpl.id', customRender: Yi.render.html},
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
        }
    };

    return Yi.event.listen(EventPrefix + 'Action', Action);
});