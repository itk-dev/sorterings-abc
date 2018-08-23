<template>
    <div class="items">
        <div class="form-group">
            <input class="form-control" v-bind:placeholder="$t('Search …')" v-model="query.name" v-on:keyup="fetchData"/>
        </div>

        <div class="alert alert-info" v-if="loading">{{ $t('Loading items …') }}</div>

        <div class="alert alert-warning" v-if="items && items.length === 0">
            {{ $t('No items starting with "{name}"', {name: query.name}) }}
        </div>

        <div v-if="items" class="list-group-item item" v-for="(item, index) in items" v-bind:key="item.id">
            <div class="name">{{ item.name }}</div>

            <div v-if="item.description">
                <div class="description">
                    <div class="name">{{ item.description.name }}</div>
                    <div class="description">{{ item.description.description }}</div>
                </div>
            </div>

            <div v-if="item.categories" v-for="(category, index) in item.categories" v-bind:key="category['@id']">
                <div class="category">
                    <div class="name">{{ category.name }}</div>
                    <div class="description">{{ category.description }}</div>
                </div>
            </div>
        </div>
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
