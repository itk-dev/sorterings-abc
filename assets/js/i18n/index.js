// @see https://codeburst.io/dependency-injection-with-vue-js-f6b44a0dae6d
import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

const messages = {
  da: {
    'Search': 'Søg',
    'Search …': 'Søg …',
    'Loading items …': 'Henter resultater …',
    'No items starting with "{name}"': 'Ingen items starter med {name}',
    'Write garbagetype fx spray can': 'Skriv affaldstype f.eks spraydåse',
    'Please consider adding the missing keyword to this list':'Søgeordret kan ikke findes. Du kan få det tilføjet til listen ved at indsende det.',
    'Add a missing keyword':'Indsend et søgeord',
    'Results':'Resultater',
    'Click on icon to see extended description':'Klik på ikonet for at se beskrivelse'
  },
  en: {
    'Search': 'Search',
    'Search …': 'Search …',
    'Loading items …': 'Loading items …',
    'No items starting with "{name}"': 'No items starting with "{name}"'
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
