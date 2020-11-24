<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">

    </base-header>

    <div class="container-fluid mt-4">
      <b-form @submit.prevent="onSubmit">
        <b-form-group
          id="input-group-1"
          :label="$t('course_screen.label.name')"
          label-for="input-1">
          <b-form-input
            id="input-1"
            v-model="course.name"
            type="text"
            required
            :placeholder="$t('course_screen.message.enter_name_course')"
          ></b-form-input>
        </b-form-group>

        <b-form-group id="input-group-2" :label="$t('course_screen.label.description')" label-for="input-2">
          <b-form-input
            id="input-2"
            v-model="course.description"
            required
            :placeholder="$t('course_screen.message.enter_description')"
          ></b-form-input>
        </b-form-group>

        <b-form-group id="input-group-3" :label="$t('course_screen.label.activate')" label-for="input-3">
          <b-form-select
            id="input-3"
            v-model="course.is_active"
            :options="activate"
            required
          ></b-form-select>
        </b-form-group>
        <b-form-group id="input-group-4" :label="$t('course_screen.label.category')" label-for="input-4">
          <multiselect
            v-model="course.category_id"
            :options="categories"
            :searchable="false"
            label="name"
            :close-on-select="true"
            :show-labels="false" :placeholder="$t('course_screen.message.enter_category')"></multiselect>
        </b-form-group>
        <b-button type="submit" variant="primary"> {{ $t("course_screen.button.submit") }}</b-button>
        <b-button type="reset" variant="danger"> {{ $t("course_screen.button.reset") }}</b-button>
      </b-form>
    </div>

  </div>
</template>
<script>
export default {
  props: ['id'],
  data() {
    return {
      course:
        {
          name: '',
          description: '',
          is_active: '',
          category_id: ''
        }
      ,
      activate: [
        {text: 'Please select an option', value: '', disabled: true},
        {text: 'Activate', value: '1'},
        {text: 'Inactivate', value: '0'},
      ],
      categories: [],
    }
  },
  created() {
    if (this.id) {
      this.getCourseById();
      this.getCategoryByCourse();
    }
    this.getDataCategory();

  },
  methods: {
    async onSubmit() {
      if (this.id) {
        this.course.category_id = this.course.category_id.id;
        await this.$store.dispatch('course/updateCourse', this.course).then(() => {
          this.$router.push({name: 'course'});
        })
      } else {
        this.course.category_id = this.course.category_id.id;
        await this.$store.dispatch('course/storeCourse', this.course).then(() => {
          this.$router.push({name: 'course'});
        })
      }
    },
    getCourseById() {
      this.$store.dispatch('course/getIdCourse', this.id).then(
        res => {
          this.course = res.data;
        }
      )
    },
    getDataCategory() {
      this.$store.dispatch('course/getDataCategory').then(
        res => {
          this.categories = res;
        }
      )
    },
    getCategoryByCourse() {
      this.$store.dispatch('course/getCategoryByCourse', this.id).then(
        res => {
          this.course.category_id = res.data;
          console.log(this.course.category_id)
        })
    }

  }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
