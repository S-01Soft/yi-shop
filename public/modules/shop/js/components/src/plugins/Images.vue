
<template>
    <div class="shop-images">
        <div class="">
            <div class="mode1" v-if="option.mode == 1">
                <div v-for="(item, index) in option.list" :key="index">
                    <img style="width: 100%" :src="item.image || image" />
                </div>
            </div>
            <div class="mode2 flex-row" v-if="option.mode == 2">
                <div
                    v-for="(item, index) in option.list"
                    :key="index"
                    class="flex-1"
                >
                    <img style="width: 100%" :src="item.image || image" />
                </div>
            </div>
            <div class="mode3 flex-row" v-if="option.mode == 3">
                <div
                    v-for="(item, index) in option.list"
                    :key="index"
                    class="flex-1"
                >
                    <img style="width: 100%" :src="item.image || image" />
                </div>
            </div>
            <div class="mode4 flex-row" v-if="option.mode == 4">
                <div class="left flex-1">
                    <img
                        @click="$jump(option.list[0].nav)"
                        style="width: 100%"
                        :src="option.list[0].image || image"
                    />
                </div>
                <div class="right flex-1 flex-column">
                    <div>
                        <img
                            style="width: 100%"
                            :src="option.list[1].image || halfImage"
                        />
                    </div>
                    <div>
                        <img
                            style="width: 100%"
                            :src="option.list[2].image || halfImage"
                        />
                    </div>
                </div>
            </div>
            <div class="mode5 flex-row" v-if="option.mode == 5">
                <div
                    v-for="(item, index) in option.list"
                    :key="index"
                    class="flex-1"
                >
                    <img style="width: 100%" :src="item.image || image" />
                </div>
            </div>
            <div class="mode6" v-if="option.mode == 6">
                <img
                    v-for="(item, index) in option.list"
                    :key="index"
                    class="mode6-img-item"
                    style="width: 50%"
                    :src="item.image || image"
                />
                <div class="clear"></div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "shop-images",
    props: {
        option: {},
    },
    data: function () {
        return {
            image: "/modules/shop/image/img.png",
            halfImage: "/modules/shop/image/img-half.png",
        };
    },
};
</script>
<style scoped>
.clear::after {
    content: "";
    display: block;
    clear: both;
}
.flex-column {
    display: flex;
    flex-direction: column;
}
.flex-row {
    display: flex;
    flex-direction: row;
}
.flex-1 {
    flex: 1;
}
.mode6-img-item {
    float: left;
}
</style>