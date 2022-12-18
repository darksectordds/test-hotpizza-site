<template>
    <template-infinity-list-component :ref="'list'"
                                      :config="_infiniteListConfig"
                                      @list="onList"
                                      @count="onCount"
                                      @listAbove="onList"
                                      @listBelow="onList"
    >
        <!-- redirect to header inside -->
        <template v-slot:header>
            <slot name="header"></slot>
        </template>

        <template v-for="(item,idx) in listSorted"
                  :slot="$refs.list._slotBodyInner[idx]"
        >

            <template-cart-product-item-component>{{ $models.$cart.name(item) }}</template-cart-product-item-component>
            <template-cart-product-item-component>{{ $models.$cart.count(item) }}</template-cart-product-item-component>
            <template-cart-product-item-component>{{ $models.$cart.price(item) }} руб</template-cart-product-item-component>
            <template-cart-product-item-component>{{ $models.$cart.count(item) * $models.$cart.price(item) }} руб</template-cart-product-item-component>
            <template-cart-product-item-component>
                <template-moment-date-component :date="$models.$cart.date(item)"></template-moment-date-component>
            </template-cart-product-item-component>

        </template>
    </template-infinity-list-component>
</template>

<script>
    import TemplateInfinityListComponent from "../../templates/TemplateInfinityListComponent";
    import TemplateCartProductItemComponent from "../../templates/TemplateCartProductItemComponent";
    import TemplateMomentDateComponent from "../../templates/TemplateMomentDateComponent";

    export default {
        name: 'CartProductListComponent',
        components: {
            TemplateMomentDateComponent,
            TemplateInfinityListComponent,
            TemplateCartProductItemComponent,
        },
        props: {
            config: {type: Object, default: () => ({})},
            configDefault: {
                type: Object, default: () => ({
                    api: '/api/cart',
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
                    fetch_list_column: 'cart',
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
                return this.$models.$product.id(a) - this.$models.$product.id(b);
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