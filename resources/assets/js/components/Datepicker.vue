<script>

    export default {
        props: ['value', 'name', 'classe'],

        mounted() {
            let vm = this;
            $(this.$el)
                .on('change', function () {
                    vm.$emit('input', this.value)
                })
        }
        // destroyed: function () {
        //     $(this.$el).off().select2('destroy')
        // }

    };
</script>

<template>
    <input type="text" class="form-control" :class="classe" :name="name"/>
</template>