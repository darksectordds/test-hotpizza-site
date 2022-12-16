<template>
    <div>
        <slot name="header"></slot>

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

        <!-- loading icon -->
        <template-loading-spinner-component
                v-else-if="isLoading"></template-loading-spinner-component>

        <!-- reload button -->
        <form-button-component
                class="w-100 btn btn-primary mb-2"
                v-else-if="isError"
                :config="{value: 'Повторить'}"
                @click.native="debounceFetchList"></form-button-component>

        <!-- list empty message -->
        <template-list-empty-component
                v-else></template-list-empty-component>

        <!-- pages -->
        <template-pagination-component class="my-4"
                                       :current="(currentPage * cLimit) + cList.length"
                                       :total="cCount"
                                       :current-page="currentPage"
                                       @interfaceCurrentPage="onCurrentPage"></template-pagination-component>

        <slot name="footer"></slot>
    </div>
</template>

<script>
    import TemplateLoadingSpinnerComponent from "./TemplateLoadingSpinnerComponent";
    import FormButtonComponent from "../forms/FormButtonComponent";
    import TemplateListEmptyComponent from "./TemplateListEmptyComponent";
    import TemplatePaginationComponent from "./TemplatePaginationComponent";

    export default {
        name: 'TemplateListComponent',
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
                    count: 0,           // количество всего элементов
                    limit: 20,          // ограничение по страницам
                    page: 0,            // начальная страница просмотра
                    throttling_time: 200,
                    cBIT_CUSTOM_TEMPLATE: false,
                }) },
        },
        data() {
            return {
                isLoading: false,
                isError: false,
                currentPage: 0,
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
            hasList() { return this.cList.length > 0; },

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
            cCount: {
                cache: false,
                get() { return this._config.count; },
            },
            cPage: {
                cache: false,
                get() { return this._config.page; },
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
            cAfterFetchFunction: {
                cache: false,
                get() { return this._config.after_fetch_function; },
            },
            cBIT_CUSTOM_TEMPLATE: {
                cache: false,
                get() { return this._config.BIT_CUSTOM_TEMPLATE === true; },
            },

            urlParams() {
                return `?${this.urlParamPage}&${this.urlParamLimit}&${this.cAPIParams}`;
            },
            urlParamPage() {
                return 'page=' + this.currentPage;
            },
            urlParamLimit() {
                return 'limit=' + this.cLimit;
            },
        },
        created() {
            this.currentPage = this.cPage;
            this.debounceFetchList = _.debounce(this.fetchList, this.cThrottlingTime);
        },
        watch: {
            // изменение страницы извне, например при переходе
            // по ссылке, где есть `?page=1`, а потом происходит
            // переопределение страницы
            cPage(page) {
                this.onCurrentPage(page);
            },
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
                this.currentPage = 0;
                this.reload();
            },

            /*
             |---------------------------------------------------------------------------------------------
             | XMLHttpRequests
             |--------------------------------------------------------------------------------------
             */

            fetchList() {
                if (!this.isLoading) {
                    this.isLoading = true;
                    this.isError = false;

                    return this.axios.get(this.cAPI + this.urlParams)
                        .then((response) => {
                            this.isLoading = false;
                            const data = response.data;

                            // заполнение списка данных
                            if (data.hasOwnProperty(this.cFetchListColumn)) {
                                this.interfaceList(response.data[this.cFetchListColumn]);
                            }
                            // заполнение общего количества объектов
                            if (data.hasOwnProperty(this.cFetchCountColumn)) {
                                this.interfaceCount(response.data[this.cFetchCountColumn]);
                            }

                            if (typeof this.cAfterFetchFunction === "function" && !!this.cAfterFetchFunction) {
                                this.cAfterFetchFunction();
                            }
                        })
                        .catch((error) => {
                            this.isLoading = false;
                            this.isError = true;
                            return this.$awn.alert(error.response.data.message);
                        });
                }
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Events
             |--------------------------------------------------------------------------------------
             */

            onCurrentPage(page) {
                if (page !== this.currentPage) {
                    // сохраняем новую страницу
                    this.currentPage = page;

                    // перезагружаем список
                    this.reload();
                }
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Interfaces
             |--------------------------------------------------------------------------------------
             */

            interfaceList(list) {
                this.$emit('list', list);
            },
            interfaceCount(count) {
                this.$emit('count', count);
            },

        },
        mounted() {
            this.debounceFetchList();
        }
    }
</script>