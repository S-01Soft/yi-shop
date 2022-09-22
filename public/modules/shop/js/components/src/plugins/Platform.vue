
<template>
    <a-form :label-col="{ span: 2 }" :wrapper-col="{ span: 20 }">
        <a-form-item label="平台">
            <div
                class="shop-platform-item"
                @click="handleClick(item, index)"
                v-for="(item, index) in list"
                :key="index"
                :class="data.indexOf(item.value) == -1 ? '' : 'disabled'"
            >
                {{ item.label }}
            </div>
        </a-form-item>
    </a-form>
</template>
<script>
var blackList = [];
var list = [
    { label: "H5", value: "H5" },
    { label: "微信公众号", value: "WX-H5" },
    { label: "微信小程序", value: "MP-WEIXIN" },
    { label: "头条小程序", value: "MP-TOUTIAO" },
    { label: "百度小程序", value: "MP-BAIDU" },
    { label: "支付宝小程序", value: "MP-ALIPAY" },
    { label: "IOS App", value: "IOS" },
    { label: "Android App", value: "ANDROID" },
];
export default {
    name: "shop-platform",
    data: function () {
        return {
            list: list,
            indeterminate: false,
            checkAll: true,
            data: [],
        };
    },
    props: {
        value: {
            default: blackList,
        },
    },
    watch: {
        value: {
            deep: true,
            handler: function (v) {
                this.data = v;
            },
        },
        data: {
            deep: true,
            handler: function (v) {
                this.$emit("input", v);
            },
        },
    },
    mounted: function () {
        this.data = this.value;
    },
    methods: {
        handleClick: function (item) {
            var index = this.data.indexOf(item.value);
            if (index == -1) this.data.push(item.value);
            else this.data.splice(index, 1);
        },
    },
};
</script>

<style scoped>
.shop-platform-item {
    display: inline-block;
    padding: 5px 10px;
    line-height: 150%;
    margin: 5px;
    background-color: #00a600;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}
.shop-platform-item.disabled {
    color: #fff;
    background-color: #b8b8b8;
}
</style>