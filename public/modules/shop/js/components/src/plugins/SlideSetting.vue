
<template>
    <div class="shop-slide-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <a-form :label-col="{ span: 4 }" :wrapper-col="{ span: 20 }">
            <a-tabs v-model="tabName" type="card">
                <a-tab-pane tab="基础" key="base">
                    <a-collapse default-active-key="1">
                        <a-collapse-panel header="幻灯片内容" key="1">
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
                                            v-model="item.url"
                                        ></yi-image>
                                    </a-form-item>
                                    <a-form-item label="背景色">
                                        <yi-color-picker
                                            draggable="false"
                                            v-model="item.background"
                                        />
                                    </a-form-item>
                                    <a-form-item label="跳转">
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
                                            @click="handleRemoveImage(index)"
                                            type="delete"
                                            style="color: #fff"
                                        ></a-icon>
                                    </div>
                                </div>
                            </vuedraggable>

                            <a-form-item>
                                <a-button type="danger" @click="handleAddImage"
                                    >添加幻灯片</a-button
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
                    <a-form-item label="高度">
                        <a-input v-model="form.height"></a-input>
                    </a-form-item>
                    <a-form-item label="背景色">
                        <yi-color-picker v-model="form.background" />
                    </a-form-item>
                </a-tab-pane>
            </a-tabs>
        </a-form>
    </div>
</template>
<script>
import SettingMixin from '../mixins/SettingMixin'
export default {
    name: "shop-slide-setting",
    mixins: [SettingMixin],
    data: function () {
        return {
            currentIndex: -1,
        };
    },
    methods: {
        handleAddImage: function () {
            this.form.list.push(this.getImageOption());
        },
        handleRemoveImage: function (index) {
            this.form.list.splice(index, 1);
        },
        getImageOption: function () {
            return {
                url: "",
                background: "#ffffff",
                nav: {
                    type: "0",
                    target: "",
                },
            };
        },
    },
};
</script>