
<template>
    <div
        class="shop-title-bar text-center"
        :style="{
            paddingTop: option.padding + 'px',
            paddingBottom: option.padding + 'px',
            background: option.backgroundColor,
            color: option.titleColor,
            fontSize: option.titleSize + 'px',
        }"
    >
        {{ option.title }}
    </div>
</template>
<script>
export default {
    name: "shop-title-bar",
    props: {
        option: {},
    },
};
</script>
<style scoped>
.shop-title-bar::before {
    display: inline-block;
    content: "";
    width: 15%;
    height: 5px;
    border-top: 1px solid #888;
    margin-right: 10px;
}
.shop-title-bar::after {
    display: inline-block;
    content: "";
    width: 15%;
    height: 5px;
    border-top: 1px solid #888;
    margin-left: 10px;
}
</style>