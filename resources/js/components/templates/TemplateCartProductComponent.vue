<template>
    <div class="cart-product">
        <div class="cart-product__item">{{ name }}</div>
        <div class="cart-product__item">{{ count }}</div>
        <div class="cart-product__item">{{ price }} руб</div>
        <div class="cart-product__item">{{ count * price }} руб</div>
        <div class="cart-product__item">{{ dateLocal }}</div>
    </div>
</template>

<script>
    import AutoUpdateDateMixin from "../utils/mixins/AutoUpdateDateMixin";

    export default {
        name: 'TemplateCartProductComponent',
        mixins: [
            AutoUpdateDateMixin
        ],
        props: {
            name: { type: String, default: 'Тест пиццы особой' },
            count: { type: Number, default: 3 },
            price: { type: Number, default: 686 },
        },
    }
</script>

<style lang="scss" scoped>
    $border-color: #c3c3c3;

    .cart-product {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        margin: 10px;

        &__item {
            padding: 0.6rem 8px 0.6rem 8px;

            border-top: 1px solid $border-color;
            border-bottom: 1px solid $border-color;
            border-right: 1px solid $border-color;
            &:last-child {
                border-right: none;
            }
        }
    }
</style>