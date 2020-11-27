<template>
  <div class="main-content">
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div class="content">
      <h3>
        {{
          id
            ? $t("task_screen.label.update_title")
            : $t("task_screen.label.create_title")
        }}
      </h3>
      <form action="" @submit.prevent="onUpdateCreateTask()">
        <div class="form-group">
          <label>{{ $t("task_screen.label.task_name") }}</label>
          <input type="text" v-model="task.name" class="form-control" />
        </div>
        <div class="form-group">
          <label>{{ $t("task_screen.label.task_content") }}</label>
          <input type="text" v-model="task.content" class="form-control" />
        </div>
        <div class="form-group">
          <label>{{ $t("task_screen.label.task_description") }}</label>
          <textarea
            type="text"
            rows="10"
            v-model="task.description"
            class="form-control"
          ></textarea>
        </div>
        <div class="form-group">
          <label class="typo__label">{{
            $t("task_screen.label.pick_subject")
          }}</label>
          <multiselect
            v-model="multi.value"
            :placeholder="$t('task_screen.label.subject_placeholder')"
            label="name"
            track-by="id"
            :options="multi.subjects"
            :multiple="true"
            :taggable="true"
          >
          </multiselect>
        </div>
        <div class="form-group">
          <b-button v-b-modal.modal-center>{{
            $t("task_screen.button.add_user_btn")
          }}</b-button>
          <b-modal
            id="modal-center"
            size="xl"
            centered
            :title="$t('task_screen.label.list_user')"
          >
            <div class="custom-modal">
              <template>
                <div class="overflow-auto">
                  <b-table id="my-table" :items="users" :fields="fields">
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
                    v-model="paginate.page"
                    :total-rows="paginate.total"
                    :per-page="paginate.perPage"
                    aria-controls="my-table"
                    @change="changePage"
                  ></b-pagination>
                </div>
              </template>
            </div>
          </b-modal>
        </div>
        <div class="">
          <div class="tag-input">
            <div
              v-for="(user, index) in submitUser"
              :key="user.id"
              class="tag-input__tag"
            >
              {{ user.name }}
              <span @click="removeTag(index)">x</span>
            </div>
          </div>
        </div>
        <div style="clear: both"></div>
        <div class="form-group">
          <label>{{ $t("task_screen.label.task_deadline") }}</label>
          <input type="date" v-model="task.deadline" class="form-control" />
        </div>
        <div class="form-group">
          <input type="checkbox" v-model="task.is_active" />
          <p>
            {{
              task.is_active == 1
                ? $t("task_screen.label.task_active")
                : $t("task_screen.label.task_inactive")
            }}
          </p>
        </div>
        <button class="btn btn-primary">
          {{
            id
              ? $t("task_screen.button.update_btn")
              : $t("task_screen.button.create_btn")
          }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { DEFAULT_PERPAGE_USER } from "@/definition/constants";
require("@/sass/modules/update-create-task.css");

export default {
  name: "UpdateTask",
  data() {
    return {
      paginate: {
        page: 1,
        perPage: DEFAULT_PERPAGE_USER,
        total: 0,
        name: "",
      },
      fields: [
        { key: "index", label: this.$i18n.t("common.label.index") },
        { key: "name", label: this.$i18n.t("user_screen.label.name") },
        {
          key: "phoneNumber",
          label: this.$i18n.t("user_screen.label.phone_number"),
        },
        { key: "email", label: this.$i18n.t("user_screen.label.email") },
        { key: "actions", label: this.$i18n.t("user_screen.label.action") },
      ],
      users: [],
      submitUser: [],
      task: {
        name: "",
        content: "",
        description: "",
        deadline: "",
        is_active: true,
      },
      multi: {
        value: [],
        subjects: [],
      },
    };
  },
  props: ["id"],
  created() {
    this.getAllSubject();
    this.getUsers();
    if (this.id) {
      this.getTask();
      this.getSubjectOfTask();
      this.getUsersOfTask();
    }
  },
  methods: {
    changePage(page) {
      this.paginate.page = page;
      this.getUsers();
    },
    async getUsers() {
      await this.$store
        .dispatch("user/GET_USER", { params: this.paginate })
        .then((response) => {
          this.users = response.data.data;
          this.paginate.perPage = response.data.per_page;
          this.paginate.total = response.data.total;
        });
    },
    removeTag(index) {
      this.submitUser.splice(index, 1);
    },
    async getUsersOfTask() {
      await this.$store
        .dispatch("task/GET_USERS_OF_TASK", this.id)
        .then((response) => {
          this.submitUser = response.data;
        });
    },
    async getSubjectOfTask() {
      await this.$store
        .dispatch("task/GET_SUBJECTS_OF_TASK", this.id)
        .then((response) => {
          response.forEach((subject) => {
            this.multi.value.push(subject);
          });
        });
    },
    async getTask() {
      await this.$store
        .dispatch("task/GET_TASK_BY_ID", this.id)
        .then((response) => {
          this.task = response;
        });
    },
    async getAllSubject() {
      await this.$store.dispatch("subject/GET_SUBJECTS",{}).then((res) => {
        this.multi.subjects = res.data.data;
      });
    },
    makeToast(message, variant) {
      this.$bvToast.toast(message, {
        variant: variant,
        solid: true,
      });
    },
    async onUpdateCreateTask() {
      let subject_id = [];
      this.multi.value.forEach((subject) => {
        subject_id.push(subject.id);
      });
      let user_id = [];
      this.submitUser.forEach((user) => {
        user_id.push(user.id);
      });
      let params = {
        task: this.task,
        subject_id: subject_id,
        user_id: user_id,
      };

      if (this.id) {
        await this.$store.dispatch("task/UPDATE_TASK", params).then(() => {
          this.$router.push({ name: "tasks.list" });
          this.makeToast(
            this.$i18n.t("task_screen.message.task_msgUpdate"),
            "success"
          );
        });
      } else {
        await this.$store.dispatch("task/STORE_TASK", params).then(() => {
          this.$router.push({ name: "tasks.list" });
          this.makeToast(
            this.$i18n.t("task_screen.message.task_msgCreate"),
            "success"
          );
        });
      }
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
