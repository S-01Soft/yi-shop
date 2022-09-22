
<template>
    <div :style="{ margin: option.margin }">
        <div
            class="shop-text-content"
            :style="{
                background: option.background,
                padding: option.padding,
                color: option.color,
                fontSize: option.size + 'px',
                textIndent: option.indent + 'px',
                textAlign: option.textAlign,
            }"
        >
        <div v-html="option.content || '请输入文字'"></div>
        </div>
    </div>
</template>
<script>
export default {
    name: "shop-text",
    props: {
        option: {},
    },
};
</script>
<style scoped>
.shop-text-content {
    text-indent: 20px;
    line-height: 150%;
}
</style>
<style>
.shop-text-content p {
    margin-bottom: 0!important;
}
</style>