<script>
export default {
    data: function () {
        return {
            form: {},
            tabName: "base",
        };
    },
    props: {
        value: {
            default: {},
        },
    },
    watch: {
        value: {
            handler: function (val) {
                this.form = val;
            },
            deep: true,
        },
        form: {
            handler: function (val) {
                this.$emit("input", val);
                this.$emit("change", val);
            },
            deep: true,
        },
    },
    mounted: function () {
        this.form = this.value;
    },
};
</script>