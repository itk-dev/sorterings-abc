<template>
    <div class="sorteringsabc-items">
        <div class="col mt-4">
            <div class="input-group sorteringsabc-items-search">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="input-search"><img v-bind:src='$config.assets.search' v-bind:alt="$t('Search')"></span>
                </div>
                <input type="text" class="form-control form-control-lg" v-bind:placeholder="$t('Search …')" v-model="query.name" v-on:keyup="fetchData">
            </div>
        </div>
        <div class="col">
            <div class="alert alert-info" v-if="loading">
                {{ $t('Loading items …') }}
            </div>

            <div class="alert alert-danger" v-if="error">
                {{ error }}
            </div>

            <div class="alert alert-primary" v-if="items && items.length === 0">
                <h4 class="alert-heading">{{ $t('No items starting with "{name}"', {name: query.name}) }}</h4>
                <p>{{ $t('Please consider adding the missing keyword to this list') }}</p>
                <hr>
                <button href="#TODO-add-url-to-new-keyword-form" class="btn btn-primary btn-small">{{ $t('Add a missing keyword') }}</button>
            </div>
        </div>
        <article class="col sorteringsabc-item" v-if="items" v-for="(item, index) in items" v-bind:key="item.id">
            <div class="sorteringsabc-name">{{ item.name }}</div>

            <section v-if="item.description">
                <div class="sorteringsabc-item-description">
                    <div class="sorteringsabc-name">{{ item.description.name }}</div>
                    <div class="sorteringsabc-description" v-html="item.description.description"></div>
                </div>
            </section>

            <div v-if="item.categories" v-for="(category, index) in item.categories" v-bind:key="category['@id']">
                <section class="sorteringsabc-item-category">
                    <span class="sorteringsabc-icon" v-bind:data-icon="category.icon"></span>
                    <div class="sorteringsabc-name">{{ category.name }}</div>
                    <div class="sorteringsabc-description" v-html="category.description"></div>
                </section>
            </div>
        </article>
    </div>
</template>

<script>
// https://codeburst.io/dependency-injection-with-vue-js-f6b44a0dae6d
import Vue from 'vue'

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
      items: null,
      query: {
        name: ''
      },
      error: null
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      const url = this.$config.data_url + '?name=' + this.query.name;

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
      }
    }
  }
}
</script>
