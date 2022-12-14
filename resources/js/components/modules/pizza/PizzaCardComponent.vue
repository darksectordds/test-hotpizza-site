<template>
    <template-pizza-card-component v-if="hasPizza"
                                   :title="title"
                                   :image="image"
                                   :description="description"
                                   :price="price"></template-pizza-card-component>
</template>

<script>
    export default {
        name: 'PizzaCardComponent',
        props: {
            pizza: {type: Object, default: () => ({})},
        },
        computed: {
            hasPizza() {
                return !!this.pizza;
            },
            title() {
                return this.$models.$product.title(this.pizza);
            },
            image() {
                return this.$models.$product.firstImage(this.pizza);
            },
            description() {
                return this.$models.$product.description(this.pizza);
            },
            price() {
                return this.$models.$product.price(this.pizza);
            },
        },
    }
</script>