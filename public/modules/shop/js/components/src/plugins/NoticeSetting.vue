
<template>
    <div class="shop-notice-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <div class="form form-horizontal">
            <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">
                <a-tabs v-model="tabName" type="card">
                    <a-tab-pane tab="基础" key="base">
                        <a-form-item label="切换时间(s)">
                            <a-input v-model="form.interval"></a-input>
                        </a-form-item>

                        <a-collapse default-active-key="1">
                            <a-collapse-panel header="通知内容" key="1">
                                <vuedraggable v-model="form.list">
                                    <div
                                        v-for="(item, index) in form.list"
                                        :key="index"
                                        class="shop-drag-item"
                                        @mouseover="currentIndex = index"
                                        @mouseleave="currentIndex = -1"
                                    >
                                        <a-form-item label="标题">
                                            <a-input
                                                v-model="item.title"
                                            ></a-input>
                                        </a-form-item>
                                        <a-form-item label="跳转目标">
                                            <shop-jump
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
                                                @click="handleDelete(index)"
                                                type="delete"
                                                style="color: #fff"
                                            ></a-icon>
                                        </div>
                                    </div>
                                </vuedraggable>
                                <a-form-item>
                                    <a-button type="danger" @click="handleAdd"
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
                        <a-form-item label="字体颜色">
                            <yi-color-picker
                                v-model="form.color"
                            ></yi-color-picker>
                        </a-form-item>
                    </a-tab-pane>
                </a-tabs>
            </a-form>
        </div>
    </div>
</template>
<script>
import SettingMixin from '../mixins/SettingMixin'
export default {
    name: "shop-notice-setting",
    mixins: [SettingMixin],
    data: function () {
        return {
            currentIndex: -1,
        };
    },
    methods: {
        handleAdd: function () {
            this.form.list.push({
                title: "",
                nav: { type: "0", target: "", params: "" },
            });
        },
        handleDelete: function (index) {
            this.form.list.splice(index, 1);
        },
    },
};
</script>