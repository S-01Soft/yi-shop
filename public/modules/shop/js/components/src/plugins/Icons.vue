
<template>
    <div class="shop-icon">
        <i
            :class="iconclass"
            class="pointer"
            v-if="iconclass != ''"
            @click="visible = !visible"
            :style="{ color: color }"
            style="width: 44px; height: 44px"
        ></i>
        <span
            v-else
            class="pointer"
            @click="visible = !visible"
            style="font-size: 12px"
            >选择</span
        >
        <a-modal
            v-model="visible"
            ok-text="确定"
            cancel-text="取消"
            :dialog-style="{ top: '20px' }"
        >
            <div
                class="shop-icons clear"
                style="max-height: 350px; overflow-y: scroll"
            >
                <div
                    class="item"
                    v-for="(item, index) in icons"
                    :key="index"
                    @click="
                        iconclass = item;
                        visible = false;
                    "
                >
                    <i style="font-size: 20px" :class="item"></i>
                </div>
            </div>
        </a-modal>
    </div>
</template>
<script>
export default {
    name: "shop-icon",
    data: function () {
        return {
            icons: [],
            iconclass: "",
            visible: false,
        };
    },
    props: {
        color: "",
        value: "",
    },
    watch: {
        value: function (val) {
            this.iconclass = val;
        },
        iconclass: function (val) {
            this.$emit("input", val);
        },
    },
    mounted: function () {
        this.getIcons();
        this.iconclass = this.value;
    },
    methods: {
        getIcons: function () {
            var that = this;
            $.get("/modules/shop/css/iconfont.css", function (ret) {
                var exp = /\.(.*):/gi;
                var result;
                while ((result = exp.exec(ret)) != null) {
                    that.icons.push("iconfont " + result[1]);
                }
            });
        },
    },
};
</script>
<style scoped>
.shop-icons .item {
    border: 1px solid #ddd;
    width: 44px;
    float: left;
    margin: 3px;
    padding: 3px 8px;
    cursor: pointer;
}
</style>