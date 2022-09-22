
<template id="shop-list-setting">
    <div class="shop-list-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <div class="form form-horizontal">
            <a-form :label-col="{ span: 4 }" :wrapper-col="{ span: 18 }">
                <a-tabs v-model="tabName" type="card">
                    <a-tab-pane tab="基础" key="base">
                        <a-collapse default-active-key="1">
                            <a-collapse-panel header="内容" key="1">
                                <vuedraggable v-model="form.list">
                                    <div
                                        v-for="(item, index) in form.list"
                                        :key="index"
                                        class="shop-drag-item"
                                        @mouseover="currentIndex = index"
                                        @mouseleave="currentIndex = -1"
                                    >
                                        <a-form-item label="标题">
                                            <a-input v-model="item.title" />
                                        </a-form-item>
                                        <a-form-item label="提示文字">
                                            <a-input v-model="item.tips" />
                                        </a-form-item>
                                        <a-form-item label="图标">
                                            <shop-icon
                                                :color="item.color"
                                                v-model="item.icon"
                                            ></shop-icon>
                                        </a-form-item>
                                        <a-form-item label="图标颜色">
                                            <yi-color-picker
                                                size="small"
                                                v-model="item.color"
                                            ></yi-color-picker>
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
                                    <a-button type="danger" @click="handleAdd"
                                        >添加</a-button
                                    >
                                </a-form-item>
                            </a-collapse-panel>
                        </a-collapse>
                    </a-tab-pane>
                    <a-tab-pane tab="其他" key="other">
                        <a-form-item label="边距">
                            <a-input v-model="form.padding"></a-input>
                        </a-form-item>
                        <a-form-item label="背景颜色">
                            <yi-color-picker
                                v-model="form.background"
                            ></yi-color-picker>
                        </a-form-item>
                    </a-tab-pane>
                </a-tabs>
            </a-form>
        </div>
    </div>
</template>
<script>
import SettingMixin from "../mixins/SettingMixin";
export default {
    name: "shop-list-setting",
    mixins: [SettingMixin],
    data: function () {
        return {
            currentIndex: -1,
        };
    },
    methods: {
        handleAdd: function () {
            this.form.list.push(this.getOption());
        },
        handleRemove: function (item, index) {
            this.form.list.splice(index, 1);
        },
        getOption: function () {
            return {
                title: "",
                icon: "",
                color: "#ef393c",
                tips: "",
                nav: { type: "0", target: "", params: "" },
            };
        },
        handleMoveUp: function (index) {
            if (index == 0 || this.form.list.length < 2) return;
            var row1 = this.form.list[index - 1];
            var row2 = this.form.list[index];
            this.$set(this.form.list, index, row1);
            this.$set(this.form.list, index - 1, row2);
        },
        handleMoveDown: function (index) {
            if (index == this.form.list.length - 1) return;
            var row1 = this.form.list[index];
            var row2 = this.form.list[index + 1];
            this.$set(this.form.list, index, row2);
            this.$set(this.form.list, index + 1, row1);
        },
        handleRemoveNav: function (item, index) {
            this.form.list.splice(index, 1);
        },
    },
};
</script>
