define([], function() {
    var that;
    function valuecopy(obj) {
        return JSON.parse(JSON.stringify(obj));
    }
    var Action = {
        index: function() {
            Yi.vue.mixin(Mixins.table);
            var self;
            var columns = [
                { title: 'id', dataIndex: 'id', key: 'id', align: 'center', customRender: Yi.render.html, }, 
                { title: '标题', dataIndex: 'title', key: 'title', align: 'center', customRender: Yi.render.html, }, 
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
                computed: {
                    ids: function() {
                        var result = [];
                        for (var i = 0; i < this.selectedRows.length; i ++) {
                            result.push(this.selectedRows[i].id);
                        }
                        return result;
                    },
                    c_columns: function() {
                        var result = [];
                        for (var i = 0; i < this.columns.length; i ++) {
                            var item = this.columns[i];
                            if (item.visible || item.visible == undefined) result.push(item);
                        }
                        return result;
                    }
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
                    handleCopy: function(item) {
                        this.$http.post('/shop/admin/page_template/copy', { id: item.id }).then(function() {
                            self.init();
                        })
                    },
                    handlePageChange: function(page) {
                        this.query.page = page;
                        this.init();
                    },
                    onSelectChange: function(selectedRowKeys, selectedRows) {
                        this.selectedRowKeys = selectedRowKeys;
                        this.selectedRows = selectedRows;
                    },
                    handleAdd: function() {                        
                        Yi.open({
                            title: '添加',
                            content: get_url('add')
                        }, function(data) {
                            if (data) self.init();
                        })
                    },
                    handleEdit: function(row) {
                        Yi.open({
                            title: '编辑',
                            content: get_url('edit') + '?id=' + row.id
                        });
                    },
                    handleDesign: function(row) {
                        top.Yi.open({
                            title: '设计',
                            area: ['90%', '95%'],
                            content: get_url('design') + '?id=' + row.id
                        }, function(data) {
                            if (data) self.init();
                        });
                    },
                    handleDeleteBatch: function() {                        
                        self.del(this.ids, function() {
                            self.selectedRowKeys = [];
                            self.selectedRows = [];
                            self.init();
                        });
                    },
                    handleDelete: function(row) {                        
                        self.del([row.id], function() {
                            self.init();
                        });
                    },
                }
            };
            return Yi.event.listen(EventPrefix + 'Option', option);
        },
        design: function() {
            var componentList = {
                common: {
                    title: '通用',
                    list: [
                        {
                            title: '幻灯片', 
                            type: 'shop-slide', img: '/modules/shop/image/slide.png',
                            option: {
                                height: '150px', background: '#ffffff', margin: '5px 0', padding: '0', 
                                list: [
                                    {url: '', background: '#ffffff', nav: {type: '0', target: '', params: ''}}
                                ]
                            }
                        },
                        {
                            title: '标题栏', 
                            type: 'shop-title-bar', img: '/modules/shop/image/title.png',
                            option: {
                                mode: '1', title: '标题', titleColor: '#888', titleSize: 13,
                                padding: 5, backgroundColor: '#f5f5f5',
                                nav: {type: '0', target: '', params: ''}
                            }
                        },
                        {
                            title: '文本', 
                            type: 'shop-text', img: '/modules/shop/image/text.png',
                            option: {
                                content: '这里输入文字', margin: '0',
                                padding: '0 10px', background: '#fff',
                                size: 13, color: '#000', indent: 10, textAlign: 'left',
                                nav: {type: '0', target: '', params: ''}
                            }
                        },
                        {
                            title: '搜索框', 
                            type: "shop-search", img: '/modules/shop/image/search.png',
                            option: {
                                backgroundColor: '#ffffff', margin: '10px 0px', padding: '10px 5px',
                                borderColor: '#f5f5f5',
                                textAlign: 'left', borderRadius: 25,
                                placeholder: '请输入关键词',

                            }
                        },
                        {
                            title: '通告', 
                            type: 'shop-notice',
                            img: '/modules/shop/image/notice.png',
                            option: {
                                margin: '5px 0', padding: '0',color: '#de8c17', background: '#fffbe8', interval: 3, list: [
                                    {title: '', nav: {type: '0', target: '', params: ''}}
                                ]
                            }
                        },
                        {
                            title: '宫格', 
                            type: 'shop-grid', 
                            img: '/modules/shop/image/grid.png',
                            option: {
                                margin: '5px 0', padding: '0', background: '#ffffff', count: '5',itemPadding: '5px', borderRadius: '5px',
                                imgBorder: '', imgBorderRadius: '50%', imgBoxShadow: '',  imgWidth: '44px', imgHeight: '44px', imgPadding: '5px',
                                itemTitleSize: '12px', itemTitleColor: '#222',
                                itemContentSize: '16px', itemContentColor: '#222', itemConentWeigh: '700',
                                list: [
                                    {image: '', content: '', title: '', nav: {type: '0', target: '', params: ''}}
                                ]
                            }
                        },
                        {
                            title: '列表', 
                            type: 'shop-list', 
                            img: '/modules/shop/image/list.png',
                            option: {
                                padding: '10px',
                                background: '#fff',
                                list: [
                                    {
                                        title: '', icon: '', color: '#ef393c', tips: '', nav: {type: '0', target: '', params: ''}
                                    }
                                ]
                            }
                        },
                        {
                            title: '图片墙', 
                            type: 'shop-images', 
                            img: '/modules/shop/image/image-list.png',
                            option: {
                                mode: '1',
                                list: []
                            }
                        }
                    ]
                },
                product: {
                    title: '商品',
                    list: [
                        {
                            type: 'shop-product-list', title: '商品列表', 
                            img: '/modules/shop/image/product.png',
                            option: {
                                margin: '5px 0', background: '#ffffff', itemMargin: '5px', itemPadding: '5px', itemBorder: '1px solid #ddd', itemBorderRadius: '5px', imgBorderRadius: '5px', count: 10,
                                imgWidth: '150px', imgHeight: '150px', 
                                category_id: null, title: '', layouttype: '0', type: '0', target: null
                            }
                        },
                    ]
                },
                user: {
                    title: '个人中心',
                    list: [
                        {
                            type: 'shop-user-head', title: '头部组件',
                            img: '/modules/shop/image/user-head.png',
                            option: {
                                image: '', background: '#fff', color: '#555'
                            }
                        },
                        {
                            type: 'shop-user-order', title: '订单组件',
                            img: '/modules/shop/image/order.png',
                            option: {
                                margin: '0 10px'
                            }
                        },
                        {
                            type: 'shop-user-logout', title: '用户退出',
                            img: '/modules/shop/image/logout.png',
                            option: {
                                margin: '10px',
                                color: '#ff1e02',
                                paddiing: '0 10px'
                            }
                        }
                    ]
                }
            };
            var option = {
                template: '#app',
                data: function() {
                    return {
                        content: {
                            option: {
                                title: '',
                                background: '#f5f5f5'
                            },
                            list: []
                        },
                        componentList: Yi.event.listen(EventPrefix + 'ComponentList', componentList),
                        id: null,
                        title: null,
                        showEdit: false,
                        activeIndex: -1,
                        option: {},
                        overIndex: -1,
                        dialogShow: false,
                        templates: [],
                        extendData: {},
                        list: [],
                        tmpActiveIndex: -1,
                        pageSet: true
                    };
                },
                mounted: function() {
                    this.init();
                    $("#vloading").hide();
                },
                watch: {
                    'content.list': {
                        deep: true,
                        handler: function(val) {
                            var that = this;
                            var cb = function() {
                                var index = 0;
                                var extendData = {};
                                for (var i = 0; i < that.$children.length; i ++) {
                                    var parent = that.$children[i].$el.parentNode;
                                    if (/ preview-item /.test(' ' + parent.className + ' ')) {
                                        var item ;
                                        if (!that.extendData[index]) item = {};
                                        else item = that.extendData[index];
                                        item.height = parent.clientHeight;
                                        extendData[index] = item;
                                        index ++;
                                    }
                                }
                                that.$set(that, 'extendData', extendData);
                            }
                            this.$nextTick(cb);
                        }
                    },
                    activeIndex: function(v) {
                        if (v == -1) this.pageSet = true;
                        else this.pageSet = false;
                        this.getOption();
                    }
                },
                methods: {
                    init: function() {
                        var that = this;
                        this.$http.get(get_url('design?id=' + Yi.getQuery('id'))).then(function(data) {
                            that.initData(data);
                        });
                    },
                    initList: function() {
                        this.getOption();
                    },
                    add: function() {
                        this.id = null;
                        this.title = '';
                        this.activeIndex = -1;
                        this.getOption();
                    },
                    save: function(cb) {
                        var self = this;
                        this.saveTemplate(this.form(), function(data) {
                            self.$message.success("操作成功");
                            cb && typeof cb == 'function' && cb(data);
                        })
                    },
                    saveAndExit: function() {
                        var self = this;
                        this.save(function() {
                            Yi.closeSelf({id: self.id, title: self.title, content: self.content});
                        });
                    },
                    saveTemplate: function(form, cb) {
                        this.$http.post(get_url('design'), {form: form}).then(function(data) {
                            typeof cb == 'function' && cb(data);
                        });
                    },
                    initData: function(data) {
                        this.id = data.id;
                        this.title = data.title;
                        if (data.content) this.content = typeof data.content == 'string' ? JSON.parse(data.content) : data.content;
                        this.getOption();
                    },
                    form: function() {
                        return {
                            id: this.id,
                            title: this.title,
                            content: JSON.stringify(this.content)
                        }
                    },
                    read: function() {
                        var that = this;
                        this.dialogShow = true;
                        this.$http.get(get_url('templates')).then(function(data) {
                            that.templates = data;
                        });
                    },
                    componentClick: function(item, index) {
                        this.content.list.push(valuecopy({type: item.type, option: item.option,title: item.title}));
                        this.activeIndex = this.content.list.length - 1;
                        this.overIndex = -1;
                        this.getOption();
                    },
                    itemClick: function(item, index) {
                        this.activeIndex = index;
                        this.getOption();
                    },
                    handleSettingChange: function(option) {
                        this.$set(this.content.list[this.activeIndex], 'option', option);
                        this.$emit('option-change', option, this.content.list[this.activeIndex].type);
                    },
                    handleMouseOver: function(item, index) {
                        this.overIndex = index;
                    },
                    handleMouseLeave: function(item, index) {
                        this.overIndex = -1;
                    },
                    deleteHandle: function(index) {
                        this.content.list.splice(index, 1);
                        if (this.content.list.length == 0) this.activeIndex = -1;
                        else this.activeIndex = 0;
                        this.initList();
                    },
                    handleCollapseClick: function(index) {
                        this.$set(this.extendData[index], 'is_collapsed', !this.extendData[index].is_collapsed);
                    },
                    getOption: function() {
                        this.option = this.activeIndex == -1 || this.content.list.length == 0 ? {} : valuecopy(this.content.list[this.activeIndex].option);
                    },
                    handleDragStart: function() {
                        this.tmpActiveIndex = this.activeIndex;
                        this.activeIndex = -1;
                    },
                    handleDragEnd: function() {
                        this.activeIndex = this.tmpActiveIndex;
                    },
                    handlePageHeadClick: function() {
                        this.pageSet = true;
                        this.activeIndex = -1;
                    },
                }
            };
            return option;
        },
        add: function() {
            var option = {
                template: '#app',
                data: function() {
                    return {
                        form: {},
                        rules: {
                            title: [
                                {"required":true,"message":"请输入模板说明","trigger":"blur"},
                            ], 
                            content: [
                                {"required":true,"message":"请输入模板内容","trigger":"blur"},
                            ], 
                        },
                        btnLoading: false
                    }
                },
                mounted: function() {
                    that = this;
                    this.reset();
                },
                methods: {
                    onSubmit: function() {
                        this.$refs.ruleForm.validate(function(valid) {
                            if (valid) that.submit();
                            else return false;
                        });
                    },
                    submit: function() {
                        this.btnLoading = true;
                        this.$http.post(get_url('add'), {form: this.form}, {loading: true}).then(function(data) {
                            Yi.closeSelf(data);
                            that.btnLoading = false;
                        }).catch(function() {
                            that.btnLoading = false;
                        });
                    },
                    reset: function() {
                        this.form = {
                            title: '',
                            content: '',
                        };
                    },
                    onCancel: function() {
                        Yi.closeSelf();
                    }
                }
            };
            return option;
        },
        edit: function() {
            var option = {
                template: '#app',
                data: function() {
                    return {
                        form: {
                            title: '',
                            content: '',
                        },
                        rules: {
                            title: [
                                {"required":true,"message":"请输入模板说明","trigger":"blur"},
                            ], 
                            content: [
                                {"required":true,"message":"请输入模板内容","trigger":"blur"},
                            ], 
                        },
                        btnLoading: false,
                        id: ''
                    }
                },
                mounted: function() {
                    that = this;
                    this.id = Yi.getQuery('id');
                    this.init();
                },
                methods: {
                    onSubmit: function() {
                        this.$refs.ruleForm.validate(function(valid) {
                            if (valid) that.submit();
                            else return false;
                        });
                    },
                    submit: function() {
                        this.btnLoading = true;
                        this.$http.post(get_url('edit'), {id: this.id, form: this.form}, {loading: true}).then(function(data) {
                            Yi.closeSelf(data);
                            that.btnLoading = false;
                        }).catch(function() {
                            that.btnLoading = false;
                        });
                    },
                    init: function() {
                        this.$http.get(get_url('edit'), {params: {id: this.id}}).then(function(data) {
                            that.form = data;
                        });
                    },
                    onCancel: function() {
                        Yi.closeSelf();
                    }
                }
            };
            return option;
        },
        select: function() { 
            var self;           
            var columns = [
                { title: 'id', dataIndex: 'id', key: 'id', customRender: Yi.render.html, }, 
                { title: '标题', dataIndex: 'title', key: 'title', customRender: Yi.render.html, }, 
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
                computed: {
                    ids: function() {
                        var result = [];
                        for (var i = 0; i < this.selectedRows.length; i ++) {
                            result.push(this.selectedRows[i].id);
                        }
                        return result;
                    }
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
                    handlePageChange: function(page) {
                        this.query.page = page;
                        this.init();
                    },
                    onSelectChange: function(selectedRowKeys, selectedRows) {
                        this.selectedRowKeys = selectedRowKeys;
                        this.selectedRows = selectedRows;
                    },
                    handleSelect: function() {
                        Yi.closeSelf({
                            multiple: true,
                            data: {
                                ids: this.ids,
                                rows: this.selectedRows
                            }
                        });
                    },
                    handleSelectOne: function(row) {
                        var data = this.multiple ? {
                            multiple: true,
                            data: {
                                ids: [row.id],
                                rows: [row]
                            }
                        } : {
                            multiple: false,
                            data: row
                        }
                        Yi.closeSelf(data);
                    }
                }
            };
            return Yi.event.listen(EventPrefix + 'Option', option);
        },
        api: {
            url: function(action) {
                return base_url + action;
            },
        }
    };

    return Action;
});