
<template>
    <div class="shop-images-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <div class="form form-horizontal">
            <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">
                <a-tabs v-model="tabName" type="card">
                    <a-tab-pane tab="基础" key="base">
                        <a-form-item label="高度">
                            <a-radio-group v-model="form.mode">
                                <a-radio-button value="1"
                                    >样式一</a-radio-button
                                >
                                <a-radio-button value="2"
                                    >样式二</a-radio-button
                                >
                                <a-radio-button value="3"
                                    >样式三</a-radio-button
                                >
                                <a-radio-button value="4"
                                    >样式四</a-radio-button
                                >
                                <a-radio-button value="5"
                                    >样式五</a-radio-button
                                >
                                <a-radio-button value="6"
                                    >样式六</a-radio-button
                                >
                            </a-radio-group>
                        </a-form-item>

                        <a-collapse default-active-key="1">
                            <a-collapse-panel header="内容" key="1">
                                <vuedraggable
                                    v-model="form.list"
                                    class="shop-drag-box"
                                >
                                    <div
                                        v-for="(item, index) in form.list"
                                        :key="index"
                                        class="shop-drag-item"
                                        @mouseover="currentIndex = index"
                                        @mouseleave="currentIndex = -1"
                                    >
                                        <a-form-item label="图片">
                                            <yi-image
                                                type="card"
                                                v-model="item.image"
                                            >
                                            </yi-image>
                                        </a-form-item>
                                        <a-form-item label="跳转">
                                            <shop-jump
                                                label-width="150px"
                                                v-model="item.nav"
                                            ></shop-jump>
                                        </a-form-item>
                                    </div>
                                </vuedraggable>
                            </a-collapse-panel>
                        </a-collapse>
                    </a-tab-pane>
                </a-tabs>
            </a-form>
        </div>
    </div>
</template>
<script>
var mode = {
    1: 1,
    2: 2,
    3: 3,
    4: 3,
    5: 4,
    6: 4,
};
import SettingMixin from '../mixins/SettingMixin'
export default {
    name: "shop-images-setting",
    mixins: [SettingMixin],
    watch: {
        "form.mode": function (v) {
            this.initImageList();
        },
    },
    mounted() {
        this.form.mode = 1;
        this.initImageList();
    },
    methods: {
        initImageList: function () {
            var list = [];
            for (var i = 0; i < mode[this.form.mode]; i++) {
                if (this.form.list[i]) list.push(this.form.list[i]);
                else
                    list.push({
                        image: "",
                        nav: { type: "0", target: "", params: "" },
                    });
            }
            this.form.list = list;
        },
    },
};
</script>