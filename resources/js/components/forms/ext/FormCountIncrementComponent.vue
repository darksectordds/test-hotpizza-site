<template>
    <div class="input-group">
        <form-button-component class="btn btn-outline-secondary"
                               :config="{value: '-'}"
                               @click.native="onValueDecrement"></form-button-component>

        <form-input-component class="form-control"
                              :config="{type: 'number', value: value, min: min, step: step}"
                              @value="onValue"></form-input-component>

        <form-button-component class="btn btn-outline-secondary"
                               :config="{value: '+'}"
                               @click.native="onValueIncrement"></form-button-component>
    </div>
</template>

<script>
    import FormButtonComponent from "../FormButtonComponent";
    import FormInputComponent from "../FormInputComponent";

    export default {
        name: 'FormCountIncrementComponent',
        components: {
            FormButtonComponent,
            FormInputComponent
        },
        props: {
            min: {type: Number, default: 1},
            step: {type: Number, default: 1},
        },
        data() {
            return {
                value: 1,
            }
        },
        watch: {
            value() {
                this.interfaceValue();
            }
        },
        methods: {

            /*
             |---------------------------------------------------------------------------------------------
             | Events
             |--------------------------------------------------------------------------------------
             */

            onValue(val) {
                this.value = val;
            },
            onValueDecrement() {
                if (this.value > this.min)
                    this.value = this.value - 1;
            },
            onValueIncrement() {
                this.value = this.value + 1;
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Interfaces
             |--------------------------------------------------------------------------------------
             */

            interfaceValue() {
                this.$emit('value', this.value);
            },
        },
    }
</script>

<style scoped>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>