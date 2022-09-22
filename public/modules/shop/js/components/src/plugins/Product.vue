<template>
    <yi-select
        :style="style"
        v-model="id"
        show-search
        @search="handleSearchProduct"
        :url="url"
        :label-field="showLabel"
        value-field="id"
        :filter-option="false"
        :paginate="true"
        :set-default="false"
        :data-index="index"
        v-bind="{ ...$props, ...$attrs }"
        v-on="$listeners"
        @data-change="() => (inited = true)"
    >
    </yi-select>
</template>
<script>
export default {
    name: "shop-product",
    data() {
        return {
            kw: '',
            id: null,
            inited: false
        }
    },
    props: {
        value: {
            default: null
        },
        mode: {
            default: 'normal'
        },
        style: {
            default: {
                width: '150px'
            }
        }
    },
    watch: {
        value(v) {
            this.id = v;
        },
        id(v) {
            this.$emit('input', v);
        }
    },
    mounted() {
        this.id = this.value;
    },
    computed: {
        url: function() {
            let where = encodeURI(JSON.stringify({
                on_sale: 1,
                title: ['like', this.kw]
            })) 
            return `/shop/admin/product/index?where=${where}`;
        }
    },
    methods: {
        handleSearchProduct: function (v) {
            this.kw = v;
        },
        showLabel(item) {
            return `<div><span>${item.title}</span> <span style="font-size:13px;color: #aaa;">￥${item.price}</span></div>`;
        }
    },
};
</script>