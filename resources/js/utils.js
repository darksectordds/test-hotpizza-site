const Utils = (function () {
// constructor
    function Utils(conf = {}) {
        this._conf = conf;
    }
// public functions
    /**
     * Проверка, является ли переданное значение объектом.
     *
     * @param unk
     * @return {boolean}
     */
    Utils.prototype.isObject = function (unk) {
        return typeof unk === 'object' && !Array.isArray(unk) && unk !== null;
    };
    /**
     * Замена текущего состояния параметров GET-запроса
     * на новую строку с параметрами.
     *
     * @param params {string}
     */
    Utils.prototype.urlQuerySearchReplaceState = function (params) {
        return window.history.replaceState(null, null, params);
    };
    /**
     * Получение параметров текущего GET-запроса
     * в виде объекта {key: value}.
     *
     * @return {object}
     */
    Utils.prototype.urlQuerySearch = function(url = null) {
        const qs = (url) ?
            url.replace(/\+/g, ' ') :
            document.location.search.replace(/\+/g, ' ');

        let params = {},
            tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;

        while (tokens = re.exec(qs)) {
            params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
        }

        return params;
    };

    return Utils;
}());




const UtilsPlugin = {
    install(Vue, options) {
        Vue.prototype.$utils = new Utils();
    }
};

export default UtilsPlugin;