<template>
  <div>
    <div>
      <projects-table></projects-table>
    </div>
    <div class="container-fluid mt-2">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">
                    {{
                      id
                        ? $t("list_subjects.title.editSubject")
                        : $t("list_subjects.title.addSubject")
                    }}
                  </h3>
                </div>
              </div>
            </div>
            <div class="content">
              <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(onSubmit)">
                  <b-form-group
                    id="subject-name"
                    :label="$t('list_subjects.label.name')"
                    label-for="input-1"
                  >
                    <ValidationProvider
                      :name="$t('list_subjects.label.name')"
                      rules="required|min:5|max:20"
                      v-slot="{ errors }"
                    >
                      <b-form-input
                        :placeholder="$t('list_subjects.label.name')"
                        v-model="subject.name"
                        class="form-control"
                      />
                      <span class="span-error">{{ errors[0] }}</span>
                    </ValidationProvider>
                  </b-form-group>
                  <b-form-group
                    id="subject-description"
                    :label="$t('list_subjects.label.description')"
                  >
                    <ValidationProvider
                      :name="$t('list_subjects.label.description')"
                      rules="required|min:5"
                      v-slot="{ errors }"
                    >
                      <b-form-textarea
                        :placeholder="$t('list_subjects.label.description')"
                        v-model="subject.description"
                        class="form-control"
                        cols="50"
                        rows="10"
                      />
                      <span class="span-error">{{ errors[0] }}</span>
                    </ValidationProvider>
                  </b-form-group>
                  <div>
                    <label class="radio" for="exampleInputPassword1">
                      {{ $t("list_subjects.label.is_active") }}
                    </label>
                    <div id="check-active">
                      <input type="checkbox" v-model="subject.is_active" />
                      <p>
                        {{
                          subject.is_active == 1
                            ? $t("list_subjects.label.active")
                            : $t("list_subjects.label.inActive")
                        }}
                      </p>
                    </div>
                  </div>
                  <b-form-group class="text-center">
                    <b-button
                      v-b-modal.modal-list-courses
                      class="btn btn-default"
                    >
                      {{ $t("list_subjects.label.add_courses") }}
                    </b-button>
                    <b-modal
                      id="modal-list-courses"
                      size="xl"
                      centered
                      :title="$t('course_screen.title.list_course')"
                      :ok="checkSubmitCourseNull()"
                    >
                      <div>
                        <b-form-group
                          label-cols-sm="3"
                          label-align-sm="right"
                          label-size="sm"
                          label-for="filterInput"
                          class="mb-3"
                        >
                          <b-input-group size="sm" class="button_search">
                            <b-form-input
                              v-model="paginateCourse.name"
                              type="search"
                              id="filterInput"
                              :placeholder="
                                $t('course_screen.message.search_by_name')
                              "
                            ></b-form-input>
                            <b-input-group-append>
                              <b-button variant="primary" @click="getCourses()">
                                {{ $t("course_screen.button.search") }}
                              </b-button>
                            </b-input-group-append>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="custom-modal">
                        <template>
                          <div class="overflow-auto">
                            <b-table
                              id="my-table"
                              :items="courses"
                              :fields="fieldsCourses"
                            >
                              <template #cell(index)="row">
                                {{
                                  ++row.index +
                                  (Number(paginateCourse.page) - 1) *
                                    Number(paginateCourse.perPage)
                                }}
                              </template>
                              <template v-slot:cell(actions)="row">
                                <input
                                  type="checkbox"
                                  :value="row.item"
                                  v-model="submitCourse"
                                />
                              </template>
                            </b-table>
                            <b-pagination
                              v-model="paginateCourse.page"
                              :total-rows="paginateCourse.total"
                              :per-page="paginateCourse.perPage"
                              aria-controls="my-table"
                              @change="changePageCourse(paginateCourse.page)"
                            >
                            </b-pagination>
                          </div>
                        </template>
                      </div>
                    </b-modal>
                  </b-form-group>
                  <b-form-group>
                    <div class="tag-input">
                      <div
                        v-for="(course, index) in submitCourse"
                        :key="course.id"
                        class="tag-input__tag"
                      >
                        {{ course.name }}
                        <span class="removeTagCourse" @click="removeTagCourse(index)">
                          x
                        </span>
                      </div>
                    </div>
                    <span
                      v-if="checkSubmitCourse"
                      class="span-error-course-task"
                    >
                      {{ $t("list_subjects.label.add_courses_error") }}
                    </span>
                  </b-form-group>
                  <button
                    id="add-update-subject"
                    class="btn btn-success float-right"
                  >
                    {{
                      id
                        ? $t("list_subjects.button.update")
                        : $t("list_subjects.button.add")
                    }}
                  </button>
                  <router-link
                    id="cancel-add-update-subject"
                    class="btn btn-danger float-right"
                    :to="{ name: 'subjects.list' }"
                    >{{ $t("list_subjects.button.cancel") }}
                  </router-link>
                </form>
              </ValidationObserver>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { DEFAULT_PERPAGE_USER, DEFAULT_PAGE } from "@/definition/constants";
