

<template>
    <div class="shop-product-list-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <div class="form form-horizontal">
            <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">
                <a-tabs v-model="tabName" type="border-card">
                    <a-tab-pane tab="基础" key="base">
                        <a-form-item label="商品个数">
                            <a-input v-model="form.count"></a-input>
                        </a-form-item>
                        <a-form-item label="布局方式">
                            <a-select
                                v-model="form.layouttype"
                                @change="layoutChange"
                            >
                                <a-select-option
                                    v-for="(item, index) in arr"
                                    :key="index"
                                    :value="item.type"
                                >
                                    {{ item.title }}</a-select-option
                                >
                            </a-select>
                        </a-form-item>
                        <a-form-item label="类别">
                            <a-select v-model="form.type" @change="typeChange">
                                <a-select-option
                                    v-for="(item, index) in types"
                                    :key="index"
                                    :value="item.type"
                                >
                                    {{ item.title }}</a-select-option
                                >
                            </a-select>
                        </a-form-item>
                        <a-form-item label="商品分类" v-if="form.type == 0">
                            <a-select
                                v-model="form.target"
                                @change="typeChange"
                            >
                                <a-select-option
                                    v-for="(item, index) in categories"
                                    :key="index"
                                    :value="item.id"
                                    ><span v-html="item.name"></span>
                                </a-select-option>
                            </a-select>
                        </a-form-item>

                        <a-form-item label="商品标签" v-if="form.type == 1">
                            <a-select
                                v-model="form.target"
                                @change="typeChange"
                            >
                                <a-select-option
                                    v-for="(item, index) in tags"
                                    :key="index"
                                    :value="item.id"
                                >
                                    {{ item.title }}</a-select-option
                                >
                            </a-select>
                        </a-form-item>
                    </a-tab-pane>
                    <a-tab-pane tab="其他" key="adv">
                        <a-form-item label="外边距">
                            <a-input v-model="form.margin"></a-input>
                        </a-form-item>
                        <a-form-item label="背景色">
                            <yi-color-picker
                                v-model="form.background"
                            ></yi-color-picker>
                        </a-form-item>
                        <a-form-item label="子项外边距">
                            <a-input v-model="form.itemMargin"></a-input>
                        </a-form-item>
                        <a-form-item label="子项内边距">
                            <a-input v-model="form.itemPadding"></a-input>
                        </a-form-item>
                        <a-form-item label="子项边框">
                            <a-input v-model="form.itemBorder"></a-input>
                        </a-form-item>
                        <a-form-item label="子项边框弧度">
                            <a-input v-model="form.itemBorderRadius"></a-input>
                        </a-form-item>
                        <a-form-item label="图片宽度">
                            <a-input v-model="form.imgWidth"></a-input>
                        </a-form-item>
                        <a-form-item label="图片高度">
                            <a-input v-model="form.imgHeight"></a-input>
                        </a-form-item>
                    </a-tab-pane>
                </a-tabs>
            </a-form>
        </div>
    </div>
</template>
<script>
import SettingMixin from "../mixins/SettingMixin";
export default {
    name: "shop-product-list-setting",
    mixins: [SettingMixin],
    data: function () {
        return {
            form: {},
            rows: [],
            loading: false,
            categories: [],
            tags: [],
            types: [
                { type: "0", title: "按分类" },
                { type: "1", title: "按标签" },
            ],
            arr: [
                { type: "0", title: "纵向两列" },
                { type: "1", title: "纵向一列" },
                { type: "2", title: "横向排列" },
            ],
        };
    },
    watch: {
        "form.type": function (v) {
            this.typeChange();
        },
    },
    mounted: function () {
        this.typeChange();
    },
    methods: {
        layoutChange: function (val) {
            switch (parseInt(val)) {
                case 0: {
                    this.form.itemBorder = "1px solid #ddd";
                    this.form.imgWidth = "150px";
                    this.form.imgHeight = "150px";
                    break;
                }
                case 1: {
                    this.form.itemBorder = "";
                    this.form.imgWidth = "90px";
                    this.form.imgHeight = "90px";
                    break;
                }
                case 2: {
                    this.form.itemBorder = "";
                    this.form.imgWidth = "90px";
                    this.form.imgHeight = "90px";
                    break;
                }
            }
        },
        typeChange: function () {
            switch (this.form.type) {
                case "0": {
                    this.searchCategory();
                    break;
                }
                case "1": {
                    this.searchTags();
                }
            }
        },
        searchCategory: function () {
            var that = this;
            that.loading = true;
            this.$http
                .get("/shop/admin/category/tree_list")
                .then(function (data) {
                    that.loading = false;
                    that.categories = that.parseGategories(data);
                });
        },
        searchTags: function () {
            var that = this;
            this.$http
                .get("/shop/admin/product_tag/all")
                .then(function (data) {
                    that.tags = data;
                });
        },
        parseGategories: function (rows) {
            var result = [];
            for (var i = 0; i < rows.length; i++) {
                var item = rows[i];
                item.title = item.name.replace(item.spacer, "");
                result.push(item);
            }
            return result;
        },
    },
};
</script>