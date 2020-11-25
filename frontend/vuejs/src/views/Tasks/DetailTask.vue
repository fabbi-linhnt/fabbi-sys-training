<template>
  <div class="main-content">
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8"></base-header>
    <div class="content">
      <h3>{{ $t("task_screen.label.detail_task") }}</h3>
      <div class="form-group">
        <label>{{ $t("task_screen.label.task_name") }}:</label> {{ task.name }}
      </div>
      <div class="form-group">
        <label>{{ $t("task_screen.label.task_description") }}:</label> {{ task.description }}
      </div>
      <div class="form-group">
        <label>{{ $t("task_screen.label.task_content") }}:</label>
        <p class="task-content">
          {{ task.content }}
        </p>
      </div>
      <div class="form-group">
        <label>{{ $t("task_screen.label.task_deadline") }}:</label> {{ task.deadline }}
      </div>
      <div class="form-group">
        <label>{{ $t("task_screen.label.task_isActive") }}: </label>
        <span>
          {{ task.is_active == 1 ? $t("task_screen.label.task_active") : $t("task_screen.label.task_inactive") }}
        </span>
      </div>
      <hr>
      <div class="form-group">
        <label>{{ $t("list_users.title.list_user") }}</label>
        <b-table striped hover :items="users" :fields="userField">
          <template #cell(index)="row">
            {{ ++row.index }}
          </template>
        </b-table>
      </div>
      <hr>
      <div class="form-group">
        <label>{{ $t("list_subjects.title.list_subjects") }}</label>
        <b-table striped hover :items="subjects" :fields="subjectsField" >
          <template #cell(index)="row">
            {{ ++row.index }}
          </template>
        </b-table>
      </div>
      <router-link class="btn btn-primary" :to="{ name : 'tasks.list' }">
        <i class="fas fa-undo-alt"> </i>
        {{ $t("task_screen.label.back_home") }}
      </router-link>
    </div>
  </div>
</template>

<script>
require("@/sass/modules/detail-task.css");

export default {
  name: "DetailTask",
  data() {
    return {
      task: [],
      users: [],
      subjects: [],
      userField: [
        { key: 'index', label: this.$i18n.t("common.label.index") },
        { key: 'name', label: this.$i18n.t("user_screen.label.name") },
        { key: 'email', label: this.$i18n.t("user_screen.label.email") },
        { key: 'phone', label: this.$i18n.t("user_screen.label.phone_number") },
      ],
      subjectsField: [
        { key: 'index', label: this.$i18n.t("common.label.index") },
        { key: 'name', label: this.$i18n.t("list_subjects.label.name") },
        { key: 'description', label: this.$i18n.t("list_subjects.label.description") }
      ]
    }
  },
  props: [
    'id'
  ],
  created() {
    this.getTask();
    this.getSubjectOfTask();
    this.getUsersOfTask();
  },
  methods: {
    async getTask() {
      await this.$store.dispatch("task/GET_TASK_BY_ID", this.id)
        .then(response => {
          this.task = response;
        })
    },
    async getUsersOfTask() {
      await this.$store.dispatch("task/GET_USERS_OF_TASK", this.id)
        .then(response => {
          this.users = response.data
        })
    },
    async getSubjectOfTask() {
      await this.$store.dispatch("task/GET_SUBJECTS_OF_TASK", this.id)
        .then(response => {
          this.subjects = response;
        })
    }
  }
}
</script>
