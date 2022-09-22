
<template>
    <div class="shop-jump">
        <div
            class="nav-label pointer"
            :style="{ minWidth: labelWidth }"
            @click="show = true"
            v-html="text"
        ></div>
        <a-modal
            v-model="show"
            ok-text="确定"
            cancel-text="取消"
            @ok="show = false"
        >
            <a-form :form="option">
                <a-form-item label="跳转类型">
                    <div>
                        <a-select v-model="option.type" @change="typeChange">
                            <a-select-option
                                v-for="item in types"
                                :key="item.type"
                                :value="item.type"
                                >{{ item.title }}
                            </a-select-option>
                        </a-select>
                    </div>
                </a-form-item>
                <a-form-item label="跳转目标">
                    <a-input
                        type="text"
                        :value="option.target"
                        v-if="option.type == 0"
                        class="form-control"
                        readonly
                    >
                    </a-input>

                    <div class="input-group" v-if="option.type == 1">
                        <a-input
                            :value="showText"
                            class="form-control"
                            type="text"
                            readonly
                            placeholder="请选择商品"
                        >
                        </a-input>
                        <input type="hidden" v-model="option.target" />
                        <div class="input-group-append">
                            <a-button @click="handleChooseProduct" type="danger"
                                >选择</a-button
                            >
                        </div>
                    </div>

                    <div class="input-group" v-if="option.type == 2">
                        <a-input
                            :value="showText"
                            class="form-control"
                            type="text"
                            readonly
                            placeholder="请选择商品分类"
                        >
                        </a-input>
                        <input type="hidden" v-model="option.target" />
                        <div class="input-group-append">
                            <a-button
                                @click="handleChooseProductCategory"
                                type="danger"
                                >选择</a-button
                            >
                        </div>
                    </div>

                    <div class="input-group" v-if="option.type == 3">
                        <a-select
                            v-model="option.target"
                            @change="showText = option.target"
                        >
                            <a-select-option
                                v-for="(item, index) in pages"
                                :key="index"
                                :value="item.path"
                            >
                                <span>{{ item.title }}</span>
                                <span style="color: #888; font-size: 12px">{{
                                    item.path
                                }}</span>
                            </a-select-option>
                        </a-select>
                    </div>

                    <a-input
                        v-if="option.type == 4"
                        @input="showText = option.target"
                        placeholder="请输入链接地址，如https://www.baidu.com"
                        v-model="option.target"
                    ></a-input>

                    <div class="input-group" v-if="option.type == 5">
                        <a-input
                            :value="showText"
                            class="form-control"
                            type="text"
                            readonly
                            placeholder="请选择文章"
                        >
                        </a-input>
                        <input type="hidden" v-model="option.target" />
                        <div class="input-group-append">
                            <a-button @click="handleChooseArticle" type="danger"
                                >选择</a-button
                            >
                        </div>
                    </div>

                    <div class="input-group" v-if="option.type == 6">
                        <a-select
                            v-model="option.target"
                            @change="showText = option.target"
                        >
                            <a-select-option
                                v-for="(item, index) in tabBars"
                                :key="index"
                                :value="item.path"
                            >
                                <span>{{ item.title }}</span>
                                <span style="color: #888; font-size: 12px">{{
                                    item.path
                                }}</span>
                            </a-select-option>
                        </a-select>
                    </div>

                    <div class="input-group" v-if="option.type == 7">
                        <a-select
                            v-model="option.target"
                            @change="showText = option.target"
                        >
                            <a-select-option
                                v-for="(item, index) in otherPages"
                                :key="index"
                                :value="item.url"
                                :label="item.title"
                            >
                                <span>{{ item.title }}</span>
                                <span style="color: #888; font-size: 12px">{{
                                    item.url
                                }}</span>
                            </a-select-option>
                        </a-select>
                        <a-input
                            style="margin-top: 10px"
                            placeholder="参数"
                            v-model="option.params"
                        ></a-input>
                        <a-checkbox
                            :default-checked="true"
                            v-model="option.token"
                            >带Token</a-checkbox
                        >
                    </div>

                    <div class="input-group" v-if="option.type == 8">
                        <a-input
                            :value="showText"
                            class="form-control"
                            type="text"
                            readonly
                            placeholder="请选择微页面"
                        >
                        </a-input>
                        <input type="hidden" v-model="option.target" />
                        <div class="input-group-append">
                            <a-button @click="handleChooseMPage" type="danger"
                                >选择</a-button
                            >
                        </div>
                    </div>
                </a-form-item>
            </a-form>
        </a-modal>
    </div>
