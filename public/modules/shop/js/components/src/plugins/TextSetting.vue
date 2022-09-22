
<template>
    <div>
        <shop-platform v-model="form.platform"></shop-platform>
        <a-form>
            <a-tabs v-model="tabName" type="card">
                <a-tab-pane tab="基础" key="base">
                    <a-form-item label="内容">
                        <a-textarea
                            allow-clear
                            v-model="form.content"
                            :rows="6"
                        ></a-textarea>
                        <a-button @click="modalShow = !modalShow" type="primary">编辑</a-button>
                    </a-form-item>
                    <a-form-item label="跳转">
                        <shop-jump v-model="form.nav"></shop-jump>
                    </a-form-item>
                </a-tab-pane>
                <a-tab-pane tab="其他" key="other">
                    <a-form-item label="外边距">
                        <a-input v-model="form.margin"></a-input>
                    </a-form-item>
                    <a-form-item label="内边距">
                        <a-input v-model="form.padding"></a-input>
                    </a-form-item>
                    <a-form-item label="字体颜色">
                        <yi-color-picker v-model="form.color"></yi-color-picker>
                    </a-form-item>
                    <a-form-item label="字体大小">
                        <a-slider
                            :min="9"
                            :max="30"
                            v-model="form.size"
                        ></a-slider>
                    </a-form-item>
                    <a-form-item label="段落缩进">
                        <a-slider
                            :min="0"
                            :max="30"
                            v-model="form.indent"
                        ></a-slider>
                    </a-form-item>
                    <a-form-item label="对齐">
                        <a-radio-group v-model="form.textAlign">
                            <a-radio value="left">左</a-radio>
                            <a-radio value="center">中</a-radio>
                            <a-radio value="right">右</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item label="背景色">
                        <yi-color-picker
                            v-model="form.background"
                        ></yi-color-picker>
                    </a-form-item>
                </a-tab-pane>
            </a-tabs>
        </a-form>

        <a-modal v-model="modalShow" title="编辑文本内容" @ok="modalShow = false">
            <div>
                <yi-editor v-model="form.content" id="i-form-content"></yi-editor>
            </div>
        </a-modal>
    </div>
</template>
<script>
import SettingMixin from "../mixins/SettingMixin";
export default {
    name: "shop-text-setting",
    mixins: [SettingMixin],
    data() {
        return {
            modalShow: false
        }
    }
};
</script>