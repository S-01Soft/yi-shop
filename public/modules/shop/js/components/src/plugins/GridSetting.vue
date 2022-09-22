
<template>
    <div class="shop-grid-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <a-form :label-col="{ span: 4 }" :wrapper-col="{ span: 20 }">
            <a-tabs v-model="tabName" type="card">
                <a-tab-pane tab="基础" key="base">
                    <a-form-item label="每行个数">
                        <a-radio-group v-model="form.count">
                            <a-radio value="3">三个</a-radio>
                            <a-radio value="4">四个</a-radio>
                            <a-radio value="5">五个</a-radio>
                            <a-radio value="6">六个</a-radio>
                        </a-radio-group>
                    </a-form-item>

                    <a-collapse default-active-key="1">
                        <a-collapse-panel header="内容" key="1">
                            <vuedraggable v-model="form.list" class="shop-drag-box">
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
                                        ></yi-image>
                                    </a-form-item>
                                    <a-form-item label="内容">
                                        <a-input v-model="item.content" />
                                    </a-form-item>
                                    <a-form-item label="标题">
                                        <a-input v-model="item.title" />
                                    </a-form-item>
                                    <a-form-item label="跳转目标">
                                        <shop-jump
                                            label-width="150px"
                                            v-model="item.nav"
                                        ></shop-jump>
                                    </a-form-item>

                                    <div
                                        style="
                                            position: absolute;
                                            top: 0;
                                            right: 0;
                                            border-top-right-radius: 5px;
                                            padding: 2px 5px;
                                            background-color: red;
                                            cursor: pointer;
                                        "
                                        v-show="currentIndex == index"
                                    >
                                        <a-icon
                                            @click="
                                                handleRemoveNav(item, index)
                                            "
                                            type="delete"
                                            style="color: #fff"
                                        ></a-icon>
                                    </div>
                                </div>
                            </vuedraggable>

                            <a-form-item>
                                <a-button type="danger" @click="handleAddNav"
                                    >添加</a-button
                                >
                            </a-form-item>
                        </a-collapse-panel>
                    </a-collapse>
                </a-tab-pane>
                <a-tab-pane tab="其他" key="adv">
                    <a-form-item label="外边距">
                        <a-input v-model="form.margin"></a-input>
                    </a-form-item>
                    <a-form-item label="内边距">
                        <a-input v-model="form.padding"></a-input>
                    </a-form-item>
                    <a-form-item label="边框弧度">
                        <a-input v-model="form.paddborderRadiusing"></a-input>
                    </a-form-item>
                    <a-form-item label="背景色">
                        <yi-color-picker
                            v-model="form.background"
                        ></yi-color-picker>
                    </a-form-item>
                    <a-form-item label="子项内边距">
                        <a-input v-model="form.itemPadding"></a-input>
                    </a-form-item>
                    <a-form-item label="内容文字大小">
                        <a-input v-model="form.itemContentSize"></a-input>
                    </a-form-item>
                    <a-form-item label="内容文字颜色">
                        <yi-color-picker
                            v-model="form.itemContentColor"
                        ></yi-color-picker>
                    </a-form-item>
                    <a-form-item label="内容文字粗细">
                        <a-input v-model="form.itemConentWeigh"></a-input>
                    </a-form-item>
                    <a-form-item label="标题文字大小">
                        <a-input v-model="form.itemTitleSize"></a-input>
                    </a-form-item>
                    <a-form-item label="标题文字颜色">
                        <yi-color-picker
                            v-model="form.itemTitleColor"
                        ></yi-color-picker>
                    </a-form-item>
                    <a-form-item label="图片宽度">
                        <a-input v-model="form.imgWidth"></a-input>
                    </a-form-item>
                    <a-form-item label="图片高度">
                        <a-input v-model="form.imgHeight"></a-input>
                    </a-form-item>
                    <a-form-item label="图片内边距">
                        <a-input v-model="form.imgPadding"></a-input>
                    </a-form-item>
                    <a-form-item label="图片边框">
                        <a-input v-model="form.imgBorder"></a-input>
                    </a-form-item>
                    <a-form-item label="图片弧度">
                        <a-input v-model="form.imgBorderRadius"></a-input>
                    </a-form-item>
                    <a-form-item label="图片阴影">
                        <a-input v-model="form.imgBoxShadow"></a-input>
                    </a-form-item>
                </a-tab-pane>
            </a-tabs>
        </a-form>
    </div>
</template>

<script>
import SettingMixin from "../mixins/SettingMixin";
export default {
    name: "shop-grid-setting",
    mixins: [SettingMixin],
    data: function () {
        return {
            currentIndex: -1,
        };
    },
    methods: {
        handleAddNav: function (item, index) {
            this.form.list.push(this.getItemData());
        },
        handleRemoveNav: function (item, index) {
            this.form.list.splice(index, 1);
        },
        handleMoveUp: function (item, index) {
            var rows = this.form.list;
            if (index == 0 || rows.length < 2) return;
            var row1 = rows[index - 1];
            var row2 = rows[index];
            this.$set(this.form.list, index, row1);
            this.$set(this.form.list, index - 1, row2);
        },
        handleMoveDown: function (item, index) {
            var rows = this.form.list;
            if (index == rows.length - 1) return;
            var row1 = rows[index];
            var row2 = rows[index + 1];
            this.$set(this.form.list, index, row2);
            this.$set(this.form.list, index + 1, row1);
        },
        getItemData: function () {
            return { image: "", title: "", nav: { type: "0", target: "" } };
        },
    },
};
</script>