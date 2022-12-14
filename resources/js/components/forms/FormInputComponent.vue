<template>
    <input :class="[_inputCfg.class]"
           v-bind="_inputCfg"
           v-model="value"
           required />
</template>

<script>
    export default {
        name: 'FormInputComponent',
        props: {
            config: { type: Object, default: () => ({}) },
            configDefault: { type: Object, default: () => ({
                    type: 'text',
                    class: 'form-control',
                    value: '-- нет значения --',
            }) },
        },
        data() {
            return {
                value: this.config.value ?? '',
            }
        },
        watch: {
            value() {
                this.interfaceValue();
            },
            _inputCfg(config) {
                this.value = config.value;
            },
        },
        computed: {
            _inputCfg() { return Object.assign(this.configDefault, this.config) },
        },
        methods: {

            /*
             |---------------------------------------------------------------------------------------------
             | Forms
             |--------------------------------------------------------------------------------------
             */

            reset() {
                this.value = null;
            },
            getFormData() {
                return this.value;
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Interfaces
             |--------------------------------------------------------------------------------------
             */

            interfaceValue() {
                this.$emit('value', this.getFormData());
            },
        },
    }
</script>