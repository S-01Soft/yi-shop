
<template id="shop-user-head">
    <div class="shop-user-head">
        <div
            class="userinfo-section"
            :style="{
                background: option.image
                    ? 'url(' + option.image + ') 100% no-repeat'
                    : option.background,
            }"
        >
            <div class="section">
                <img
                    src="/static/images/missing-face.png"
                    style="width: 50px; height: 50px"
                />
                <div class="info">
                    <div class="nickname" :style="{ color: option.color }">
                        Admin
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "shop-user-head",
    props: {
        option: {},
    },
};
</script>
<style scoped>
.section {
    margin: 5px 10px;
}
.shop-user-head .b-btn {
    text-align: center;
    font-size: 14px;
    color: #36343c;
    border-radius: 15px;
    background: linear-gradient(to right, #f9e6af, #ffd465);
    z-index: 1;
    padding: 5px 15px;
}

.shop-user-head .userinfo-section {
    position: relative;
    display: flex;
    flex-direction: row;
    align-items: center;
    flex-wrap: wrap;
    padding: 11px 20px;
    background-size: cover !important;
}

.shop-user-head .info .nickname {
    margin-left: 10px;
    font-size: 14px;
}

.userinfo-section .section {
    display: flex;
    flex-direction: row;
    align-items: center;
}

.shop-user-head .not-logic-section {
    padding: 20px 0;
}
</style>