
<template>
    <div class="shop-user-section">
        <div
            class="shop-grid"
            :style="{
                background: option.background,
                margin: option.margin,
                padding: option.padding,
            }"
        >
            <div v-if="option.list && option.list.length">
                <div class="nav-list" v-for="(row, i) in list" :key="i">
                    <div
                        class="nav-item"
                        v-for="(item, j) in row"
                        style="float: left"
                        :style="{
                            width: 100 / option.count + '%',
                            padding: option.itemPadding,
                        }"
                    >
                        <div class="item text-center">
                            <img
                                v-if="item.image && item.image.length"
                                :style="{
                                    border: option.imgBorder,
                                    boxShadow: option.imgBoxShadow,
                                    borderRadius: option.imgBorderRadius,
                                    width: option.imgWidth,
                                    height: option.imgHeight,
                                    padding: option.imgPadding,
                                }"
                                :src="item.image"
                                width="100%"
                                alt=""
                            />
                            <div
                                class=""
                                v-else
                                style="
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                "
                                :style="{
                                    border: option.imgBorder,
                                    borderRadius: option.imgBorderRadius,
                                    boxShadow: option.imgBoxShadow,
                                    padding: option.imgPadding,
                                    fontSize: option.itemContentSize,
                                    color: option.itemContentColor,
                                    fontWeigh: option.itemContentWeigh,
                                }"
                            >
                                {{ item.content || "--" }}
                            </div>
                            <div
                                v-else
                                style="
                                    height: 50px;
                                    line-height: 50px;
                                    text-align: center;
                                    font-size: 12px;
                                    color: #888;
                                "
                            >
                                <i>[无图]</i>
                            </div>
                            <div
                                v-if="item.title && item.title.length"
                                class="title text-center"
                                :style="{
                                    fontSize: option.itemTitleSize,
                                    color: option.itemTitleColor,
                                }"
                            >
                                {{ item.title }}
                            </div>
                            <div
                                v-else
                                style="
                                    text-align: center;
                                    font-size: 12px;
                                    color: #888;
                                "
                            >
                                <i>[无标题]</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "shop-grid",
    props: {
        option: {},
    },
    computed: {
        list: function () {
            var result = [];
            var rows = this.option.list;
            var count = this.option.count;
            if (count == 0) return [];
            var len = Math.ceil(rows.length / count);
            for (var i = 0; i < len; i++) {
                var item = [];
                for (var j = i * count; j < (i + 1) * count; j++) {
                    if (rows[j]) item.push(rows[j]);
                }
                result.push(item);
            }
            return result;
        },
    },
};
</script>


<style scoped>
.shop-grid .nav-list::after {
    content: "";
    display: block;
    clear: both;
}

.shop-grid .title1 {
    font-size: 13px;
    margin: 5px 10px;
    display: flex;
    align-items: center;
}

.shop-grid .txt {
    margin-left: 5px;
}
</style>