
<template>
    <div class="shop-user-head-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <a-form :label-col="{ span: 3 }" :wrapper-col="{ span: 18 }">
            <a-tabs v-model="tabName" type="card">
                <a-tab-pane tab="基础" key="base">
                    <a-form-item label="背景图片">
                        <yi-image
                            type="card"
                            :show-delete="true"
                            v-model="form.image"
                        ></yi-image>
                    </a-form-item>
                    <a-form-item label="背景颜色">
                        <yi-color-picker
                            v-model="form.background"
                        ></yi-color-picker>
                    </a-form-item>
                    <a-form-item label="昵称颜色">
                        <yi-color-picker v-model="form.color"></yi-color-picker>
                    </a-form-item>
                </a-tab-pane>
            </a-tabs>
        </a-form>
    </div>
</template>
<script>
import SettingMixin from "../mixins/SettingMixin";
export default {
    name: "shop-user-head-setting",
    mixins: [SettingMixin],
};
</script>