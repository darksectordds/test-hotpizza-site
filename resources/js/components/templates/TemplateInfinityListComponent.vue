<template>
    <div>
        <slot name="header"></slot>

        <template v-if="!isEndAbove">
            <div class="w-100 text-center"
                 v-if="isLoadingAbove"
            >
                <template-loading-spinner-component/>
            </div>

            <form-button-component v-else-if="hasAboveButtonConfig"
                                   :config="cAboveButtonConfig"
                                   @click.native="debounceFetchListAbove"></form-button-component>
        </template>

        <slot v-if="cBIT_CUSTOM_TEMPLATE" name="body"></slot>

        <!-- list -->
        <template v-else-if="hasList">
            <template v-for="(item,idx) in cList">

                <!-- slot inner: header -->
                <slot :name="_slotHeaderInner[idx]"></slot>

                <!-- slot inner: body -->
                <slot :name="_slotBodyInner[idx]"></slot>

                <!-- slot inner: footer -->
                <slot :name="_slotFooterInner[idx]"></slot>

            </template>
        </template>

        <!-- list empty message -->
        <template-list-empty-component
                v-else-if="!hasList && !isEndAbove && !isEndBelow && !isLoadingAbove && !isLoadingBelow"></template-list-empty-component>

        <template v-if="!isEndBelow">
            <div class="w-100 text-center"
                 v-if="isLoadingBelow"
            >
                <template-loading-spinner-component/>
            </div>

            <form-button-component v-else-if="hasBelowButtonConfig"
                                   :config="cBelowButtonConfig"
                                   @click.native="debounceFetchListBelow"></form-button-component>
        </template>

        <slot name="footer"></slot>
    </div>
</template>

