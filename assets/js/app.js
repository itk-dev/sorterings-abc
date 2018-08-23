require('../css/app.scss');

import Vue from 'vue'
import makeI18n from './i18n'
import Items from './components/Items'
const container = document.getElementById('sorteringsabc-app')

if (container !== null) {
    const config = JSON.parse(container.getAttribute('data-configuration'))
    const i18n = makeI18n(config.locale || 'da')

    /* eslint-disable no-new */
    new Vue({
        el: container,
        config,
        i18n,
        components: { Items },
        template: '<Items/>'
    })
}
