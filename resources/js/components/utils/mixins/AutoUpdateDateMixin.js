/*
 |---------------------------------------------------------------------------------------------
 | AutoUpdateDate Mixin
 |---------------------------------------------------------------------------------------------
 |
 | Автоматическое обновление даты по GTM формату.
 |
 | Использование: dateLocal там, где именно ее нужно нарисовать
 |
 */

export default {
    props: {
        date: {
            type: String,
            default: '',
        },
    },
    data: function () {
        return {
            dateLocal: this.date,
            dateGMTOffset: '',                      // тип GTM смещение, например: 'GTM-0300'
            timerAutoUpdateDates: '',               // таймер
        }
    },
    computed: {
        /**
         * Проверяет наличие GTM смещения зоны от GTM-0000
         *
         * @return {boolean}
         */
        hasDateGMTOffset: function () { return this.dateGMTOffset.length > 0; },
    },
    watch: {
        /**
         * Значение в props передаются намного позже в цикле,
         * где дочерние элементы используют данный mixin,
         * из-за чего значение props.date формируется
         * исходя из самого первого элемента, а не
         * из тех данных, которые передаются.
         *
         * Этот watch.date автоматически обновляет значение,
         * когда в последний момент передается значение
         * в поле props.date
         *
         * @param newDate
         * @param oldDate
         */
        date: function (newDate, oldDate) {
            if (newDate !== oldDate) {
                this.onAutoUpdateDate();
            }
        },
    },
    created: function () {
        /* регистрируем таймер автообновления даты */
        this.timerAutoUpdateDates = setInterval(this.onAutoUpdateDate, 60000);

        /* получаем UTC значение таймзоны в формате GTM. Пример: '... GTM+0300' */
        this.dateGMTOffset = this.getGTMOffset();
    },
    methods: {
        /**
         * Получение GTM зоны в формате (+/-)(4 номера).
         * Пример: если "GTM+0300", то на выходе будет "-0300".
         *
         * @return {string}
         */
        getGTMOffset() {
            let gtmDateStr = new Date(new Date().getTime()).toString();
            let gtmOffset = gtmDateStr.substr(gtmDateStr.indexOf('GMT') + 3, 5);

            return ((gtmOffset[0] === '+') ? '-' : '+') + gtmOffset.substr(1);
        },
        /**
         * Синхронизирует дату с текущим GTM, если такой определен
         *
         * @param date
         * @return {Date}
         */
        getSynchronizeDate(date) {
            if (this.hasDateGMTOffset) {
                return new Date(new Date(date).toUTCString() + this.dateGMTOffset);
            }

            return date;
        },

        /*
         |---------------------------------------------------------------------------------------------
         | Event listener
         |---------------------------------------------------------------------------------------------
         */

        onAutoUpdateDate: function() {
            this.dateLocal = moment(this.getSynchronizeDate(this.date), 'YYYY-MM-DD h:mm:ss').fromNow();
        },
    },
    mounted: function () {
        this.onAutoUpdateDate();
    },
    beforeDestroy: function() {
        /* убираем таймер автообновления даты */
        clearInterval(this.timerAutoUpdateDates);
    },
}