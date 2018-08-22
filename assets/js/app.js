/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

import Vue from 'vue'
import makeI18n from './i18n'
import Items from './components/Items'
const container = document.getElementById('app')

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
