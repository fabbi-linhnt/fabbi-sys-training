<template>
  <div>
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>

    <div class="container-fluid mt-4">
      <ValidationObserver v-slot="{ handleSubmit }">
        <b-form @submit.prevent="handleSubmit(onSubmit)">
          <ValidationProvider
            :name="$t('course_screen.label.name')"
            rules="required|min:3|max:20"
            v-slot="{ errors }">
            <b-form-group
              id="input-group-1"
              :label="$t('course_screen.label.name')"
              label-for="input-1">
              <b-form-input
                id="input-1"
                v-model="course.name"
                type="text"
                :placeholder="$t('course_screen.message.enter_name_course')"
              ></b-form-input>
              <span class="err">{{ errors[0] }}</span>
            </b-form-group>
          </ValidationProvider>
          <ValidationProvider
            :name="$t('course_screen.label.description')"
            rules="required|min:5|max:50"
            v-slot="{ errors }">
            <b-form-group id="input-group-2"
                          :label="$t('course_screen.label.description')"
                          label-for="input-2">
              <b-form-input
                id="input-2"
                v-model="course.description"
                :placeholder="$t('course_screen.message.enter_description')"
              ></b-form-input>
              <span class="err">{{ errors[0] }}</span>
            </b-form-group>
          </ValidationProvider>

          <ValidationProvider :name="$t('course_screen.label.is_active')" rules="required" v-slot="{ errors }">
            <b-form-group id="input-group-3"
                          :label="$t('course_screen.label.activate')"
                          label-for="input-3">
              <b-form-select
                id="input-3"
                v-model="course.is_active"
                :options="activate"
              ></b-form-select>
              <span class="err">{{ errors[0] }}</span>
            </b-form-group>
          </ValidationProvider>
          <ValidationProvider :name="$t('course_screen.label.category')" rules="required" v-slot="{ errors }">
            <b-form-group id="input-group-4"
                          :label="$t('course_screen.label.category')"
                          label-for="input-4">
              <Treeselect
                :multiple="false"
                v-model="course.category_id"
                :options="categories"
                :placeholder="$t('course_screen.message.please_select_an_option')">
              </Treeselect>
              <span class="err">{{ errors[0] }}</span>
            </b-form-group>
          </ValidationProvider>
          <b-form-group :label="$t('course_screen.label.image')">
            <div>
              <!-- Styled -->
              <b-form-file
                v-model="picture"
                :state="Boolean(picture)"
                :placeholder="$t('course_screen.message.choose_a_file_or_drop_it_here')"
                @change="previewImage"
              ></b-form-file>
              <div class="mt-3" v-if="picture">
                <b-img id="imgCourse" :src="picture"></b-img>
              </div>
            </div>
          </b-form-group>
          <b-button type="submit" variant="primary"> {{ $t("course_screen.button.submit") }}</b-button>
          <b-button type="reset"
                    :to="{ name: 'courses.list' }"
                    variant="danger">
            {{ $t("course_screen.button.cancel") }}
          </b-button>
        </b-form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
import Firebase from 'firebase';

require("@/sass/modules/form-course.css");
export default {
  props: ["id"],
  data() {
    return {
      course: {
        name: "",
        description: "",
        is_active: "",
        category_id: null,
      },
      activate: [
        {text: this.$i18n.t("course_screen.message.please_select_an_option"), value: "", disabled: true},
        {text: "Activate", value: "1"},
        {text: "Inactivate", value: "0"},
      ],
      categories: [],
      imageData: null,
      picture: null,
      uploadValue: 0,
    };
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
        await this.$store
          .dispatch("course/UPDATE_COURSE", this.course)
          .then(() => {
            this.$router.push({name:"courses.list"});
          });
      } else {
        this.course.category_id = this.course.category_id.id;
        await this.$store
          .dispatch("course/STORE_COURSE", this.course)
          .then(() => {
            this.$router.push({name:"courses.list"});
          });
      }
    },
    async getCourseById() {
      await this.$store.dispatch("course/GET_ID_COURSE", this.id).then((res) => {
        this.course = res.data;
      });
    },
    async getDataCategory() {
      await this.$store.dispatch("course/GET_DATA_CATEGORIES").then((res) => {
        this.categories = res;
      });
    },
    async getCategoryByCourse() {
      await this.$store
        .dispatch("course/GET_CATEGORIES_BY_COURSE", this.id)
        .then((res) => {
          this.course.category_id = res.data;
        });
    },
    previewImage(event) {
      this.uploadValue = 0;
      this.picture = null;
      this.imageData = event.target.files[0];
      this.picture = null;
      const storageRef = Firebase.storage().ref();
      const imgRef = storageRef.child(`imagesCourse/${this.imageData.name}`)
      imgRef.put(this.imageData).then(s => {
        imgRef.getDownloadURL().then(url => {
          this.picture = url;
        })
      })
    }
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
