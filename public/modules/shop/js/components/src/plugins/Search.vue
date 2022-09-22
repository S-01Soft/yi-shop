
<template>
    <div class="shop-search" :style="{ margin: option.margin }">
        <div :style="{ padding: option.padding }">
            <input
                type="text"
                :placeholder="option.placeholder"
                :style="{
                    borderRadius: option.borderRadius + 'px',
                    textAlign: option.textAlign,
                    borderColor: option.borderColor,
                }"
            />
        </div>
    </div>
</template>
<script>
export default {
    name: "shop-search",
    props: {
        option: {},
    },
};
</script>

<style scoped>
.shop-search input {
    width: 100%;
    border: 1px solid;
    padding: 5px 15px;
}
</style>