
<template>
    <div class="page-setting">
        <div class="form form-horizontal">
            <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">
                <a-tabs v-model="tabName" type="card">
                    <a-tab-pane tab="页面设置" key="base">
                        <a-form-item label="页面标题">
                            <a-input v-model="form.title"></a-input>
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
    name: "page-setting",
    mixins: [SettingMixin],
    data: function () {
        return {};
    },
    mounted: function () {
    },
    methods: {},
};
</script>