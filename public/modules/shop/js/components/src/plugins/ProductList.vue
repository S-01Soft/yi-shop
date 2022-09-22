
<template>
    <div
        class="shop-product-list clear"
        :style="{ background: option.background, margin: option.margin }"
    >
        <div v-if="data.length != 0">
            <div v-if="option.layouttype == 0" class="type-0 clear">
                <div
                    class="product-item"
                    v-for="(item, index) in data"
                    :key="index"
                    style="width: 50%; display: inline-block"
                >
                    <div>
                        <div
                            class="product"
                            :style="{
                                margin: option.itemMargin,
                                padding: option.itemPadding,
                                border: option.itemBorder,
                                borderRadius: option.itemBorderRadius,
                            }"
                        >
                            <div style="margin: auto;max-height: 120px;">
                                <img
                                    style="width: 100%"
                                    :style="{
                                        borderRadius: option.imgBorderRadius,
                                    }"
                                    :src="item.image[0]"
                                    alt=""
                                />
                            </div>
                            <div class="clamp line-1">{{ item.title }}</div>
                            <div style="color: red">
                                ￥{{ item.skus[0].price }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="option.layouttype == 1" class="type-1">
                <div
                    class="product-item"
                    v-for="(item, index) in data"
                    :key="index"
                >
                    <div>
                        <div
                            class="product clear"
                            :style="{
                                margin: option.itemMargin,
                                padding: option.itemPadding,
                                border: option.itemBorder,
                                borderRadius: option.itemBorderRadius,
                            }"
                        >
                            <img
                                :style="{
                                    width: option.imgWidth,
                                    height: option.imgHeight,
                                    borderRadius: option.imgBorderRadius,
                                }"
                                :src="item.image[0]"
                            />
                            <div
                                class="text"
                                :style="{ height: option.imgHeight }"
                                style="max-width: 190px"
                            >
                                <div>
                                    <div class="clamp line-1">
                                        {{ item.title }}
                                    </div>
                                    <div style="color: red">
                                        ￥{{ item.skus[0].price }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="option.layouttype == 2" class="type-2">
                <div
                    class="product-item"
                    v-for="(item, index) in data"
                    :key="index"
                >
                    <div>
                        <div
                            class="product"
                            :style="{
                                margin: option.itemMargin,
                                padding: option.itemPadding,
                                border: option.itemBorder,
                                borderRadius: option.itemBorderRadius,
                            }"
                        >
                            <div>
                                <img
                                    :style="{
                                        borderRadius: option.imgBorderRadius,
                                        width: option.imgWidth,
                                        height: option.imgHeight || '150px',
                                    }"
                                    :src="item.image[0]"
                                />
                            </div>
                            <div
                                class="line-1"
                                :style="{ width: option.imgWidth }"
                                style="
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                "
                            >
                                {{ item.title }}
                            </div>
                            <div style="color: red">
                                ￥{{ item.skus[0].price }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="data.length == 0"
            style="
                height: 100px;
                line-height: 100px;
                text-align: center;
                color: #888;
            "
        >
            <i>[商品列表] 无商品</i>
        </div>
    </div>
</template>

<script>
export default {
    name: "shop-product-list",
    data: function () {
        return {
            data: [],
        };
    },
    props: {
        option: {},
    },
    watch: {
        "option.count": {
            deep: true,
            handler: function (val) {
                this.getList();
            },
        },
        "option.target": {
            deep: true,
            handler: function (val) {
                this.getList();
            },
        },
        "option.type": {
            deep: true,
            handler: function (val) {
                this.getList();
            },
        },
    },
    mounted: function () {
        this.getList();
    },
    methods: {
        getList: function () {
            var that = this;
            var form = { per_page: that.option.count };
            switch (parseInt(this.option.type)) {
                case 0: {
                    form.cat_id = this.option.target;
                    break;
                }
                case 1: {
                    form.tag_id = this.option.target;
                    break;
                }
            }
            this.$http
                .get("/shop/api/product/getList", { params: form })
                .then(function (data) {
                    that.data = data.data;
                });
        },
    },
};
</script>

<style scoped>
.shop-product-list .type-1 img {
    float: left;
    width: 50%;
}

.shop-product-list .type-1 .box {
    display: flex;
}

.shop-product-list .type-1 .text {
    padding: 10px;
    display: flex;
    flex-direction: column;
}

.shop-product-list .type-2 {
    overflow-x: scroll;
    display: flex;
}

.shop-product-list .type-2 .product-item {
    display: inline-block;
}
</style>