<template>
  <div>
    <k-button class="doctor" :class="status" @click="doctor()" :job="this.job">{{ label }}</k-button>
    <k-info-field v-if="results !== undefined" v-for="(value, key) in results" :key="key" :text="value.message" :theme="theme(value.result)"/>
  </div>
</template>

<script>
export default {
  name: 'Doctor',
  props: {
    label: String,
    job: String,
    progress: String,
    cooldown: Number,
    status: String,
    results: Array,
  },
  methods: {
    theme(s) {
      s = s.toLowerCase()
      let t = 'info'
      if (s == 'warning' || s == 'failure') {
        t = 'negative'
      } else if(s == 'success') {
        t = 'positive'
      }
      return t
    },
    doctor() {
      this.getRequest(this.job)
    },
    getRequest (url) {
      let that = this
      let oldlabel = this.label
      this.label = this.progress.length > 0 ? this.progress : this.label + '...'
      this.status = 'doing-job'
      this.$api.get(url)
        .then(response => {
            if(response.label !== undefined) {
              that.label = response.label
            }
            if(response.data !== undefined) {
              that.results = response.data
            }
            if(response.status !== undefined) {
              that.status = response.status == 200 ? 'is-success' : 'has-error'
            } else {
              that.status = 'has-response'
            }
            setTimeout(function(){
              that.label = oldlabel
              that.status = ''
            }, that.cooldown)
        })
    }
  }
}
</script>

<style lang="postcss">
  .doctor {
    background-color: black;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    padding: 5px 10px 7px 10px;
    min-width: 200px;
  }
  .doctor:hover {
    opacity: 0.75;
  }
  .doctor .k-button-text {
    opacity: 1;
  }
  .doctor.doing-job {
    background-color: #444;
  }
  .doctor.has-response {
    background-color: #999;
  }
  .doctor.is-success {
    background-color: #5d800d;
  }
  .doctor.has-error {
    background-color: #d16464;
  }
</style>
