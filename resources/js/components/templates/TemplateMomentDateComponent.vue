<template>
    <div>{{ dateLocal }}</div>
</template>

<<script>
    import AutoUpdateDateMixin from "../utils/mixins/AutoUpdateDateMixin";

    export default {
        name: 'TemplateMomentDateComponent',
        mixins: [AutoUpdateDateMixin],
    }
</script>