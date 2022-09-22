
<template id="shop-user-order-setting">
    <div class="shop-user-order-setting">
        <shop-platform v-model="form.platform"></shop-platform>
        <a-form :label-col="{span:3}" :wrapper-col="{span:18}">
            <a-tabs v-model="tabName" type="card">
                <a-tab-pane tab="基础" key="base">
                    <a-form-item label="外边距">
                        <a-input v-model="form.margin"></a-input>
                    </a-form-item>
                </a-tab-pane>
            </a-tabs>
        </a-form>
    </div>
</template>
<script>
import SettingMixin from '../mixins/SettingMixin'
export default {
    name: 'shop-user-order-setting',
    mixins: [SettingMixin]
}
</script>