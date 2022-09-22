
<template>
    <div
        class="shop-user-logout"
        :style="{ margin: option.margin, color: option.color }"
    >
        退出登录
    </div>
</template>
<script>
export default {
    name: "shop-user-logout",
    props: {
        option: {},
    },
};
</script>
<style scoped>
.shop-user-logout {
    display: flex;
    align-items: baseline;
    padding: 10px;
    line-height: 30px;
    position: relative;
    background: #fff;
    justify-content: center;
    margin: 0 10px;
}
</style>