</template>
<script>
export default {
    name: "shop-jump",
    data: function () {
        return {
            option: {},
            show: false,
            showText: "",
            pages: [],
            tabBars: [],
            otherPages: [],
            types: [
                { type: "0", title: "无跳转" },
                { type: "3", title: "跳转到页面" },
                { type: "6", title: "跳转到底部导航" },
                { type: "8", title: "跳转到微页面" },
                { type: "1", title: "跳转到商品" },
                { type: "2", title: "跳转到分类" },
                { type: "5", title: "跳转到文章" },
                { type: "4", title: "跳转到外链" },
                { type: "7", title: "拓展页面" },
            ],
        };
    },
    props: {
        value: {},
        labelWidth: "",
    },
    mounted: function () {
        this.option = this.value;
        this.init();
    },
    computed: {
        text: function () {
            if (!this.option) return ''
            for (var i = 0; i < this.types.length; i++) {
                var item = this.types[i];
                if (this.option.type == item.type) {
                    return "[ " + item.title + " ] " + this.showText;
                }
            }
        },
    },
    watch: {
        value: {
            handler: function (val) {
                this.option = val;
                this.init();
            },
            deep: true,
        },
        option: {
            handler: function (val) {
                this.$emit("input", val);
            },
            deep: true,
        },
    },
    methods: {
        init: function () {
            if (!this.option) return;
            var type = this.option.type;
            this.showText = "";
            var target = this.option.target;
            if (!target) return;
            var url = "";
            switch (parseInt(type)) {
                case 1: {
                    url = "/shop/admin/product/index";
                    this.getData(url, target, "title");
                    break;
                }
                case 2: {
                    url = "/shop/admin/category/index";
                    this.getData(url, target, "name");
                    break;
                }
                case 3: {
                    this.showText = this.option.target;
                    this.getPages();
                    break;
                }
                case 4: {
                    this.showText = this.option.target;
                    break;
                }
                case 5: {
                    url = "/shop/admin/article/index";
                    this.getData(url, target, "title");
                    break;
                }
                case 6: {
                    this.showText = this.option.target;
                    this.getTabBars();
                    break;
                }
                case 7: {
                    this.showText = this.option.target;
                    this.getExpandPages();
                    break;
                }
                case 8: {
                    url = "/shop/admin/page_template/index";
                    this.getData(url, target, "title");
                    break;
                }
            }
        },
        getData: function (url, target, textField) {
            var that = this;
            this.$http
                .get(url, { params: { id: target } })
                .then(function (data) {
                    that.showText = data.data[0][textField];
                });
        },
        typeChange: function () {
            this.option.target = "";
            this.showText = "";
            switch (parseInt(this.option.type)) {
                case 3: {
                    this.getPages();
                    break;
                }
                case 6: {
                    this.getTabBars();
                    break;
                }
                case 7: {
                    this.getExpandPages();
                    break;
                }
            }
        },
        getPages: function () {
            var that = this;
            if (this.pages.length) return this.pages;
            $.ajax({
                url: "/shop/admin/index/getPages",
                success: function (res) {
                    that.pages = res.data;
                },
            });
        },
        getTabBars: function () {
            var that = this;
            if (this.tabBars.length) return this.tabBars;
            $.ajax({
                url: "/shop/admin/index/getTabBars",
                success: function (res) {
                    that.tabBars = res.data;
                },
            });
        },
        getExpandPages: function () {
            var that = this;
            if (this.otherPages.length) return this.otherPages;
            $.ajax({
                url: "/shop/admin/index/getExpandPages",
                success: function (res) {
                    that.otherPages = res.data;
                },
            });
        },
        handleChooseProductCategory: function () {
            var that = this;
            Yi.open(
                {
                    title: "选择分类",
                    content: "/shop/admin/category/select",
                },
                function (data) {
                    that.option.target = data.data.id;
                    that.showText = data.data.name;
                }
            );
        },
        handleChooseProduct: function () {
            var that = this;
            Yi.open(
                {
                    title: "选择商品",
                    content: "/shop/admin/product/select",
                },
                function (data) {
                    that.option.target = data.data.id;
                    that.showText = data.data.title;
                }
            );
        },
        handleChooseArticle: function () {
            var that = this;
            Yi.open(
                {
                    title: "选择文章",
                    content: "/shop/admin/article/select",
                },
                function (data) {
                    that.option.target = data.data.id;
                    that.showText = data.data.title;
                }
            );
        },
        handleChooseMPage: function () {
            var self = this;
            Yi.open(
                {
                    title: "选择微页面",
                    content: "/shop/admin/page_template/select",
                },
                function (data) {
                    self.option.target = data.data.id;
                    self.showText = data.data.title;
                }
            );
        },
    },
};
</script>