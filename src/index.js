import Doctor from './components/fields/Doctor.vue';

panel.plugin('bnomei/doctor', {
  fields: {
    'doctor': Doctor,
  },
  components: {
    'doctor-view': {
      template: `
          <k-inside class="doctor-panel-view">
            <k-view>
            <k-header>
                Kirby Doctor
            </k-header>
            <k-doctor-field label="Perform checks" progress="Performing checks..." job="plugin-doctor/check" />
            </k-view>
          </k-inside>
        `
    }
  }
});
