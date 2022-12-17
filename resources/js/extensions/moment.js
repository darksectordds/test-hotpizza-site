/* подключаем плагин */
let moment = require('moment');

/* назначаем локализацию */
switch (window.language)
{
    case 'en': require('moment/locale/ru'); break;

    default: require('moment/locale/ru');
}

window.moment = function (...args) {
    return moment(...args);
};