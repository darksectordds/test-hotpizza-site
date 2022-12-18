<template>
    <component is="main">
        <!-- navbar -->
        <navbar-component ref="navbar"
                          :navigations="navigations"
                          :navbar-slot-name-rule-func="navbarSlotNameRule"
                          @navigate="onNavigate"
                          @mounted="onNavbarMounted">

            <!-- расширяем cart с помощью badge
                с информацией о количестве продуктов в заказе
                нашей корзины -->
            <template v-if="hasProductInCart"
                      :slot="navbarSlotNameRule(cartIdx)"
            >
                <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="top:18px;left:57px;">
                    {{ cartProductCount }}<span class="visually-hidden">продуктов в заказе</span>
                </span>
            </template>

        </navbar-component>

        <!-- single-page view -->
        <view-component :idx="currentIdx"
                        :navigations="navigations"
                        :style="[{'margin-top': `${marginTopOffset}px`}]"
                        @idx="onNavigate"></view-component>
    </component>
</template>

<script>
    import NavbarComponent from "./NavbarComponent";
    import ViewComponent from "./ViewComponent";

    export default {
        name: 'AppComponent',
        components: { NavbarComponent, ViewComponent },
        data() {
            return {
                // hack инициализации

                // описание ссылок под single-page app
                // следующего формата:
                // -------------------------------------------
                // - icon(css),
                // - title(text),
                // - route(name из router.js),
                // - params(параметры передающие в vue-компонент),
                // - query(url-параметры),
                // - children(список детей, как было показано чуть выше с route)
                navigations: [
                    { key:0, class: '', icon: null, title: 'Главная', route: 'index', params:{}, query: {}, children: [] },
                    { key:1, class: 'position-relative', icon: 'fa-solid fa-cart-shopping', title: null, route: 'cart', params:{}, query: {}, children: [] },
                ],
                currentIdx: 0,              // default="Главная"
                cartProductCount: 0,        // количество продуктов в карзине
                marginTopOffset: 0,         // отступ от navbar
            }
        },
        computed: {
            cartIdx() {
                return _.findIndex(this.navigations, (o) => o.route === 'cart')
            },
            hasProductInCart() {
                return this.cartProductCount > 0;
            },
        },
        created() {
            this.debounceSubmitCarProductCount = _.debounce(this.submitCarProductCount, 200);
            this.$root.$app = this;
        },
        methods: {
            navbarSlotNameRule(idx) {
                return `navbar_slot_ext_${idx}`;
            },

            navbarCartBadgeCounterInc(count) {
                this.cartProductCount += Number(count);
            },
            navbarCartBadgeCounterReset() {
                this.cartProductCount = 0;
            },

            /*
             |---------------------------------------------------------------------------------------------
             | XMLHttpRequests
             |--------------------------------------------------------------------------------------
             */

            submitCarProductCount() {
                return this.axios.get('/api/cart/count')
                    .then(res => {
                        const count = Number(res.data);
                        this.navbarCartBadgeCounterInc(count);
                    }).catch(error => {
                        console.error(error);
                    });
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Events
             |--------------------------------------------------------------------------------------
             */

            onNavigate(idx) {
                if (this.currentIdx !== idx) {
                    this.currentIdx = idx;
                }
            },
            onNavbarMounted() {
                // создаем отступ view от navbar(position: fixed)
                this.marginTopOffset = this.$refs.navbar.$el.clientHeight;
            }
        },
        mounted() {
            this.debounceSubmitCarProductCount();
        }
    }
</script>