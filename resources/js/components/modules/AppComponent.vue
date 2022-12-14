<template>
    <view-component :navigation="navigation"></view-component>
</template>

<script>
    import ViewComponent from "./ViewComponent";

    export default {
        name: 'AppComponent',
        components: { ViewComponent },
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
                navigation: [
                    { icon: null, title: 'Главная', route: 'index', params:{}, query: {}, children: [] },
                ],
            }
        },
    }
</script>