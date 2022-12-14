<template>
    <button v-bind="_config">{{ cValue }}</button>
</template>

<script>
    export default {
        name: 'FormButtonComponent',
        props: {
            config: { type: Object, default: () => ({}) },
            configDefault: { type: Object, default: () => ({
                value: 'нет значения',
            }) },
        },
        computed: {
            _config() {
                return Object.assign(this.configDefault, this.config);
            },
            cValue: {
                cache: false,
                get() { return this._config.value; },
            },
        },
    }
</script>