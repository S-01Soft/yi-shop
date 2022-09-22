import Product from './Product';
import Grid from './Grid';
import GridSetting from './GridSetting';
import Icons from './Icons';
import Images from './Images';
import ImagesSetting from './ImagesSetting';
import Jump from './Jump';
import List from './List';
import ListSetting from './ListSetting';
import Notice from './Notice';
import NoticeSetting from './NoticeSetting';
import PhoneFrame from './PhoneFrame';
import Platform from './Platform';
import ProductList from './ProductList';
import ProductListSetting from './ProductListSetting';
import Search from './Search';
import SearchSetting from './SearchSetting';
import Slide from './Slide';
import SlideSetting from './SlideSetting';
import Text from './Text';
import TextSetting from './TextSetting';
import TitleBar from './TitleBar';
import TitleBarSetting from './TitleBarSetting';
import UserHead from './UserHead';
import UserHeadSetting from './UserHeadSetting';
import UserLogout from './UserLogout';
import UserLogoutSetting from './UserLogoutSetting';
import UserOrder from './UserOrder';
import UserOrderSetting from './UserOrderSetting';
import PageSetting from './PageSetting';
import SettingMixin from '../mixins/SettingMixin';

const components = [
    Product, Grid, GridSetting, Icons, Images, ImagesSetting, Jump, List, ListSetting, Notice, NoticeSetting, PhoneFrame, Platform,
    ProductList, ProductListSetting, Search, SearchSetting, Slide, SlideSetting, Text, TextSetting, TitleBar, TitleBarSetting, 
    UserHead, UserHeadSetting, UserLogout, UserLogoutSetting, UserOrder, UserOrderSetting, PageSetting
];

const install = function(Vue) {
    components.forEach(component => {
        Vue.component(component.name, component);
    });
}

export {
    install,
    SettingMixin
}

