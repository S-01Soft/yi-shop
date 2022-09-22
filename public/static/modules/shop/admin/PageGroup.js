define([], function() {
    var Action = {
        
        index: function() {
            var self;
            var option = {
                template: '#app',
                data: function() {
                    return {
                        groups: [],
                        tpl: {
                            show: false,
                            action: 'add',
                            form: {
                                id: 0,
                                title: ''
                            }
                        },
                        tplData: {
                            options: {}
                        },
                        activeTmp: null
                    }
                },
                mounted: function() {
                    self = this;
                    this.getTemplates();
                    $("#vloading").hide();
                },
                methods: {
                    reload: function() {
                        this.groups = [];
                        this.getTemplates();
                    },
                    handleShowAddTemplate: function() {
                        this.tpl.action = 'add'
                        this.tpl.show = true;
                    },
                    handleEditTemplate: function() {
                        var data = {}
                        if (this.tpl.action == 'add') {
                            data = {
                                form: {
                                    title: this.tpl.form.title,
                                    content: ''
                                }
                            }
                        } else {
                            data = {
                                id: this.tpl.form.id,
                                form: {
                                    title: this.tpl.form.title
                                }
                            }
                        }
                        this.saveTemplate(data, function(data) {
                            self.tpl.show = false;
                            self.tpl.form.title = '';
                            self.reload();
                        })
                    },
                    handleShowEditTemplate: function(item) {
                        this.tpl.form.id = item.id
                        this.tpl.form.title = item.title
                        this.tpl.action = 'edit'
                        this.tpl.show = true;
                    },
                    editTemplate: function(item) {
                        top.Yi.open({
                            title: '编辑模板',
                            content: '/shop/admin/page_template/design?id=' + item.content[self.tplData[item.id].activeTab], 
                            area: ['95%', '95%']
                        }, function(data) {
                            self.reload();
                        });
                    },
                    saveTemplate: function(form, cb) {
                        this.$http.post(get_url(this.tpl.action), form).then(function(data) {
                            cb && typeof cb == 'function' && cb(data);
                        });
                    },
                    useTemplate: function(id) {
                        this.$http.post(get_url('useTemplate'), {id: id}).then(function() {                            
                            self.getTemplates();
                        });
                    },
                    delTemplate: function(id, index) {
                        var self = this;
                        this.$confirm({
                            title: '提示',
                            content: '确定删除该模板吗？',
                            okText: '确定',
                            cancelText: '取消',
                            onOk: function() {
                                self.$http.post(get_url('delete'), {id: id}).then(function(data) {
                                    self.groups.splice(index, 1);
                                })
                            }
                        });
                    },
                    getTemplates: function () {
                        this.dialogShow = true;
                        this.$http.get(get_url('index')).then(function(data) {
                            var tplData = {};
                            for (var i = 0; i < data.data.length; i ++) {
                                var item = data.data[i];
                                if (!tplData[item.id]) tplData[item.id] = {};
                                tplData[item.id].activeTab = 'home';
                            }
                            tplData.options = {};
                            self.tplData = tplData;
                            self.groups = data.data;
                            self.initOptions();
                        });
                    },
                    initOptions: function(ids) {
                        ids = ids || this.getTemplateIds();
                        var where = {
                            id: ['in', ids]
                        };
                        this.$http.get('/shop/admin/page_template/all?where=' + encodeURI(JSON.stringify(where))).then(function(data) {
                            for (var i = 0; i < data.length; i ++) {
                                self.$set(self.tplData.options, data[i].id, JSON.parse(data[i].content))
                            }
                        });
                    },
                    getTemplateIds: function() {
                        var ids = [];
                        for (var i = 0; i < this.groups.length; i ++) {
                            ids = ids.concat(Object.values(this.groups[i].content));
                        }
                        return ids;
                    },
                    handleBindPage: function(item, index) {
                        Yi.open({
                            title: '绑定页面',
                            content: '/shop/admin/page_template/select'
                        }, function(data) {
                            console.log(data)
                            self.$set(self.groups[index].content, self.tplData[item.id].activeTab, data.data.id);
                            self.tplData[item.id].options = JSON.parse(data.data.content);
                            self.$http.post(get_url('edit'), {id: item.id, form: self.groups[index]}).then(function() {
                                self.reload();
                            });
                        })
                    }
                }
            };
            return option;
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
                                {required: true, message: $lang(["Input", "Title"])},
                            ], 
                            content: [
                                {required: true, message: $lang(["Input", "Content"])},
                            ], 
                        },
                        btnLoading: false,
                        tabs: {"base":$lang('Base')},
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
                            content: {},
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
                            content: {},
                        },
                        rules: {
                            title: [
                                {required: true, message: $lang(["Input", "Title"])},
                            ], 
                            content: [
                                {required: true, message: $lang(["Input", "Content"])},
                            ], 
                        },
                        btnLoading: false,
                        id: '',
                        tabs: {"base":$lang('Base')},
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