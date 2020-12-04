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
          <div>
            <div class="form-group">
              <div>
                <b-button v-b-modal.modal-center>
                  {{ $t("task_screen.button.add_user_btn") }}
                </b-button>
                <b-modal
                  id="modal-center"
                  size="xl"
                  centered
                  :title="$t('task_screen.label.list_user')"
                >
                  <div>
                    <b-form-group
                      label-cols-sm="3"
                      label-align-sm="right"
                      label-size="sm"
                      label-for="filterInput"
                      class="mb-3"
                    >
                      <b-input-group size="sm" id="modal_action_user_search">
                        <b-form-input
                          v-model="paginateUser.search"
                          type="search"
                          id="filterInput"
                          :placeholder="$t('course_screen.message.search_by_name')"
                        ></b-form-input>
                        <b-input-group-append>
                          <b-button variant="primary" @click="getUsers()">
                            {{ $t("course_screen.button.search") }}
                          </b-button>
                        </b-input-group-append>
                      </b-input-group>
                    </b-form-group>
                  </div>
                  <div class="custom-modal">
                    <template>
                      <div class="overflow-auto">
                        <b-table id="my-table" :items="users" :fields="fieldUser">
                          <template #cell(index)="row">
                            {{ ++row.index }}
                          </template>
                          <template v-slot:cell(actions)="row">
                            <input
                              type="checkbox"
                              v-model="submitUser"
                              :value="row.item"
                            />
                          </template>
                        </b-table>
                        <b-pagination
                          v-model="paginateUser.page"
                          :total-rows="paginateUser.total"
                          :per-page="paginateUser.perPage"
                          aria-controls="my-table"
                          @change="changePageUser(paginateUser.page)"
                        ></b-pagination>
                      </div>
                    </template>
                  </div>
                </b-modal>
              </div>
            </div>
            <div class="">
           <span v-if="status === true" class="span-err">
             {{ $t("task_screen.message.required") }}
           </span>
              <div class="tag-input">
                <div
                  v-for="(user, index) in submitUser"
                  :key="user.id"
                  class="tag-input__tag"
                >
                  {{ user.name }}
                  <span @click="removeTagUser(index)" class="removeTagUser">x</span>
                </div>
              </div>
            </div>
            <div style="clear: both"></div>
          </div>
          <b-form-group :label="$t('course_screen.label.image')">
            <div>
              <b-form-file
                v-model="course.picture"
                :state="Boolean(course.picture)"
                :placeholder="$t('course_screen.message.choose_a_file_or_drop_it_here')"
                @change="previewImage"
              ></b-form-file>
              <div class="mt-3" v-if="course.picture">
                <b-img id="imgCourse" :src="course.picture"></b-img>
              </div>
            </div>
            <b-button type="submit" class="btn btn-success float-right"> {{
                $t("course_screen.button.submit")
              }}
            </b-button>
            <b-button
              :to="{ name: 'courses.list' }"
              class="btn btn-danger float-right">
              {{ $t("course_screen.button.cancel") }}
            </b-button>
          </b-form-group>
        </b-form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
import Firebase from 'firebase';
import {DEFAULT_PERPAGE_USER} from "../../definition/constants";

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
        picture: null,
      },
      activate: [
        {text: this.$i18n.t("course_screen.message.please_select_an_option"), value: "", disabled: true},
        {text: "Activate", value: "1"},
        {text: "Inactivate", value: "0"},
      ],
      fieldUser: [
        {key: "index", label: this.$i18n.t("common.label.index")},
        {key: "name", label: this.$i18n.t("user_screen.label.name")},
        {key: "phoneNumber", label: this.$i18n.t("user_screen.label.phone_number")},
        {key: "email", label: this.$i18n.t("user_screen.label.email")},
        {key: "actions", label: this.$i18n.t("user_screen.label.action")},
      ],
      paginateUser: {
        page: 1,
        perPage: DEFAULT_PERPAGE_USER,
        total: 0,
        search: "",
      },
      users: [],
      status: false,
      submitUser: [],
      categories: [],
      imageData: null,
      uploadValue: 0,
      imageError: false,
      defaultImage: require("@/assets/imgs/default.jpeg")
    };
  },
  created() {
    if (this.id) {
      this.getCourseById();
    }
    this.getDataCategory();
    this.getUsers();
  },
  methods: {
    async onSubmit() {
      if (this.submitUser.length === 0) {
        return this.status = true;
      }
      if (this.id) {
        await this.$store
          .dispatch("course/UPDATE_COURSE", this.course)
          .then(() => {
            this.$router.push({name: "courses.list"});
          });
      } else {
        await this.$store
          .dispatch("course/STORE_COURSE", this.course)
          .then(() => {
            this.$router.push({name: "courses.list"});
          });
      }
    },
    removeTagUser(index) {
      this.submitUser.splice(index, 1);
    },
    changePageUser(page) {
      this.paginateUser.page = page;
      this.getUsers();
    },
    async getUsers() {
      await this.$store
        .dispatch("user/GET_USER", {params: this.paginateUser})
        .then((response) => {
          this.users = response.data.data;
          this.paginateUser.perPage = response.data.per_page;
          this.paginateUser.total = response.data.total;
        });
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
    previewImage(event) {
      this.uploadValue = 0;
      this.course.picture = null;
      this.imageData = event.target.files[0];
      this.course.picture = null;
      const storageRef = Firebase.storage().ref();
      const imgRef = storageRef.child(`imagesCourse/${this.imageData.name}`)
      imgRef.put(this.imageData).then(() => {
        imgRef.getDownloadURL().then(url => {
          this.course.picture = url;
        })
      })
    }
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
