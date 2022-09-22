
<template>
    <div class="shop-notice text-center"
        :style="{background: option.background,margin: option.margin,padding:option.padding}">
        <a-carousel height="20px" indicator-position="none" direction="vertical" style="text-align: left;"
            :autoplay="true" v-if="option.list && option.list.length">
            <div v-for="(item,index) in option.list">
                <p class="text-left" :style="{color: option.color, margin: '2px 0'}">
                    <a-icon type="notification"></a-icon> {{item.title||'标题'}}
                </p>
            </div>
        </a-carousel>
        <div class="text-center" style="height: 20px;color: #888;line-height:20px" v-else>
            <i>[通告] 无内容</i>
        </div>
    </div>
</template>
<script>
export default {
            name: 'shop-notice',
            props: {
                option: {}
            }
}
</script>