<template>
    <button v-bind="_config">
        <slot>{{ cValue }}</slot>
    </button>
</template>

<script>
    export default {
        name: 'FormButtonComponent',
        props: {
            config: { type: Object, default: () => ({}) },
            configDefault: { type: Object, default: () => ({
                value: null,
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