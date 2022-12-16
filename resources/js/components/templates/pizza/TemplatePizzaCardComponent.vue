<template>
    <div class="pizza-card">
        <div class="pizza-card__offset"></div>
        <div class="pizza-card__context">
            <div class="pizza-card__context__image-box">
                <template-image-component class="img"
                                          :image="image"></template-image-component>
            </div>
            <div class="pizza-card__context__data-container">
                <div class="title">{{ title }}</div>
                <div class="description">{{ description }}</div>
                <div class="price">{{ price }} руб.</div>
                <div class="control">

                    <form-count-increment-component class="control__increment me-4"
                                                    @value="onCount"></form-count-increment-component>

                    <form-button-component class="control__cart btn btn-success"
                                           :config="{value: 'в корзину'}"
                                           @click.native="interfaceCount"></form-button-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FormCountIncrementComponent from "../../forms/ext/FormCountIncrementComponent";
    import FormButtonComponent from "../../forms/FormButtonComponent";
    import TemplateImageComponent from "../TemplateImageComponent";

    export default {
        name: 'TemplatePizzaCardComponent',
        components: {
            TemplateImageComponent,
            FormButtonComponent,
            FormCountIncrementComponent
        },
        props: {
            title: { type: String },
            image: { type: String },
            description: { type: String },
            price: { type: Number },
        },
        data() {
            return {
                count: 0,
            }
        },
        methods: {

            /*
             |---------------------------------------------------------------------------------------------
             | Events
             |--------------------------------------------------------------------------------------
             */

            onCount(val) {
                this.count = val;
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Interfaces
             |--------------------------------------------------------------------------------------
             */

            interfaceCount() {
                this.$emit('count', this.count);
            },
        },
    }
</script>

<style lang="scss" scoped>
    $background-offset-width: 60px;
    $padding-y: 52px;
    $padding-x: 52px;

    .pizza-card {
        /*display: flex;*/
        /*flex-direction: row;*/
        //border: 1px solid black;

        &__offset {
            width: $background-offset-width;
        }
        &__context {
            display: flex;
            width: 100%;

            // 52 21 52 60
            padding: 21px 11px 21px 0;
            background-color: white;
            border: 1px solid #e1dbda;
            border-radius: 17px;

            &__image-box {
                position: relative;
                left: -30px;

                min-width: 180px;
                min-height: 150px;
                background: linear-gradient(135deg, rgba(252,133,59,1) 0%, rgba(252,56,56,1) 100%);
                border-radius: 14px;
                border: 1px solid #f7c362;

                .img {
                    display: block;
                    width: 100%;
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: cover;
                }
            }
            &__data-container {
                width: inherit;

                .title,
                .description,
                .price {
                    padding-bottom: 0.5rem;
                }

                .title {
                    padding-top: 0.6rem;
                    font-size: 1.2rem;
                    font-weight: bold;
                }
                .description {
                    line-height: 1em;
                    font-size: 0.9rem;
                    color: rgb(145,142,158);
                }
                .price {
                    font-size: 1.2rem;
                    font-weight: bold;
                }

                .control {
                    display: flex;
                    flex-direction: row;
                    align-items: flex-start;

                    &__increment {
                        max-width: 120px;
                    }

                    &__cart {
                        padding-left: 50px;
                        padding-right: 50px;
                    }
                }
            }
        }
    }
</style>