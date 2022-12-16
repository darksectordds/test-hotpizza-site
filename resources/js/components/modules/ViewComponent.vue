<template>
    <div class="container">
        <div class="row no-gutters" style="max-width: 980px; margin: auto;">
            <router-view :key="$route.fullPath"></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ViewComponent',
        props: {
            // WARNING:
            // требует объекты следующего формата
            // -------------------------------------------
            // - icon(css),
            // - title(text),
            // - route(name из router.js),
            // - params(параметры передающие в vue-компонент),
            // - query(url-параметры),
            // - children(список детей, как было показано чуть выше с route)
            navigations: {type: Array, default: []},
            idx: {type: Number, default: 0},
        },
        computed: {
            /**
             * URL-ссылка у $router.
             * ------------------------------------------------------------
             * WARNING: определяется только единожды при первой прогрузке!
             * Именно это и необходимо! Чтобы была возможность определить
             * по URL нужный route, route-params и route-query
             */
            url() {
                // определяем текущую ссылку,
                // убираем первый слеш перед ней
                // пример: /ticket/1/messenger => ticket/1/messenger
                return this.$router.history.current.fullPath.substr(1);
            },
            /**
             * Ограничиваем себя только теми routes из $router.options.routes,
             * которые были определены в кастомном routers.
             *
             * @return []
             */
            someRoutes() {
                // сокращаем количество routes в зависимости от
                // нашего кастомного routers
                return _.filter(this.$router.options.routes, (route) => {
                    return _.some(this.navigations, (o) => {
                        return o.route === route.name || _.some(o.children, (child) => child === route.name);
                    });
                });
            },
            /**
             * Определяет необходимые параметры route из URL, чтобы можно
             * было в дальнейшем его вставить в $router.push(...)
             *
             * @return {object} route
             */
            route() {
                return this.routeSearchByUrl(this.url);
            },
            /**
             * Удалось найти по URL-параметрам нужный route,
             * по которому можно перейти.
             *
             * @return Boolean
             */
            isUrlAutoDetected() {
                return !_.isEmpty(this.route);
            },
        },
        watch: {
            idx(idx) {
                this.showRouteIdx(idx);
            },
        },
        methods: {

            showRoute(route) {
                this.$router.push(route);
            },
            showRouteIdx(idx) {
                this.$router.push({
                    name: this.navigations[idx].route,
                    params: this.navigations[idx].params,
                    query: this.navigations[idx].query
                });
            },
            showCurrentRoute() {
                this.showRouteIdx(this.idx);
            },

            routeNameByUrl(url) {
                return url.split('?')[0];
            },
            routeQueryByUrl(url) {
                const url_parts = url.split('?');

                if (url_parts.length === 2) {
                    return this.$utils.urlQuerySearch(url_parts[1]);
                }

                return {};
            },
            routeSearchByUrl(url) {
                const _url_main = this.routeNameByUrl(url);
                const _url_query = this.routeQueryByUrl(url);

                const url_levels = _url_main.split('/');

                // заполняем параметры route
                let params = {};
                let route_name = null;

                const isFound = _.some(this.someRoutes, (route) => {
                    // делим текущий route на объекты
                    // например:
                    //  ticket/4/messenger => [ticket, 4, messenger]
                    const route_levels = route.path.split('/');

                    // 1: проверка на размерность
                    if (route_levels.length !== url_levels.length) {
                        return false;
                    }

                    if (_.every(route_levels, (route_path, index) => {
                        // найден патерн переменных
                        if (route_path.substr(0, 1) === ':') {
                            // заносим и паттерн со значением в params
                            // в данном случае [ticketId: 4]
                            params[route_path.substr(1)] = Number(url_levels[index]);
                            return true;
                        }
                        // иначе это обычый путь, поэтому просто сраниваем строки
                        return route_path === url_levels[index];
                    })
                    ) {
                        route_name = route.name;
                        return true;
                    }
                });

                return (isFound) ? {name: route_name, params: params, query: _url_query} : {};
            },
            routeRedirect(url) {
                const route = this.routeSearchByUrl(url);

                if (!_.isEmpty(route)) {
                    this.showRoute(route);
                }
            },
        },
        mounted() {
            if (this.$route.name === null) {

                // если определили необходимый route по URL-параметрам
                // то переходим с этими параметрами
                if (this.isUrlAutoDetected) {

                    // и погружаем нужный route с правильными параметрами
                    // определенными прямо из url и urlQuery
                    this.showRoute(this.route);
                }

                // иначе переходим по стандартной загружаемой route
                else
                    this.showCurrentRoute();
            }
        }
    }
</script>