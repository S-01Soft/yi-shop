<template>
    <div
        class="shop-slide text-center"
        :style="{
            background: option.background,
            margin: option.margin,
            padding: option.padding,
        }"
    >
        <div
            v-if="option.list && option.list.length"
            :style="{ height: option.height }"
        >
            <a-carousel autoplay :height="option.height" :dots="false">
                <div
                    v-for="(item, idx) in option.list"
                    :key="idx"
                    class="text-center"
                >
                    <div
                        :style="{ background: item.background }"
                        v-if="item.url.length"
                    >
                        <img
                            :style="{ height: option.height, margin: 'auto' }"
                            :src="item.url"
                            alt=""
                        />
                    </div>
                    <div
                        v-else
                        style="color: #888"
                        :style="{
                            height: option.height,
                            lineHeight: option.height,
                        }"
                    >
                        <i>[幻灯片] 无图片</i>
                    </div>
                </div>
            </a-carousel>
        </div>
    </div>
</template>
<script>
export default {
    name: "shop-slide",
    props: {
        option: {},
        index: 0,
        operate: false,
    },
};
</script>
<style scoped>
.shop-slide-setting table th,
.shop-slide-setting table td {
    text-align: center;
    padding: 4px;
}

.shop-slide-setting .shop-navs {
    width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>