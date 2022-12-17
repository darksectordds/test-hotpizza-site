<template>
    <template-cart-product-component :name="name"
                                     :count="count"
                                     :price="price"
                                     :date="date"></template-cart-product-component>
</template>
<script>
    import TemplateCartProductComponent from "../../templates/TemplateCartProductComponent";

    export default {
        name: 'CartProductComponent',
        components: { TemplateCartProductComponent },
        props: {
            cartProduct: { type: Object, default: () => ({}) },
        },
        computed: {
            name() {},
            count() {},
            price() {},
            date() {},
        },
    }
</script>