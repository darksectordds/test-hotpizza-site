<template>
    <template-infinity-list-component :ref="'list'"
                                      :config="_infiniteListConfig"
                                      @list="onList"
                                      @count="onCount"
                                      @listAbove="onList"
                                      @listBelow="onList"
    >
        <template v-for="(item,idx) in listSorted"
                  :slot="$refs.list._slotBodyInner[idx]"
        >
            <pizza-card-component :pizza="item"
                                  :key="this.$models.$product.id(item)"></pizza-card-component>
        </template>
    </template-infinity-list-component>
</template>

<script>
    import TemplateInfinityListComponent from "../../templates/TemplateInfinityListComponent";
    import PizzaCardComponent from "../pizza/PizzaCardComponent";

    export default {
        name: 'ListPizzaCardComponent',
        components: {
            TemplateInfinityListComponent,
            PizzaCardComponent,
        },
        props: {
            config: {type: Object, default: () => ({})},
            configDefault: {
                type: Object, default: () => ({
                    api: '/api/product',
                    list: [],                                       // список объектов
                    limit: 20,                                      // ограничение по страницам
                })
            },
        },
        data() {
            return {
                list: [],
                count: 0,       // обшее количество, а не сколько загружено!
                type: 'above',
            }
        },
        computed: {

            _config() {
                return Object.assign(this.configDefault, this.config);
            },

            cList: {
                cache: false,
                get() { return this._config.list; },
            },
            cCount: {
                cache: false,
                get() { return this._config.count; },
            },
            cLimit: {
                cache: false,
                get() { return this._config.limit; },
            },
            cAPI: {
                cache: false,
                get() { return this._config.api; },
            },

            _infiniteListConfig() {
                return {
                    api: this.cAPI,
                    api_params: this.urlParams,
                    list: this.list,
                    limit: this._config.limit,
                    type: this.type,
                    fetch_list_column: 'product',
                    BIT_FETCH_ON_MOUNTED: true,
                };
            },

            hasList() {
                return this.list.length > 0;
            },
            listSorted() {
                return this.list.sort(this.sortByASC);
            },

            /*
             |---------------------------------------------------------------------------------------------
             | URL параметры
             |--------------------------------------------------------------------------------------
             */

            urlParams() {
                let params = '';

                if (this.type === 'above' && this.urlParamFirstID.length > 0) {
                    params += '&' + this.urlParamFirstID;
                }
                else if (this.type === 'below' && this.urlParamLastID.length > 0) {
                    params += '&' + this.urlParamLastID;
                }

                return params;
            },
            urlParamFirstID() {
                return (this.hasList) ? 'uid=' + this.$models.$product.id(this.list[0]) : '';
            },
            urlParamLastID() {
                return (this.hasList) ? 'uid=' + this.$models.$product.id(this.list[this.list.length - 1]) : '';
            },
        },
        methods: {
            sortByASC(a, b) {
                return this.this.$models.$product.id(a) - this.this.$models.$product.id(b);
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Events
             |---------------------------------------------------------------------------------------
             */

            onList(list) {
                this.list.push(...list);
                this.interfaceList();
            },
            onCount(count) {
                this.count = count;
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Interfaces
             |---------------------------------------------------------------------------------------
             */

            interfaceList() {
                this.$emit('list', this.list);
            },
        }
    }
</script>