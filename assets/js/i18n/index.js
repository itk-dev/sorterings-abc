// @see https://codeburst.io/dependency-injection-with-vue-js-f6b44a0dae6d
import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

const messages = {
  da: {
    'Loading items …': 'Henter items …'
  },
  en: {
    'Loading items …': 'Loading items …'
  }
}

// http://www.ecma-international.org/ecma-402/2.0/#sec-intl-datetimeformat-constructor
const dateTimeFormats = {
  da: {
    short: {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    },
    long: {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric'
    }
  }
}

export default function makeI18n (locale) {
  return new VueI18n({
    locale,
    messages,
    dateTimeFormats
  })
}
