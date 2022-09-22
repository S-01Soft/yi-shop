
<template>
    <div class="shop-search-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <div class="form form-horizontal">
            <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 16 }">
                <a-tabs v-model="tabName" type="card">
                    <a-tab-pane tab="基础" key="base">
                        <a-form-item label="提示文字">
                            <a-input v-model="form.placeholder"></a-input>
                        </a-form-item>
                        <a-form-item label="边框圆角">
                            <a-slider
                                v-model="form.borderRadius"
                                :min="0"
                                :max="50"
                            ></a-slider>
                        </a-form-item>
                        <a-form-item label="边框颜色">
                            <yi-color-picker
                                v-model="form.borderColor"
                            ></yi-color-picker>
                        </a-form-item>
                        <a-form-item label="对齐方式">
                            <a-radio-group v-model="form.textAlign">
                                <a-radio-button value="left"
                                    >居左</a-radio-button
                                >
                                <a-radio-button value="center"
                                    >居中</a-radio-button
                                >
                            </a-radio-group>
                        </a-form-item>
                        <a-form-item label="外边距">
                            <a-input v-model="form.margin"></a-input>
                        </a-form-item>
                        <a-form-item label="内边距">
                            <a-input v-model="form.padding"></a-input>
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
    name: "shop-search-setting",
    mixins: [SettingMixin],
};
</script>