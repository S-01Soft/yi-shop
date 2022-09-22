require.config({
    paths: {
        sortablejs: '../modules/shop/js/libs/sortable',
        vuedraggable: '../modules/shop/js/libs/vuedraggable.umd.min',
        ShopComponents: '../modules/shop/js/components/dist/components'
    },
    shim: {
        ShopComponents: ['YiComponents']
    }
});
Yi.require('ShopComponents');
define(['vue', 'ShopComponents', 'vuedraggable'], function(Vue, ShopComponents, vuedraggable) {
    Vue.component('vuedraggable', vuedraggable);
    Vue.use(ShopComponents);
    window.setting_mixins = ShopComponents.SettingMixin;
});