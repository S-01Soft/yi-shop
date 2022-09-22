require.config({
    paths: {
        san: '../modules/cms/js/san.min',
    },
    map: {
        '*': {
            tpl: '../modules/cms/js/tpl'
        }
    }
});
define(['san', 'tpl!../modules/shop/template/address.html', 'css!../modules/shop/css/shop-address.css'], function(san, template) {
    var self;
    var MyApp = san.defineComponent({
        template: template,
        initData: function() {
            return {
                list: [],
                area: [],
                m_id: '',
                row: {},
                form: {},
				provinceList: [],
				cityList: [],
				areaList: [],
                currentProvince: 0,
				currentCity: 0,
				currentArea: 0,
				currentValue: {},
            }
        },
        attached: function() {
            self = this;
            self.data.set('m_id', 'm-' + Math.random())
            this.resetForm();
            this.getAddress();
        },
        resetForm: function() {
            this.data.set('form', {
                id: null, contactor_name: '', phone: '', address_id: 0, street: '', is_default: 0, address: ''
            });
        },
        getArea: function() {
            if (this.data.get('area').length) return;
            Yi.ajax({
                url: '/shop/api/index/getArea'
            }, function(data) {
                self.data.set('area', data);
                self.getProvince();
                return false;
            })
        },
        getProvince: function() {
            let result = []
            this.data.get('area').forEach((item, index) => {
                if (item.level == 1) result.push(item)
            })
            this.data.set('provinceList', result);
            this.changeProvinceChildren()
        },        
        changeProvinceChildren() {
            let result = []
            let province = this.data.get('provinceList')[this.data.get('currentProvince')];
            this.data.get('area').forEach((item, index) => {
                if (item.level == 2 && item.pid == province.id) result.push(item)
            })
            this.data.set('cityList', result);
            this.data.set('currentCity', 0);
            this.data.set('currentArea', 0);
            this.changeCityChildren();
        },
        changeCityChildren() {
            let result = []
            let city = this.data.get('cityList')[this.data.get('currentCity')];
            this.data.get('area').forEach((item, index) => {
                if (item.level == 3 && item.pid == city.id) result.push(item)
            })
            this.data.set('areaList', result);
            this.data.set('currentArea', 0);
        },
        getAddress: function() {
            Yi.ajax({
                url: '/shop/api/user/getAddress',
                loading: false
            }, function(data) {
                self.data.set('list', data);
                return false;
            });
        },
        handleAdd: function() {
            this.resetForm();
            this.data.set('currentProvince', 0);
            this.changeProvinceChildren()
            this.modal('show');
        },
        delete: function(item) {
            $.confirm('确定删除吗？', function() {
                Yi.ajax({
                    url: '/shop/api/user/delAddress',
                    data: {
                        address_id: item.id
                    }
                }, function() {
                    self.getAddress();
                })
            })
        },
        edit: function(item) {
            var form = {
                id: item.id,
                contactor_name: item.contactor_name,
                phone: item.phone,
                address_id: item.address_id,
                street: item.street,
                is_default: item.is_default,
                address: item.address
            }
            this.data.set('form', form);
            this.initArea(item.address_id);
            this.modal('show');
        },
        initArea: function(id) {
            var timer = setInterval(function() {
                if (self.data.get('provinceList').length) {
                    var list = self.data.get('area');
                    var area = Yi.util.findRows(list, 'id', id, true);
                    var city = Yi.util.findRows(list, 'id', area.pid, true);
                    var province = Yi.util.findRows(list, 'id', city.pid, true);
                    var province_id = Yi.util.findRows(self.data.get('provinceList'), 'id', province.id);
                    self.data.set('currentProvince', province_id);
                    self.changeProvinceChildren();
                    self.data.set('currentCity', Yi.util.findRows(self.data.get('cityList'), 'id', city.id));
                    self.changeCityChildren();
                    self.data.set('currentArea', Yi.util.findRows(self.data.get('areaList'), 'id', area.id));
                    clearInterval(timer)
                }
            }, 50);
        },
        modal: function(action) {
            this.getArea();
            var id = this.data.get('m_id');
            el = document.getElementById(id);
            return $(el).modal(action);
        },
        submit: function() {
            var form = this.data.get('form');
            var area = this.data.get('areaList')[this.data.get('currentArea')];
            form.address_id = area.id;
            form.address = area.mergename;
            Yi.ajax({
                url: '/shop/api/user/editAddress',
                data: form
            }, function() {
                self.getAddress();
                self.modal('hide')
            })
        }
    });
    return function(id, data) {
        var app = new MyApp({
            data: data
        });
        app.attach(document.getElementById(id));
        return app;
    }
});