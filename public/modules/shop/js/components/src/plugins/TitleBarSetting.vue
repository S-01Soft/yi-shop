
<template>
    <div class="form form-horizontal">
        <shop-platform v-model="form.platform"></shop-platform>
        <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">
            <a-tabs v-model="tabName" type="card">
                <a-tab-pane tab="基础" key="base">
                    <a-form-item label="标题">
                        <a-input v-model="form.title"></a-input>
                    </a-form-item>
                    <a-form-item label="标题大小">
                        <a-slider
                            v-model="form.titleSize"
                            :min="11"
                            :max="30"
                        ></a-slider>
                    </a-form-item>
                    <a-form-item label="上下边距">
                        <a-slider
                            v-model="form.padding"
                            :min="0"
                            :max="30"
                        ></a-slider>
                    </a-form-item>
                    <a-form-item label="文字颜色">
                        <yi-color-picker
                            v-model="form.titleColor"
                        ></yi-color-picker>
                    </a-form-item>
                    <a-form-item label="背景色">
                        <yi-color-picker
                            v-model="form.backgroundColor"
                        ></yi-color-picker>
                    </a-form-item>
                    <a-form-item label="跳转">
                        <shop-jump v-model="form.nav"></shop-jump>
                    </a-form-item>
                </a-tab-pane>
            </a-tabs>
        </a-form>
    </div>
</template>
<script>
import SettingMixin from "../mixins/SettingMixin";
export default {
    name: "shop-title-bar-setting",
    mixins: [SettingMixin],
};
</script>