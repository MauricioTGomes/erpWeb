<script>

    export default {
        props: ['options', 'value', 'placeholder'],

        mounted() {
            let vm = this;
            $(this.$el)
                .select2({width: '100%', placeholder: this.placeholder, data: this.options})
                .val(this.value)
                .trigger('change')
                .on('change', function () {
                    vm.$emit('input', this.value)
                })
        },
        watch: {
            // value: function (value) {
            //     $(this.$el).val(value);
            // },
            // options: function (options) {
            //     $(this.$el).empty().select2({data: options})
            // }
        },
        destroyed: function () {
            $(this.$el).off().select2('destroy')
        }

    };
</script>

<template>
    <select>
        <slot></slot>
    </select>
</template>