<template>
    <template-pizza-card-component v-if="hasPizza"
                                   :title="name"
                                   :image="image"
                                   :description="description"
                                   :price="price"></template-pizza-card-component>
</template>

<script>
    import TemplatePizzaCardComponent from "../../templates/pizza/TemplatePizzaCardComponent";

    export default {
        name: 'PizzaCardComponent',
        components: {
            TemplatePizzaCardComponent
        },
        props: {
            pizza: {type: Object, default: () => ({})},
        },
        computed: {
            hasPizza() {
                return !!this.pizza;
            },
            name() {
                return this.$models.$product.name(this.pizza);
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