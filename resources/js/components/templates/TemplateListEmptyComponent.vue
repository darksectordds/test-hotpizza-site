<template>
    <div class="w-100 py-4 d-flex justify-content-center align-items-center">{{ value }}</div>
</template>

<script>
    export default {
        name: 'TemplateListEmptyComponent',
        props: {
            value: { type: String, default: '--- пусто ---' },
        },
    }
</script>