
<template>
    <div class="phone-frame">
        <div class="head-box" :style="{ height: headHeight }">
            <slot name="head"></slot>
        </div>
        <div class="body-box" :style="bodyStyle">
            <slot name="body"></slot>
        </div>
        <div class="footer-box text-center" :style="footerStyle">
            <slot name="footer"></slot>
        </div>
        <slot></slot>
    </div>
</template>
<script>
export default {
    name: "shop-phone-frame",
    props: {
        headHeight: {
            type: String,
            default: "32px",
        },
        bodyHeight: {
            type: String,
            default: "420px",
        },
        bodyStyle: {
            type: Object | String,
            default: {},
        },
        footerStyle: {
            type: String,
            default: "",
        },
    },
};
</script>

<style scoped>
.phone-frame {
    background-image: url("/modules/shop/image/phone.png");
    font-size: 16px;
    background-color: #fff;
    width: 330px;
    height: 670px;
    z-index: 10;
    margin: 0;
    box-sizing: border-box;
    overflow-y: auto;
    background-repeat: no-repeat;
    background-size: 100%;
    border-radius: 30px;
    padding: 40px 20px 38px 20px;
}

.phone-frame .body-box {
    position: relative;
    height: 555px;
}

.pcomment-item {
    position: relative;
}
</style>