<template>
    <textarea v-bind="_textareaCfg"
              v-model="editorText"
    ></textarea>
</template>

<script>
    export default {
        name: 'FormTextareaComponent',
        props: {
            config: { type: Object, default: () => ({counter: true}) },
            configDefault: { type: Object, default: () => ({
                    rows: 10,
                    limit: 2048,
                    class: 'form-control',
                }) },

            text: { type: String, default: null },
        },
        data: function () {
            return {
                editorText: this.text,
            }
        },
        computed: {
            _textareaCfg() {
                return Object.assign(this.configDefault, this.config);
            },
            hasText() {
                return !!this.editorText;
            },
            length: {
                cache: false,
                get() { return (this.hasText) ? this.editorText.length : 0; }
            },
        },
        watch: {
            text(val) { this.editorText = val; },
            editorText() {
                this.debounceInterfaceValue();
            },
        },
        created() {
            this.debounceInterfaceValue = _.debounce(this.interfaceValue, 100);
        },
        methods: {

            /*
             |---------------------------------------------------------------------------------------------
             | Forms
             |--------------------------------------------------------------------------------------
             */

            reset() {
                this.editorText = null;
            },
            getFormData() {
                return this.editorText;
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