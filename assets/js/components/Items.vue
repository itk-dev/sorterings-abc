<template>
    <div class="sorteringsabc-items">
        <div class="container-fluid">
            <div class="row bg-petroleum-light sticky-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 mt-4 mb-3">
                            <div class="input-group sorteringsabc-items-search">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="input-search"><img v-bind:src='$config.assets.search' v-bind:alt="$t('Search')">{{ $t('Search') }}</span>
                                </div>
                                <input type="text" class="form-control form-control-lg" v-bind:placeholder="$t('Write garbagetype fx spray can')" v-model="query.name" v-on:keyup="fetchItems">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-1">
                            <div class="alert alert-primary" v-if="loading">
                                {{ $t('Loading items …') }}
                            </div>
                            <div class="alert alert-danger" v-if="error">
                                {{ error }}
                            </div>
                            <div class="alert alert-light" v-if="items && items.length === 0">
                                <h4 class="alert-heading text-dark">{{ $t('No suggestions') }}</h4>
                                <p class="text-dark">{{ $t('Please consider adding the missing keyword to this list') }}</p>
                                <a role="button" href="https://www2.aarhus.dk/affaldvarme/affald-og-genbrug/sortering-derhjemme/sorteringsguide/giv-os-feedback/" class="btn btn-primary btn-small" target="_parent">{{ $t('Add a missing keyword') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-gray-light pb-3">
                <div class="container" v-if="!items">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-light mt-2">
                                <p class="text-dark mb-1 mt-1">{{ $t('Er du i tvivl om, hvor en bestemt affaldstype skal hen, kan du søge her. Kan du ikke finde det, du leder efter – så prøv at søge på noget lignende.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container" v-if="items">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted mb-1 mt-1">{{ $t('Results') }} ({{ items.length }})</p>
                        </div>
                        <div class="col-md-12 sorteringsabc-item mb-3" v-for="item in items" v-bind:key="item.id">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">{{ item.name }}</h3>
                                    <p class="card-text" v-if="item.description">
                                        <span v-html="item.description.description"></span>
                                    </p>
                                    <div class="row" v-bind:id="getUniqueId(item.name)">
                                        <div v-if="item.allCategories" v-for="category in item.allCategories" v-bind:key="category.id" class="col-3 col-md-2 col-lg-1 categories" >
                                            <!-- HIDE UNTIL CATEGORY DESCRIPTIONS ARE READY <a v-bind:href="'#'+getUniqueId(item.name, category.name)" data-toggle="collapse" aria-expanded="false" v-bind:class="{ active: category.active }">-->
                                                <span v-bind:class="{ active: category.active }">
                                                <img class="sorteringsabc-items-icon img-fluid" v-bind:src="$config.assets[category.icon]" v-bind:alt="category.name"/>
                                                </span>
                                             <!-- HIDE UNTIL CATEGORY DESCRIPTIONS ARE READY </a>-->
                                        </div>
                                        <div v-if="item.allCategories" v-for="category in item.allCategories" v-bind:key="getUniqueId(item.name, category.name)" class="col-12 categories-description">
                                            <div class="collapse mt-2" v-bind:id="getUniqueId(item.name, category.name)"  v-bind:data-parent="'#'+getUniqueId(item.name)">
                                                <h4>{{ category.name }}</h4>
                                                <div class="sorteringsabc-description" v-html="category.description">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <!-- HIDE UNTIL CATEGORY DESCRIPTIONS ARE READY <p class="text-muted mt-2 mb-0">{{ $t('Click on icon to see extended description') }}</p>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

setInterval(function() {
    // first parameter is the message to be passed
    // second paramter is the domain of the parent 
    // in this case "*" has been used for demo sake (denoting no preference)
    // in production always pass the target domain for which the message is intended 
    window.top.postMessage(document.body.scrollHeight, "*");
}, 500);

// https://codeburst.io/dependency-injection-with-vue-js-f6b44a0dae6d
import Vue from 'vue'
const querystring = require('querystring')

Vue.mixin({
  beforeCreate () {
    const options = this.$options
    if (options.config) {
      this.$config = options.config
    } else if (options.parent && options.parent.$config) {
      this.$config = options.parent.$config
    }
  }
})

export default {
  name: 'hearing-items',
  data () {
    return {
      loading: false,
      allCategories: null,
      items: null,
      query: {
        name: ''
      },
      error: null
    }
  },
  created () {
    // Load all categories
    const self = this
    fetch(this.$config.data_urls.item_categories)
      .then(response => {
        if (!response.ok) {
          throw (response.statusText)
        }
        return response.json()
      })
      .then(data => {
        self.allCategories = data['hydra:member']
        self.allCategories.forEach((category) => {
          delete category.items
          category.active = false
        })
      })
      .then(self.fetchItems)
      .catch(error => { self.setData(error, null) })
  },
  methods: {
    // Get a unique element id consisting of only letters and digits
    // (e.g. needed for "collapse") and starting with a letter.
    getUniqueId (...names) {
      let id = names.join('--').replace(/[^a-z0-9]+/g, '-');

      // Make sure that id starts with a letter.
      return /^[^a-z]/i.test(id) ? 'c--'+id : id;
    },
    fetchItems () {
      // Don't search if query is empty.
      if (this.query.name.length <= 0) {
        this.error = this.items = null
        return
      }

      let url = this.$config.data_urls.items
      if (this.query) {
        url += (url.indexOf('?') < 0 ? '?' : '&')
          +querystring.stringify(this.query);
      }

      this.error = this.items = null
      this.loading = true

      const self = this
      fetch(url)
        .then(response => {
          self.loading = false
          if (!response.ok) {
            throw (response.statusText)
          }
          return response.json()
        })
        .then(data => { self.setData(null, data) })
        .catch(error => { self.setData(error, null) })
    },
    setData (error, data) {
      if (error) {
        this.error = error.toString()
      } else {
        this.data = data
        this.items = this.data['hydra:member']
        if (this.allCategories) {
          // Merge item's selected categories into all categories.
          this.items.forEach((item) => {
            item.allCategories = this.allCategories.map((category) => {
              // Clone category
              category = JSON.parse(JSON.stringify(category));
              for (var c of item.categories) {
                if (c['@id'] === category['@id']) {
                  category.active = true
                }
              }
              return category
            })
          })
        }
      }
    }
  }
}
</script>
