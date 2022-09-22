
<template>
    <div class="shop-user-section" :style="{ padding: option.padding }">
        <div class="shop-list" :style="{ background: option.background }">
            <ul>
                <li v-for="(item, index) in option.list" :key="index">
                    <div>
                        <i
                            :class="item.icon"
                            :style="{ color: item.color }"
                        ></i>
                        <span class="title">{{ item.title }}</span>
                        <i
                            class="iconfont iconyou"
                            style="font-size: 12px; color: #888; float: right"
                        ></i>
                        <span class="tips">{{ item.tips }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    name: "shop-list",
    props: {
        option: {},
    },
};
</script>
<style scoped>
.shop-list {
    background-color: #fff;
    padding: 0 10px;
    border-radius: 2px;
}

.shop-list ul {
    list-style: none;
}

.shop-list ul,
.shop-list li {
    padding: 0;
    margin: 0;
}

.shop-list li {
    padding: 4px 5px;
}

.shop-list .title {
    margin-left: 5px;
}

.shop-list .tips {
    color: #888;
    float: right;
    font-size: 12px;
    margin-right: 5px;
}
</style>