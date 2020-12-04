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
                    >
                      <form>
                        <label>{{ $t("list_users.label.search_user") }}</label>
                        <b-input-group class="button_search">
                          <b-form-input
                            :placeholder="$t('list_users.label.search_user')"
                            type="text"
                            v-model="paginateCourse.name"
                          ></b-form-input>
                          <b-input-group-append>
                            <b-button
                              size="sm"
                              text="Button"
                              variant="info"
                              @click.prevent="getCourses()"
                            >
                              {{ $t("list_users.button.search_btn") }}
                            </b-button>
                          </b-input-group-append>
                        </b-input-group>
                      </form>
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
                                  (Number(paginateCourse.page) - 1) * Number(paginateCourse.perPage)
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
                            ></b-pagination>
                          </div>
                        </template>
                      </div>
                    </b-modal>
                  </b-form-group>
                  <b-form-group>
                    <div class="card shadow" id="card">
                      <div class="card-header border-0">
                        <div class="row align-items-center">
                          <div class="col">
                            <div class="tag-input">
                              <div
                                v-for="(course, index) in submitCourse"
                                :key="course.id"
                                class="tag-input__tag"
                              >
                                {{ course.name }}
                                <span @click="removeTagCourse(index)">x</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <span
                      v-if="checkSubmitCourse"
                      class="span-error-course-task"
                    >
                      {{ $t("list_subjects.label.add_courses_error") }}
                    </span>
                  </b-form-group>
                  <b-form-group class="text-center">
                    <b-button
                      v-b-modal.modal-list-tasks
                      class="btn btn-default"
                    >
                      {{ $t("list_subjects.label.add_tasks") }}
                    </b-button>
                    <b-modal
                      id="modal-list-tasks"
                      size="xl"
                      centered
                      :title="$t('task_screen.label.list_tasks')"
                    >
                      <form>
                        <label>{{ $t("list_users.label.search_user") }}</label>
                        <b-input-group class="button_search">
                          <b-form-input
                            :placeholder="$t('list_users.label.search_user')"
                            type="text"
                            v-model="paginateTask.name"
                          ></b-form-input>
                          <b-input-group-append>
                            <b-button
                              size="sm"
                              text="Button"
                              variant="info"
                              @click.prevent="getTasks()"
                            >
                              {{ $t("list_users.button.search_btn") }}
                            </b-button>
                          </b-input-group-append>
                        </b-input-group>
                      </form>
                      <div class="custom-modal">
                        <template>
                          <div class="overflow-auto">
                            <b-table
                              id="my-table"
                              :items="tasks"
                              :fields="fields"
                            >
                              <template #cell(index)="row">
                                {{
                                  ++row.index +
                                  (Number(paginateTask.page) - 1) * Number(paginateTask.perPage)
                                }}
                              </template>
                              <template v-slot:cell(actions)="row">
                                <input
                                  type="checkbox"
                                  v-model="submitTask"
                                  :value="row.item"
                                />
                              </template>
                            </b-table>
                            <b-pagination
                              v-model="paginateTask.page"
                              :total-rows="paginateTask.total"
                              :per-page="paginateTask.perPage"
                              aria-controls="my-table"
                              @change="changePageTask(paginateTask.page)"
                            ></b-pagination>
                          </div>
                        </template>
                      </div>
                    </b-modal>
                  </b-form-group>
                  <b-form-group>
                    <div class="card shadow" id="card">
                      <div class="card-header border-0">
                        <div class="row align-items-center">
                          <div class="col">
                            <div class="tag-input">
                              <div
                                v-for="(task, index) in submitTask"
                                :key="task.id"
                                class="tag-input__tag"
                              >
                                {{ task.name }}
                                <span @click="removeTagTask(index)">x</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <span v-if="checkSubmitTask" class="span-error-course-task">
                      {{ $t("list_subjects.label.add_tasks_error") }}
                    </span>
                  </b-form-group>
                  <button id="update-add-subject" class="btn btn-success">
                    {{
                      id
                        ? $t("list_subjects.button.update")
                        : $t("list_subjects.button.add")
                    }}
                  </button>
                  <router-link
                    id="cancel"
                    class="btn btn-danger"
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
      paginateTask: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE_USER,
        total: "",
        name: "",
      },
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
      fields: [
        { key: "index", label: this.$i18n.t("task_screen.label.task_index") },
        {
          key: "name",
          label: this.$i18n.t("task_screen.label.task_name"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "description",
          label: this.$i18n.t("task_screen.label.task_description"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "content",
          label: this.$i18n.t("task_screen.label.task_content"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "deadline",
          label: this.$i18n.t("task_screen.label.task_deadline"),
          sortable: true,
          sortDirection: "desc",
        },
        { key: "actions", label: this.$i18n.t("task_screen.label.action") },
      ],
      tasks: [],
      submitTask: [],
      subject: {
        name: "",
        description: "",
        is_active: false,
      },
      submitCourse: [],
      courses: [],
      courses_by_id: [],
      checkSubmitTask: false,
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
    this.getTasks();
    if (this.id) {
      this.getSubject();
      this.listCoursesById();
    }
  },
  methods: {
    changePageTask(page) {
      this.paginateTask.page = page;
      this.getTasks();
    },
    changePageCourse(page) {
      this.paginateCourse.page = page;
      this.getCourses();
    },
    async getTasks() {
      if (this.paginateTask.name) {
        this.paginateTask.page = DEFAULT_PAGE;
      }
      await this.$store
        .dispatch("task/GET_TASKS", { params: this.paginateTask })
        .then((response) => {
          this.tasks = response.data;
          this.paginateTask.perPage = response.per_page;
          this.paginateTask.total = response.total;
        });
    },
    removeTagTask(index) {
      this.submitTask.splice(index, 1);
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
      let course_id = [];
      course_id = this.submitCourse.map((obj) => obj.id);
      let task_id = [];
      task_id = this.submitTask.map((obj) => obj.id);
      let params = {
        subject: this.subject,
        course_id: course_id,
        task_id: task_id,
      };
      if (task_id.length == 0) {
        this.checkSubmitTask = true;
      }
      if (course_id.length == 0) {
        this.checkSubmitCourse = true;
      }
      if (this.id) {
        if (task_id.length > 0) {
          await this.$store
            .dispatch("subject/UPDATE_SUBJECT", params)
            .then((res) => {
              if (res.data) {
                this.$toast.success(
                  this.$i18n.t("list_subjects.label.update_success"),
                  "OK",
                  this.notificationSystem.options.success
                ),
                  this.$router.push({ name: "subjects.list" });
              }
            });
        }
      } else {
        if (task_id.length > 0) {
          await this.$store
            .dispatch("subject/STORE_SUBJECT", params)
            .then((res) => {
              if (res.data) {
                this.$toast.success(
                  this.$i18n.t("list_subjects.label.add_success"),
                  this.$i18n.t("list_subjects.label.success"),
                  this.notificationSystem.options.success
                ),
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
          this.courses_by_id = res.data;
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
