<template>
    <template-article-component class="my-4">

        <!-- список неоплаченных продуктов в корзине -->
        <cart-product-list-component ref="list"
                                     class="pizza__cart-product mb-4">
            <template v-slot:header>
                <template-cart-product-item-component>Название</template-cart-product-item-component>
                <template-cart-product-item-component>Количество</template-cart-product-item-component>
                <template-cart-product-item-component>Цена</template-cart-product-item-component>
                <template-cart-product-item-component>Результирующая цена</template-cart-product-item-component>
                <template-cart-product-item-component>Дата</template-cart-product-item-component>
            </template>
        </cart-product-list-component>

        <!-- оплатить -->
        <div class="form-group text-end">
            <span class="fs-4 fw-bold me-5"
                  v-if="isMounted"
            >{{ priceTotal }} руб.</span>

            <form-button-component class="btn btn-success w-25"
                                   @click.native="onRoute">Оплатить</form-button-component>
        </div>
    </template-article-component>
</template>
<script>
    import TemplateArticleComponent from "../../templates/TemplateArticleComponent";
    import CartProductListComponent from "../../modules/cart/CartProductListComponent";
    import FormButtonComponent from "../../forms/FormButtonComponent";
    import TemplateCartProductItemComponent from "../../templates/TemplateCartProductItemComponent";

    export default {
        components: {
            TemplateCartProductItemComponent,
            FormButtonComponent,
            TemplateArticleComponent,
            CartProductListComponent
        },
        data() {
            return {
                // WARNING: _isMounted текущего vue контекста
                // не вызвывает rerender, поэтому hack
                isMounted: false,
            }
        },
        computed: {
            list: {
                cache: false,
                get() { return this.$refs.list.list },
            },
            priceTotal() {
                return _.sumBy(this.list ?? [], (o) => {
                    const count = this.$models.$cart.count(o);
                    const price = this.$models.$cart.price(o);

                    return count * price;
                });
            },
        },
        methods: {
            onRoute() {
                this.$router.push({name: 'cart-submit', params: {}, query: {}});
            },
        },
        mounted() {
            this.isMounted = true;
        }
    }
</script>