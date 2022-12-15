<template>
    <component is="main">
        <!-- navbar -->
        <navbar-component ref="navbar"
                          :navigations="navigations"
                          @navigate="onNavigate"
                          @mounted="onNavbarMounted"></navbar-component>

        <!-- single-page view -->
        <view-component :idx="currentIdx"
                        :navigations="navigations"
                        :style="[{'margin-top': `${marginTopOffset}px`}]"></view-component>
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
                    { key:0, icon: null, title: 'Главная', route: 'index', params:{}, query: {}, children: [] },
                    { key:1, icon: 'fa-solid fa-cart-shopping', title: null, route: 'cart', params:{}, query: {}, children: [] },
                ],
                currentIdx: 0, // default="Главная"
                marginTopOffset: 0,
            }
        },
        methods: {
            onNavigate(idx) {
                this.currentIdx = idx;
            },
            onNavbarMounted() {
                // создаем отступ view от navbar(position: fixed)
                this.marginTopOffset = this.$refs.navbar.$el.clientHeight;
            }
        }
    }
</script>