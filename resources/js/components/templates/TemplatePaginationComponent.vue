<template>
    <div class="pizza__pagination">
        <!-- информация о номере страницы -->
        <div class="pizza__pagination__page">{{ `${current} из ${total}` }}</div>

        <!-- форма управления страницами -->
        <div class="pizza__pagination__form"
             v-if="hasPages"
        >

            <!-- << -->
            <form-button-component class="btn pizza__pagination__form__btn me-2"
                                   :class="{disabled: !hasPageBelowLimit}"
                                   :disabled="!hasPageBelowLimit"
                                   :config="{value: btnHtmlLeftStepDeep}"
                                   @click.native="interfaceCurrentPage(currentPage - stepDeepPage)"></form-button-component>

            <!-- < -->
            <form-button-component class="btn pizza__pagination__form__btn me-2"
                                   :class="{disabled: !hasPageBelow}"
                                   :disabled="!hasPageBelow"
                                   :config="{value: btnHtmlLeftStep}"
                                   @click.native="interfaceCurrentPage(currentPage - stepPage)"></form-button-component>

            <!-- input -->
            <form-input-component :input-config="{value: currentPage + 1}"
                                  @interfaceHandleKeyUp="onInputHandleKeyUp">
                <template v-slot:after>
                    <span>из {{ countPages }}</span>
                </template>
            </form-input-component>

            <!-- > -->
            <form-button-component class="btn pizza__pagination__form__btn ms-2"
                                   :class="{disabled: !hasPageAbove}"
                                   :disabled="!hasPageAbove"
                                   :config="{value: btnHtmlRightStep}"
                                   @click.native="interfaceCurrentPage(currentPage + stepPage)"></form-button-component>

            <!-- >> -->
            <form-button-component class="btn pizza__pagination__form__btn ms-2"
                                   :class="{disabled: !hasPageAboveLimit}"
                                   :disabled="!hasPageAboveLimit"
                                   :config="{value: btnHtmlRightStepDeep}"
                                   @click.native="interfaceCurrentPage(currentPage + stepDeepPage)"></form-button-component>
        </div>
    </div>
</template>

<script>
    import FormButtonComponent from "../forms/FormButtonComponent";
    import FormInputComponent from "../forms/FormInputComponent";

    export default {
        name: 'TemplatePaginationComponent',
        components: {FormInputComponent, FormButtonComponent},
        props: {
            current: { type: Number, default: 0 },      // текущее количество
            total: { type: Number, default: 0 },        // общее всего
            limit: { type: Number, default: 20 },       // макс. количество по страницам

            // текущая страница (рекурсия из вне + тут)
            // PS:[первоначальная позиция при загрузке компонента
            // + динамическое изменение при переключении]
            currentPage: { type: Number, default: 0 },
            stepPage: { type: Number, default: 1 },         // шаг
            stepDeepPage: { type: Number, default: 10 },    // глубокий шаг
        },
        computed: {
            btnHtmlLeftStepDeep() {
                return '<i class="fas fa-angle-double-left"></i>';
            },
            btnHtmlLeftStep() {
                return '<i class="fas fa-angle-left"></i>';
            },
            btnHtmlRightStep() {
                return '<i class="fas fa-angle-right"></i>';
            },
            btnHtmlRightStepDeep() {
                return '<i class="fas fa-angle-double-right"></i>';
            },


            countPages() {
                return Math.ceil(this.total / this.limit);
            },

            hasPages() {
                return this.countPages > 1;
            },
            hasPageBelow() {
                return (this.currentPage - 1) >= 0;
            },
            hasPageAbove() {
                return (this.currentPage + 1) < this.countPages;
            },
            hasPageBelowLimit() {
                return (this.currentPage - this.stepDeepPage) >= 0;
            },
            hasPageAboveLimit() {
                return (this.currentPage + this.stepDeepPage) < this.countPages;
            },
        },
        methods: {

            /*
             |---------------------------------------------------------------------------------------------
             | Events
             |--------------------------------------------------------------------------------------
             */

            onInputHandleKeyUp(e) {
                // Enter
                if (e.keyCode === 13) {
                    // WARNING: поскольку страницы считаются от [0... countPages), а
                    // выводится все наоборот (1... countPages], то переводим обратно
                    // в [0... countPages)
                    const value = e.target.value - 1;

                    // проверяем ограничения и переводим на другую страницу
                    if ((0 <= value) && (value < this.countPages)) {
                        this.interfaceCurrentPage(value);
                    }
                }
            },

            /*
             |---------------------------------------------------------------------------------------------
             | Interfaces
             |--------------------------------------------------------------------------------------
             */

            interfaceCurrentPage(page) {
                if (this.currentPage !== page) {
                    this.$emit('interfaceCurrentPage', page);
                }
            },
        },
    }
</script>