define([], function() {
    var Action = {
        
        index: function() {
            Yi.vue.mixin(Mixins.table);
            var self;
            var columns = [
                { title: $lang('Id'), dataIndex: 'id', key: 'id', align: 'center', customRender: Yi.render.html, }, 
                { title: $lang('Product'), dataIndex: 'product', key: 'product', width: '480px', customRender: function(val, row, index, column) {
                    var el = '<div style="max-width: 480px;">'
                    el += '<div>用户：' + row.user.nickname + '</div>';
                    el += '<div class="line-1" title="' + row.product.title + '"><a href="/shop/index/product/info.html?id=' + row.product_id + '" target="_blank">' + row.product.title + '</a></div>';

                    el += '<div>评价内容：</div>';
                    if (row.images) {
                        el += '<div style="white-space: nowrap;">'
                        var images = row.images.split(',');
                        for (var i = 0; i < images.length; i ++) {
                            var img = images[i];
                            if (!img) continue;
                            el += '<img style="width: 60px;height: 60px;margin: 2px;border-radius: 5px;" src="' + img + '" />';
                        }
                        el += '</div>';
                    }
                    el += '<div style="margin: 5px 0; padding: 10px 5px;background: #eee;border-radius: 5px;">' + row.content + '</div>';
                    el += '</div>';
                    return Yi.render.html(val, row, index, column, el);
                }},
                { title: $lang('Images'), dataIndex: 'images', key: 'images', align: 'center', customRender: Yi.render.images, visible: false}, 
                { title: $lang('Content'), dataIndex: 'content', key: 'content', align: 'center', customRender: Yi.render.html, visible: false}, 
                { title: $lang('Star'), dataIndex: 'star', key: 'star', align: 'center', customRender: function(val, row, index, column) {
                    var h = $vm.$createElement;
                    var nodes = [];
                    for (var i = 0; i < val; i ++) {
                        nodes.push(h('a-icon', {
                            props: { type: 'star', theme: 'filled' },
                            style: { color: 'red', margin: '2px' }
                        }))
                    }
                    return h('div', {}, nodes);
                }, }, 
                { title: $lang('ShopProductSku Price'), dataIndex: 'product_sku_price', key: 'product_sku.price', align: 'center', customRender: Yi.render.html},
                { title: $lang('Status'), dataIndex: 'status', key: 'status', align: 'center', customRender: Yi.render.switch, }, 
                { title: $lang('Created At'), dataIndex: 'created_at', key: 'created_at', align: 'center', customRender: Yi.render.date, }, 
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
                            page: 1, page_size: 10, order: "id DESC",
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
                        var params = Yi.event.listen(EventPrefix + 'InitParams', _.merge(this.query, Yi.getQuery()));
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
    };
    return Yi.event.listen(EventPrefix + 'Action', Action);
});