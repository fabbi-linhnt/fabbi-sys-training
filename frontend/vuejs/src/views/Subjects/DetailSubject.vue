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
                  <h3>{{ $t("list_subjects.label.detailSubject") }}</h3>
                </div>
              </div>
            </div>
            <div class="content-detail-subject">
              <div class="form-group">
                <label>{{ $t("list_subjects.label.name") }}:</label>
                {{ subject.name }}
              </div>
              <div class="form-group">
                <label>{{ $t("list_subjects.label.description") }}:</label>
                {{ subject.description }}
              </div>
              <div class="form-group">
                <label>{{ $t("list_subjects.label.is_active") }}: </label>
                <span>
                  {{
                    subject.is_active == 1
                      ? $t("task_screen.label.task_active")
                      : $t("task_screen.label.task_inactive")
                  }}
                </span>
              </div>
              <br />
              <div class="form-group">
                <label>{{ $t("list_users.title.list_user") }}</label>
                <b-table striped hover :items="users" :fields="userField">
                  <template #cell(index)="row">
                    {{
                      ++row.index +
                      (Number(paginateUser.page) - 1) * Number(paginateUser.perPage)
                    }}
                  </template>
                </b-table>
              </div>
              <div class="pagination">
                <b-pagination
                  v-model="paginateUser.page"
                  :total-rows="paginateUser.total"
                  :per-page="paginateUser.perPage"
                  aria-controls="my-table"
                  @change="changePageSubject(paginateUser.page)"
                >
                </b-pagination>
              </div>
              <br />
              <div class="form-group">
                <label>{{ $t("task_screen.label.list_tasks") }}</label>
                <b-table
                  striped
                  hover
                  :items="courses"
                  :fields="coursesField"
                >
                  <template #cell(index)="row">
                    {{
                      ++row.index +
                      (Number(paginateCourse.page) - 1) * Number(paginateCourse.perPage)
                    }}
                  </template>
                </b-table>
              </div>
              <div class="pagination">
                <b-pagination
                  v-model="paginateCourse.page"
                  :total-rows="paginateCourse.total"
                  :per-page="paginateCourse.perPage"
                  aria-controls="my-table"
                  @change="changePageCourse(paginateCourse.page)"
                >
                </b-pagination>
              </div>
              <router-link class="btn btn-primary" :to="{ name: 'subjects.list' }">
                <i class="fas fa-undo-alt"> </i>
                {{ $t("task_screen.label.back_home") }}
              </router-link>
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
require("@/sass/modules/detail-subject.css");

export default {
  components: {
    ProjectsTable,
  },
  name: "DetailTask",
  data() {
    return {
      subject: [],
      paginateCourse: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE_USER,
        total: "",
      },
      paginateUser: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE_USER,
        total: "",
      },
      users: [],
      courses: [],
      userField: [
        { key: "index", label: this.$i18n.t("common.label.index") },
        { key: "name", label: this.$i18n.t("user_screen.label.name") },
        { key: "email", label: this.$i18n.t("user_screen.label.email") },
        { key: "phone", label: this.$i18n.t("user_screen.label.phone_number") },
      ],
      coursesField: [
        { key: "index", label: this.$i18n.t("common.label.index") },
        { key: "name", label: this.$i18n.t("list_subjects.label.name") },
        {
          key: "description",
          label: this.$i18n.t("list_subjects.label.description"),
        },
      ],
    };
  },
  props: ["id"],
  created() {
    this.getSubject();
    this.getCourseOfSubject();
    this.getUsersOfTask();
  },
  methods: {
    changePageCourse(page) {
      this.paginateCourse.page = page;
      this.getSubjectOfTask();
    },
    changePageUser(page) {
      this.paginateUser.page = page;
      this.getUserOfTask();
    },
    async getSubject() {
      await this.$store
        .dispatch("subject/GET_SUBJECT_BY_ID", this.id)
        .then((response) => {
          this.subject = response.data;
        });
    },
    async getUsersOfTask() {
      await this.$store
        .dispatch("task/GET_USERS_OF_TASK", this.id)
        .then((response) => {
          this.users = response.data;
          this.paginateUser.perPage = response.data.per_page;
          this.paginateUser.total = response.data.total;
        });
    },
    async getCourseOfSubject() {
      await this.$store
        .dispatch("subject/GET_COURSES_BY_SUBJECT_ID", this.id)
        .then((response) => {
          this.subjects = response.data;
          this.paginateCourse.perPage = response.data.per_page;
          this.paginateCourse.total = response.data.total;
        });
    },
  },
};
</script>
