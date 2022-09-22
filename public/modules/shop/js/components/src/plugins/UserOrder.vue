<template>
    <div class="shop-user-section" :style="{margin: option.margin}">
        <div class="shop-user-order">
            <div class="order-section">
                <div class="order-item" hover-class="common-hover">
                    <span class="iconfont iconquanbudingdan1"></span>
                    <span>全部订单</span>
                </div>
                <div class="order-item" hover-class="common-hover">
                    <span class="iconfont icondaifukuan"></span>
                    <span>待付款</span>
                </div>
                <div class="order-item" hover-class="common-hover">
                    <span class="iconfont icondaishouhuo"></span>
                    <span>待收货</span>
                </div>
                <div class="order-item" hover-class="common-hover">
                    <span class="iconfont iconicon4"></span>
                    <span>待评价</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    
            name: "shop-user-order",
            props: {
                option: {}
            }
}
</script>
<style scoped>
    .shop-user-order {
        background-color: #fff;
    }

    .shop-user-order .order-section {
        padding: 14px 0;
        display: flex;
    }

    .shop-user-order .order-item {
        width: 60px;
        height: 60px;
        border-radius: 5px;
        font-size: 12px;
        color: #555;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .shop-user-order .order-item .iconfont {
        font-size: 24px;
        margin-bottom: 9px;
        color: #fa436a;
    }
</style>