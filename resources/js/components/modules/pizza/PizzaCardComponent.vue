<template>
    <template-pizza-card-component v-if="hasPizza"
                                   :title="name"
                                   :image="image"
                                   :description="description"
                                   :price="price"
                                   @cart="onCart"></template-pizza-card-component>
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
            uid() {
                return this.$models.$product.id(this.pizza);
            },
            name() {
                return this.$models.$product.name(this.pizza);
            },
            image() {
                const photo = this.$models.$product.firstPhoto(this.pizza);

                return this.$models.$productPhoto.url(photo);
            },
            description() {
                return this.$models.$product.description(this.pizza);
            },
            price() {
                return this.$models.$product.price(this.pizza);
            },
        },
        created() {
            this.debounceSubmitCart = _.debounce(this.submitCart, 200);
        },
        methods: {

            /*
             |---------------------------------------------------------------------------------------------
             | XMLHttpRequests
             |--------------------------------------------------------------------------------------
             */

            submitCart(count) {
                return this.axios.post(`/api/cart/${this.uid}`, {
                    count: count
                }).then(res => {
                    // TODO: увеличить счетчик корзины
                    console.log(res);
                }).catch(error => {
                    console.log(error);
                });
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Events
             |--------------------------------------------------------------------------------------
             */

            onCart(count) {
                this.debounceSubmitCart(count);
            }
        }
    }
</script>