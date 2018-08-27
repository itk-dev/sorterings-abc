<template>
    <div class="sorteringsabc-items">

        <!--
        Dummy header only for testing purpose
        -->
        <div class="container-fluid">
            <div class="row bg-primary">
                <div class="container">
                    <div class="col-md-12">
                        <h1 class="text-white mt-4 mb-4">SORTERINGS ABC</h1>       
                    </div>
                </div>
            </div>
        </div>
        <!--
        END header
        -->

        <div class="container-fluid">
            <div class="row bg-petroleum-light">
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
                                <h4 class="alert-heading">{{ $t('No items starting with "{name}"', {name: query.name}) }}</h4>
                                <p>{{ $t('Please consider adding the missing keyword to this list') }}</p>
                                <button href="https://www2.aarhus.dk/affaldvarme/affald-og-genbrug/sortering-derhjemme/sorteringsguide/giv-os-feedback/" class="btn btn-primary btn-small">{{ $t('Add a missing keyword') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-gray-light pb-3">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted mb-1 mt-1" v-if="items" >{{ $t('Results') }} ({{ items.length }})</p>
                        </div>
                        <div class="col-md-12 sorteringsabc-item mb-3" v-if="items" v-for="item in items" v-bind:key="item.id">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="card-title">{{ item.name }}</h3>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-primary float-right">{{ item.description.name }}</span>
                                        </div>
                                    </div>
                                    <p class="card-text" v-if="item.description">                     
                                        <span v-html="item.description.description"></span>
                                    </p>
                                    <div class="row">
                                        <div v-if="item.allCategories" v-for="category in item.allCategories" v-bind:key="category['@id']" class="col-3 col-md-2 categories"> 
                                            <a v-bind:href="'#'+item.id+category.name" data-toggle="collapse" aria-expanded="false" v-bind:class="{ active: category.active }">
                                                <img class="sorteringsabc-items-icon img-fluid" v-bind:src="$config.assets[category.icon]" v-bind:alt="category.name"/>
                                            </a>
                                            <div class="collapse" v-bind:id="item.id+category.name">
                                                <h4>{{ category.name }}</h4>
                                                <div class="sorteringsabc-description" v-html="category.description">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">{{ $t('Click on icon to see extended description') }}</p>
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
    fetchItems () {
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
