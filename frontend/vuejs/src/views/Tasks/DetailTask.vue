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
                  <h3>{{ $t("task_screen.label.detail_task") }}</h3>
                </div>
              </div>
            </div>
            <div class="content-detail-task">
              <div class="form-group">
                <label>{{ $t("task_screen.label.task_name") }}:</label>
                {{ task.name }}
              </div>
              <div class="form-group">
                <label>{{ $t("task_screen.label.task_description") }}:</label>
                {{ task.description }}
              </div>
              <div class="form-group">
                <label>{{ $t("task_screen.label.task_content") }}:</label>
                <p class="task-content">
                  {{ task.content }}
                </p>
              </div>
              <div class="form-group">
                <label>{{ $t("task_screen.label.task_deadline") }}:</label>
                {{ task.time }}
                {{ $t("task_screen.label.day") }}
              </div>
              <div class="form-group">
                <label>{{ $t("task_screen.label.task_isActive") }}: </label>
                <span>
                  {{
                    task.is_active == 1
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
                  v-model="paginateSubject.page"
                  :total-rows="paginateSubject.total"
                  :per-page="paginateSubject.perPage"
                  aria-controls="my-table"
                  @change="changePageSubject(paginateSubject.page)"
                >
                </b-pagination>
              </div>
              <br />
              <div class="form-group">
                <label>{{ $t("list_subjects.title.list_subjects") }}</label>
                <b-table
                  striped
                  hover
                  :items="subjects"
                  :fields="subjectsField"
                >
                  <template #cell(index)="row">
                    {{
                      ++row.index +
                      (Number(paginateSubject.page) - 1) * Number(paginateSubject.perPage)
                    }}
                  </template>
                </b-table>
              </div>
              <div class="pagination">
                <b-pagination
                  v-model="paginateSubject.page"
                  :total-rows="paginateSubject.total"
                  :per-page="paginateSubject.perPage"
                  aria-controls="my-table"
                  @change="changePageSubject(paginateSubject.page)"
                >
                </b-pagination>
              </div>
              <router-link class="btn btn-primary" :to="{ name: 'tasks.list' }">
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
require("@/sass/modules/detail-task.css");

export default {
  components: {
    ProjectsTable,
  },
  name: "DetailTask",
  data() {
    return {
      task: [],
      paginateSubject: {
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
      subjects: [],
      userField: [
        { key: "index", label: this.$i18n.t("common.label.index") },
        { key: "name", label: this.$i18n.t("user_screen.label.name") },
        { key: "email", label: this.$i18n.t("user_screen.label.email") },
        { key: "phone", label: this.$i18n.t("user_screen.label.phone_number") },
      ],
      subjectsField: [
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
    this.getTask();
    this.getSubjectsOfTask();
    this.getUsersOfTask();
  },
  methods: {
    changePageSubject(page) {
      this.paginateSubject.page = page;
      this.getSubjectOfTask();
    },
    changePageUser(page) {
      this.paginateUser.page = page;
      this.getUserOfTask();
    },
    async getTask() {
      await this.$store
        .dispatch("task/GET_TASK_BY_ID", this.id)
        .then((response) => {
          this.task = response;
        });
    },
    async getUsersOfTask() {
      await this.$store
        .dispatch("task/GET_USERS_OF_TASK", this.id)
        .then((response) => {
          this.users = response.data.data;
          this.paginateUser.total = response.data.total;
          this.paginateUser.perPage = response.data.per_page;
        });
    },
    async getSubjectsOfTask() {
      await this.$store
        .dispatch("task/GET_SUBJECTS_OF_TASK", this.id)
        .then((response) => {
          this.subjects = response.data.data;
          this.paginateSubject.total = response.data.total;
          this.paginateSubject.perPage = response.data.per_page;
        });
    },
  },
};
</script>