import ProjectsTable from "@/layout/HeaderCard";
require("@/sass/modules/add-update-subject.css");

export default {
  components: {
    ProjectsTable,
  },
  data() {
    return {
      paginateCourse: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE_USER,
        total: "",
        name: "",
      },
      fieldsCourses: [
        { key: "index", label: this.$i18n.t("task_screen.label.task_index") },
        {
          key: "name",
          label: this.$i18n.t("course_screen.label.name"),
        },
        {
          key: "description",
          label: this.$i18n.t("course_screen.label.description"),
        },
        {
          key: "actions",
          label: this.$i18n.t("course_screen.label.actions"),
        },
      ],
      subject: {
        name: "",
        description: "",
        is_active: false,
      },
      submitCourse: [],
      courses: [],
      course_id: [],
      courses_by_id: [],
      checkSubmitCourse: false,
      notificationSystem: {
        options: {
          success: {
            position: "topCenter",
          },
          error: {
            position: "topRight",
          },
        },
      },
    };
  },
  props: ["id"],
  created() {
    this.getCourses();
    if (this.id) {
      this.getSubject();
      this.listCoursesById();
    }
  },
  methods: {
    changePageCourse(page) {
      this.paginateCourse.page = page;
      this.getCourses();
    },
    removeTagCourse(index) {
      this.submitCourse.splice(index, 1);
    },
    async getCourses() {
      if (this.paginateCourse.name) {
        this.paginateCourse.page = DEFAULT_PAGE;
      }
      await this.$store
        .dispatch("course/GET_COURSES", { params: this.paginateCourse })
        .then((response) => {
          this.courses = response.data;
          this.paginateCourse.perPage = response.per_page;
          this.paginateCourse.total = response.total;
        });
    },
    checkSubmitCourseNull() {
      if (this.submitCourse.length > 0) {
        this.checkSubmitCourse = false;
      }
    },
    async getSubject() {
      await this.$store
        .dispatch("subject/GET_SUBJECT_BY_ID", this.id)
        .then((response) => {
          this.subject.name = response.data.name;
          this.subject.description = response.data.description;
          this.subject.is_active = response.data.is_active;
        });
    },
    async onSubmit() {
      this.course_id = this.submitCourse.map((obj) => obj.id);
      let params = {
        name: this.subject.name,
        description: this.subject.description,
        is_active: this.subject.is_active,
        course_id: this.course_id,
      };
      if (this.course_id.length === 0) {
        this.checkSubmitCourse = true;
      }
      if (this.id) {
        if (this.course_id.length > 0) {
          await this.$store
            .dispatch("subject/UPDATE_SUBJECT", {
              id: this.id,
              data: params,
            })
            .then((res) => {
              if (res.data) {
                this.$toast.success(
                  this.$i18n.t("list_subjects.label.update_success"),
                  this.$i18n.t("list_subjects.label.success"),
                  this.notificationSystem.options.success
                ),
                  this.$router.push({ name: "subjects.list" });
              }
            });
        }
      } else {
        if (this.course_id.length > 0) {
          await this.$store
            .dispatch("subject/STORE_SUBJECT", params)
            .then((res) => {
              if (res.data) {
                this.$toast.success(
                  this.$i18n.t("list_subjects.label.add_success"),
                  this.$i18n.t("list_subjects.label.success"),
                  this.notificationSystem.options.success
                )
                  this.$router.push({ name: "subjects.list" });
              }
            });
        }
      }
    },
    async listCoursesById() {
      await this.$store
        .dispatch("subject/GET_COURSES_BY_SUBJECT_ID", this.id)
        .then((res) => {
          this.courses_by_id = res.data.data;
          for (var i = 0; i < this.courses.length; i++) {
            for (var j = 0; j < this.courses_by_id.length; j++) {
              if (this.courses_by_id[j].id == this.courses[i].id) {
                this.submitCourse.push(this.courses[i]);
              }
            }
          }
        });
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