<script>
    import TemplateLoadingSpinnerComponent from "./TemplateLoadingSpinnerComponent";
    import FormButtonComponent from "../forms/FormButtonComponent";
    import TemplateListEmptyComponent from "./TemplateListEmptyComponent";
    import TemplatePaginationComponent from "./TemplatePaginationComponent";

    export default {
        name: 'TemplateInfiniteListComponent',
        components: {
            TemplateLoadingSpinnerComponent,
            FormButtonComponent,
            TemplateListEmptyComponent,
            TemplatePaginationComponent
        },
        props: {
            config: { type: Object, default: () => ({}) },
            configDefault: { type: Object, default: () => ({
                    list: [],           // список объектов
                    list_key: 'id',     // уникальный ключ в :key от v-for каждого item'a; иначе idx
                    limit: 20,          // ограничение по страницам
                    throttling_time: 200,
                    api_params: '',
                    BIT_CUSTOM_TEMPLATE: false,
                    BIT_FETCH_ON_MOUNTED: true,
                }) },
        },
        data() {
            return {
                isLoadingAbove: false,
                isEndAbove: false,              // флаг окончания бессконечного scroll-a списка вверх

                isLoadingBelow: false,
                isEndBelow: false,              // флаг окончания бессконечного scroll-a списка вниз
            }
        },
        computed: {
            _config() {
                return Object.assign(this.configDefault, this.config);
            },
            _slotHeaderInner() {
                return _.map(this.cList, (o, idx) => this.slotName('header', this.slotKey(o, idx)) );
            },
            _slotBodyInner() {
                return _.map(this.cList, (o, idx) => this.slotName('body', this.slotKey(o, idx)) );
            },
            _slotFooterInner() {
                return _.map(this.cList, (o, idx) => this.slotName('footer', this.slotKey(o, idx)) );
            },
            hasList() {
                return this.cList.length > 0;
            },
            isAboveLoading() {
                return this.cType === 'above';
            },
            isBelowLoading() {
                return this.cType === 'below';
            },
            hasAboveButtonConfig() {
                return this.$utils.isObject(this.cAboveButtonConfig) && !_.isEmpty(this.cAboveButtonConfig);
            },
            hasBelowButtonConfig() {
                return this.$utils.isObject(this.cBelowButtonConfig) && !_.isEmpty(this.cBelowButtonConfig);
            },

            cType: {
                cache: false,
                get() { return this._config.type; },
            },
            cList: {
                cache: false,
                get() { return this._config.list; },
            },
            cListKey: {
                cache: false,
                get() { return this._config.list_key; },
            },
            cLimit: {
                cache: false,
                get() { return this._config.limit; },
            },
            cAPI: {
                cache: false,
                get() { return this._config.api; },
            },
            cAPIParams: {
                cache: false,
                get() { return this._config.api_params; },
            },
            cThrottlingTime: {
                cache: false,
                get() { return this._config.throttling_time; },
            },
            cFetchListColumn: {
                cache: false,
                get() { return this._config.fetch_list_column; },
            },
            cFetchCountColumn: {
                cache: false,
                get() { return this._config.fetch_count_column; },
            },
            cAboveButtonConfig: {
                cache: false,
                get() { return this._config.above_button_config; },
            },
            cBelowButtonConfig: {
                cache: false,
                get() { return this._config.below_button_config; },
            },
            cBIT_CUSTOM_TEMPLATE: {
                cache: false,
                get() { return this._config.BIT_CUSTOM_TEMPLATE === true; },
            },
            cBIT_FETCH_ON_MOUNTED: {
                cache: false,
                get() { return this._config.BIT_FETCH_ON_MOUNTED === true; },
            },

            urlParams() {
                let params = `?${this.urlParamLimit}`;

                if (this.cAPIParams.length > 0) {
                    params += `&${this.cAPIParams}`;
                }

                return params;
            },
            urlParamLimit() {
                return 'limit=' + this.cLimit;
            },
        },
        created() {
            this.debounceFetchList = _.debounce(this.fetchList, this.cThrottlingTime);
            this.debounceFetchListAbove = _.debounce(this.fetchListAbove, this.cThrottlingTime);
            this.debounceFetchListBelow = _.debounce(this.fetchListBelow, this.cThrottlingTime);
        },
        methods: {
            slotKey(item, idx) {
                // WARNING: основной смысл в том, чтобы заменить idx на иной уникальный ключ,
                // желательно 'item.id'!
                // Это необходимо из-за того, что когда изменяется список list, то
                // не происходит re-render'a, и визуально никак не обновляется, хотя
                // сам list обновляется и добавляется новый slot под него.

                if (item.hasOwnProperty(this.cListKey)) {
                    return item[this.cListKey];
                }

                return idx;
            },
            slotName(name, idx) {
                return `${name}_${idx}`;
            },
            reload() {
                this.interfaceList([]);         // чистим список
                this.debounceFetchList();       // подгружаем новый список
            },
            reset() {
                this.reload();
            },

            /*
             |---------------------------------------------------------------------------------------------
             | XMLHttpRequests
             |--------------------------------------------------------------------------------------
             */

            submitFetchList(
                colLoading,
                colEnd,
                extUrlParams = '',
                iListFunc = this.interfaceList
            ) {
                if (!this.$data[colEnd] && !this.$data[colLoading]) {
                    this.$set(this.$data, colLoading, true);

                    return this.axios.get(this.cAPI + this.urlParams + extUrlParams)
                        .then((response) => {
                            this.$set(this.$data, colLoading, false);
                            const data = response.data;

                            // заполнение списка данных
                            if (data.hasOwnProperty(this.cFetchListColumn)) {

                                const list = data[this.cFetchListColumn];

                                // больше выгружать нечего
                                // подымаем флаг завершения загрузки
                                if (list.length !== this.cLimit) {
                                    this.$set(this.$data, colEnd, true);
                                }

                                iListFunc(list);
                            }

                            // общее количество
                            if (extUrlParams === '&p=latest' && data.hasOwnProperty(this.cFetchCountColumn)) {
                                this.interfaceCount(data[this.cFetchCountColumn]);
                            }
                        })
                        .catch((error) => {
                            if (error.hasOwnProperty('response') &&
                                error.response.hasOwnProperty('data') &&
                                error.response.data.hasOwnProperty('message')
                            ) {
                                this.$set(this.$data, colLoading, false);
                                console.error(error);
                            }
                        });
                }
            },

            fetchListAbove() {
                this.submitFetchList(
                    'isLoadingAbove', 'isEndAbove', '&p=above', this.interfaceListAbove);
            },
            fetchListBelow() {
                this.submitFetchList(
                    'isLoadingBelow','isEndBelow', '&p=below', this.interfaceListBelow);
            },
            fetchListLatest() {
                this.submitFetchList(
                    'isLoadingBelow','isEndBelow', '&p=latest');
            },
            fetchList() {
                // latest
                if (!this.hasList) {
                    this.fetchListLatest();
                }
                // above
                else if (this.isAboveLoading) {
                    this.fetchListAbove();
                }
                // below
                else if(this.isBelowLoading) {
                    this.fetchListBelow();
                }
                // default
                else {
                    this.submitFetchList('isLoadingBelow','isEndBelow');
                }
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Interfaces
             |--------------------------------------------------------------------------------------
             */

            interfaceList(list) {
                if (list.length < this.cLimit) {
                    this.isEndAbove = true;
                    this.isEndBelow = true;
                }

                this.$emit('list', list);
            },
            interfaceCount(count) {
                this.$emit('count', count);
            },
            interfaceListAbove(list) {
                if (list.length < this.cLimit) {
                    this.isEndAbove = true;
                }

                this.$emit('listAbove', list);
            },
            interfaceListBelow(list) {
                if (list.length < this.cLimit) {
                    this.isEndBelow = true;
                }

                this.$emit('listBelow', list);
            },

        },
        mounted() {
            if (this.cBIT_FETCH_ON_MOUNTED) {
                this.debounceFetchList();
            }
        }
    }
</script>