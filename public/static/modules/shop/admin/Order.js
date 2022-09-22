define([], function() {
    
    var Action = {
        index: function() {
            Yi.vue.mixin(Mixins.table);
            var self;
            var columns = [
                { title: 'id', dataIndex: 'id', key: 'id', align: 'center', customRender: Yi.render.html, }, 
                { title: '订单编号', dataIndex: 'order_sn', key: 'order_sn', align: 'center', customRender: Yi.render.html, }, 
                { title: '用户', dataIndex: 'user_id', key: 'user_id', align: 'center', customRender: Yi.render.html, }, 
                { title: '是否支付', dataIndex: 'is_pay', key: 'is_pay', align: 'center', customRender: Yi.render.html, }, 
                { title: '支付时间', dataIndex: 'pay_time', key: 'pay_time', align: 'center', customRender: Yi.render.date, }, 
                { title: '是否发货', dataIndex: 'is_delivery', key: 'is_delivery', align: 'center', customRender: Yi.render.html, }, 
                { title: '发货时间', dataIndex: 'delivery', key: 'delivery', align: 'center', customRender: Yi.render.date, }, 
                { title: '是否收货', dataIndex: 'is_received', key: 'is_received', align: 'center', customRender: Yi.render.html, enums: { 0: '未收货', 1: '已收货' },  customRender: Yi.render.enum, }, 
                { title: '收货时间', dataIndex: 'received_time', key: 'received_time', align: 'center', customRender: Yi.render.date, }, 
                { title: '快递公司编号', dataIndex: 'express_code', key: 'express_code', align: 'center', customRender: Yi.render.html, }, 
                { title: '快递单号', dataIndex: 'express_no', key: 'express_no', align: 'center', customRender: Yi.render.html, }, 
                { title: '订单状态', dataIndex: 'status', key: 'status', align: 'center', customRender: Yi.render.html, }, 
                { title: '联系人', dataIndex: 'contactor', key: 'contactor', align: 'center', customRender: Yi.render.html, }, 
                { title: '联系电话', dataIndex: 'contactor_phone', key: 'contactor_phone', align: 'center', customRender: Yi.render.html, }, 
                { title: '送货地址', dataIndex: 'address', key: 'address', align: 'center', customRender: Yi.render.html, }, 
                { title: '运费', dataIndex: 'delivery_price', key: 'delivery_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '订单金额', dataIndex: 'order_price', key: 'order_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '应付金额', dataIndex: 'pay_price', key: 'pay_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '支付平台', dataIndex: 'pay_type', key: 'pay_type', align: 'center', customRender: Yi.render.html, }, 
                { title: '支付方式', dataIndex: 'pay_method', key: 'pay_method', align: 'center', customRender: Yi.render.html, }, 
                { title: '商品总额', dataIndex: 'products_price', key: 'products_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '优惠金额', dataIndex: 'discount_price', key: 'discount_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '已付金额', dataIndex: 'payed_price', key: 'payed_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '买家是否评价', dataIndex: 'buyer_review', key: 'buyer_review', align: 'center', customRender: Yi.render.html, }, 
                { title: '卖家是否评价', dataIndex: 'saler_review', key: 'saler_review', align: 'center', customRender: Yi.render.html, }, 
                { title: '买家备注', dataIndex: 'saler_remark', key: 'saler_remark', align: 'center', customRender: Yi.render.html, }, 
                { title: '用户备注', dataIndex: 'remark', key: 'remark', align: 'center', customRender: Yi.render.html, }, 
                { title: '团购状态', dataIndex: 'groupon_status', key: 'groupon_status', align: 'center', customRender: Yi.render.html, }, 
                { title: '售后买家留言', dataIndex: 'after_buyer_remark', key: 'after_buyer_remark', align: 'center', customRender: Yi.render.html, }, 
                { title: '售后卖家留言', dataIndex: 'after_saler_remark', key: 'after_saler_remark', align: 'center', customRender: Yi.render.html, }, 
                { title: '售后状态', dataIndex: 'after_sale_status', key: 'after_sale_status', align: 'center', customRender: Yi.render.html, }, 
                { title: '退款金额', dataIndex: 'refund_fee', key: 'refund_fee', align: 'center', customRender: Yi.render.html, }, 
                { title: '积分抵扣', dataIndex: 'score_price', key: 'score_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '使用积分', dataIndex: 'use_score', key: 'use_score', align: 'center', customRender: Yi.render.html, }, 
                { title: '余额抵扣', dataIndex: 'money_price', key: 'money_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '用户', dataIndex: 'user_nickname', key: 'user.nickname', align: 'center', customRender: Yi.render.html},
                { title: '快递公司编号', dataIndex: 'expressCodeC_name', key: 'express_code_c.name', align: 'center', customRender: Yi.render.html},
                { title: '订单类型', dataIndex: 'order_type', key: 'order_type', align: 'center', customRender: Yi.render.html},
                { title: '创建时间', dataIndex: 'created_at', key: 'created_at', align: 'center', customRender: Yi.render.date, }, 
                { title: '最后更新', dataIndex: 'updated_at', key: 'updated_at', align: 'center', customRender: Yi.render.date, }, 
                { title: '删除时间', dataIndex: 'deleted_at', key: 'deleted_at', align: 'center', customRender: Yi.render.date, }, 
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
                            order_sn: [
                                {"required":true,"message":"请输入订单编号","trigger":"blur"},
                            ], 
                            contactor: [
                                {"required":true,"message":"请输入联系人","trigger":"blur"},
                            ], 
                            contactor_phone: [
                                {"required":true,"message":"请输入联系电话","trigger":"blur"},
                            ], 
                            score_price: [
                                {"required":true,"message":"请输入score_price","trigger":"blur"},
                            ], 
                            use_score: [
                                {"required":true,"message":"请输入use_score","trigger":"blur"},
                            ], 
                            money_price: [
                                {"required":true,"message":"请输入money_price","trigger":"blur"},
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
                            order_sn: '',
                            user_id: '',
                            is_pay: 0,
                            pay_time: '',
                            is_delivery: 0,
                            delivery: '',
                            is_received: 0,
                            received_time: '',
                            express_code: '',
                            express_no: '',
                            status: 0,
                            contactor: '',
                            contactor_phone: '',
                            address: '',
                            delivery_price: 0,
                            order_price: 0,
                            pay_price: 0,
                            pay_type: '',
                            pay_method: '',
                            products_price: 0,
                            discount_price: 0,
                            payed_price: 0,
                            buyer_review: 0,
                            saler_review: 0,
                            saler_remark: '',
                            remark: '',
                            order_type: 0,
                            groupon_status: 0,
                            order_sn_re: '',
                            after_buyer_remark: '',
                            after_saler_remark: '',
                            after_sale_status: 0,
                            refund_fee: 0,
                            score_price: 0,
                            use_score: 0,
                            money_price: 0,
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
                            order_sn: '',
                            user_id: '',
                            is_pay: 0,
                            pay_time: '',
                            is_delivery: 0,
                            delivery: '',
                            is_received: 0,
                            received_time: '',
                            express_code: '',
                            express_no: '',
                            status: 0,
                            contactor: '',
                            contactor_phone: '',
                            address: '',
                            delivery_price: 0,
                            order_price: 0,
                            pay_price: 0,
                            pay_type: '',
                            pay_method: '',
                            products_price: 0,
                            discount_price: 0,
                            payed_price: 0,
                            buyer_review: 0,
                            saler_review: 0,
                            saler_remark: '',
                            remark: '',
                            order_type: 0,
                            groupon_status: 0,
                            order_sn_re: '',
                            after_buyer_remark: '',
                            after_saler_remark: '',
                            after_sale_status: 0,
                            refund_fee: 0,
                            score_price: 0,
                            use_score: 0,
                            money_price: 0,
                        },
                        rules: {
                            order_sn: [
                                {"required":true,"message":"请输入订单编号","trigger":"blur"},
                            ], 
                            contactor: [
                                {"required":true,"message":"请输入联系人","trigger":"blur"},
                            ], 
                            contactor_phone: [
                                {"required":true,"message":"请输入联系电话","trigger":"blur"},
                            ], 
                            score_price: [
                                {"required":true,"message":"请输入score_price","trigger":"blur"},
                            ], 
                            use_score: [
                                {"required":true,"message":"请输入use_score","trigger":"blur"},
                            ], 
                            money_price: [
                                {"required":true,"message":"请输入money_price","trigger":"blur"},
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
            var self;           
            var columns = [
                { title: 'id', dataIndex: 'id', key: 'id', align: 'center', customRender: Yi.render.html, }, 
                { title: '订单编号', dataIndex: 'order_sn', key: 'order_sn', align: 'center', customRender: Yi.render.html, }, 
                { title: '用户', dataIndex: 'user_id', key: 'user_id', align: 'center', customRender: Yi.render.html, }, 
                { title: '是否支付', dataIndex: 'is_pay', key: 'is_pay', align: 'center', customRender: Yi.render.html, }, 
                { title: '支付时间', dataIndex: 'pay_time', key: 'pay_time', align: 'center', customRender: Yi.render.date, }, 
                { title: '是否发货', dataIndex: 'is_delivery', key: 'is_delivery', align: 'center', customRender: Yi.render.html, }, 
                { title: '发货时间', dataIndex: 'delivery', key: 'delivery', align: 'center', customRender: Yi.render.date, }, 
                { title: '是否收货', dataIndex: 'is_received', key: 'is_received', align: 'center', customRender: Yi.render.html, enums: { 0: '未收货', 1: '已收货' },  customRender: Yi.render.enum, }, 
                { title: '收货时间', dataIndex: 'received_time', key: 'received_time', align: 'center', customRender: Yi.render.date, }, 
                { title: '快递公司编号', dataIndex: 'express_code', key: 'express_code', align: 'center', customRender: Yi.render.html, }, 
                { title: '快递单号', dataIndex: 'express_no', key: 'express_no', align: 'center', customRender: Yi.render.html, }, 
                { title: '订单状态', dataIndex: 'status', key: 'status', align: 'center', customRender: Yi.render.html, }, 
                { title: '联系人', dataIndex: 'contactor', key: 'contactor', align: 'center', customRender: Yi.render.html, }, 
                { title: '联系电话', dataIndex: 'contactor_phone', key: 'contactor_phone', align: 'center', customRender: Yi.render.html, }, 
                { title: '送货地址', dataIndex: 'address', key: 'address', align: 'center', customRender: Yi.render.html, }, 
                { title: '运费', dataIndex: 'delivery_price', key: 'delivery_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '订单金额', dataIndex: 'order_price', key: 'order_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '应付金额', dataIndex: 'pay_price', key: 'pay_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '支付平台', dataIndex: 'pay_type', key: 'pay_type', align: 'center', customRender: Yi.render.html, }, 
                { title: '支付方式', dataIndex: 'pay_method', key: 'pay_method', align: 'center', customRender: Yi.render.html, }, 
                { title: '商品总额', dataIndex: 'products_price', key: 'products_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '优惠金额', dataIndex: 'discount_price', key: 'discount_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '已付金额', dataIndex: 'payed_price', key: 'payed_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '买家是否评价', dataIndex: 'buyer_review', key: 'buyer_review', align: 'center', customRender: Yi.render.html, }, 
                { title: '卖家是否评价', dataIndex: 'saler_review', key: 'saler_review', align: 'center', customRender: Yi.render.html, }, 
                { title: '买家备注', dataIndex: 'saler_remark', key: 'saler_remark', align: 'center', customRender: Yi.render.html, }, 
                { title: '创建时间', dataIndex: 'created_at', key: 'created_at', align: 'center', customRender: Yi.render.date, }, 
                { title: '最后更新', dataIndex: 'updated_at', key: 'updated_at', align: 'center', customRender: Yi.render.date, }, 
                { title: '删除时间', dataIndex: 'deleted_at', key: 'deleted_at', align: 'center', customRender: Yi.render.date, }, 
                { title: '用户备注', dataIndex: 'remark', key: 'remark', align: 'center', customRender: Yi.render.html, }, 
                { title: '订单类型', dataIndex: 'order_type', key: 'order_type', align: 'center', customRender: Yi.render.html, }, 
                { title: '团购状态', dataIndex: 'groupon_status', key: 'groupon_status', align: 'center', customRender: Yi.render.html, }, 
                { title: 'out_trade_no', dataIndex: 'order_sn_re', key: 'order_sn_re', align: 'center', customRender: Yi.render.html, }, 
                { title: '售后买家留言', dataIndex: 'after_buyer_remark', key: 'after_buyer_remark', align: 'center', customRender: Yi.render.html, }, 
                { title: '售后卖家留言', dataIndex: 'after_saler_remark', key: 'after_saler_remark', align: 'center', customRender: Yi.render.html, }, 
                { title: '售后状态', dataIndex: 'after_sale_status', key: 'after_sale_status', align: 'center', customRender: Yi.render.html, }, 
                { title: '退款金额', dataIndex: 'refund_fee', key: 'refund_fee', align: 'center', customRender: Yi.render.html, }, 
                { title: 'score_price', dataIndex: 'score_price', key: 'score_price', align: 'center', customRender: Yi.render.html, }, 
                { title: 'use_score', dataIndex: 'use_score', key: 'use_score', align: 'center', customRender: Yi.render.html, }, 
                { title: 'money_price', dataIndex: 'money_price', key: 'money_price', align: 'center', customRender: Yi.render.html, }, 
                { title: '用户', dataIndex: 'user_nickname', key: 'user.nickname', align: 'center', customRender: Yi.render.html},
                { title: '快递公司编号', dataIndex: 'expressCodeC_name', key: 'express_code_c.name', align: 'center', customRender: Yi.render.html},
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
        show: function() {
            var option = {
                template: '#app',
                methods: {
                    handleSubmit: function(v, k, e) {
                        e.load();
                        this.$http.post(get_url('edit_address'), {form: {id: Yi.getQuery('id'), k: k, v: v}}).then(function() {
                            e.finish();
                            location.reload();
                        });
                    }
                }
            };
            return Yi.event.listen(EventPrefix + 'Option', option);
        },
    };

    return Yi.event.listen(EventPrefix + 'Action', Action);
});