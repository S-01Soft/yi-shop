
<template>
    <div class="shop-user-logout-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <a-form :label-col="{ span: 4 }" :wrapper-col="{ span: 20 }">
            <a-tabs v-model="tabName" type="card">
                <a-tab-pane tab="基础" key="base">
                    <a-form-item label="外边距">
                        <a-input v-model="form.margin"></a-input>
                    </a-form-item>
                    <a-form-item label="内边距">
                        <a-input v-model="form.padding"></a-input>
                    </a-form-item>
                    <a-form-item label="颜色">
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
    name: "shop-user-logout-setting",
    mixins: [SettingMixin],
};
</script>