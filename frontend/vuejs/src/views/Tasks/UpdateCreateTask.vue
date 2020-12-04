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
            <div class="content">
              <h3>
                {{
                  id
                    ? $t("task_screen.label.update_title")
                    : $t("task_screen.label.create_title")
                }}
              </h3>
              <ValidationObserver v-slot="{ handleSubmit }">
                <form action="" @submit.prevent="handleSubmit(onUpdateCreateTask)">
                  <validation-provider
                    :name="$t('task_screen.label.task_content') "
                    rules="required|min:3|max:20"
                    v-slot="{ errors }">
                    <div class="form-group">
                      <label>{{ $t("task_screen.label.task_content") }}</label>
                      <input type="text" v-model="task.content" class="form-control"/>
                    </div>
                    <span class="err">{{ errors[0] }}</span>
                  </validation-provider>
                  <validation-provider
                    :name="$t('task_screen.label.task_description') "
                    rules="required|min:5|max:100  "
                    v-slot="{ errors }">
                    <div class="form-group">
                      <label>{{ $t("task_screen.label.task_description") }}</label>
                      <textarea
                        type="text"
                        rows="10"
                        v-model="task.description"
                        class="form-control"
                      ></textarea>
                    </div>
                    <span class="err">{{ errors[0] }}</span>
                  </validation-provider>
                  <div class="form-group">
                    <div>
                      <b-button v-b-modal.modal-center-1 class="btn_add_subject">
                        {{ $t("task_screen.button.add_subject_btn") }}
                      </b-button>
                      <b-modal
                        id="modal-center-1"
                        size="xl"
                        centered
                        :title="$t('task_screen.label.list_subject')">
                        <div>
                          <b-form-group
                            label-cols-sm="3"
                            label-align-sm="right"
                            label-size="sm"
                            label-for="filterInput"
                            class="mb-3"
                          >
                            <b-input-group size="sm" id="modal_action_subject_search">
                              <b-form-input
                                v-model="paginateSubject.name"
                                type="search"
                                id="filterInput"
                                :placeholder="$t('course_screen.message.search_by_name')"
                              ></b-form-input>
                              <b-input-group-append>
                                <b-button variant="primary" @click="getAllSubject()">
                                  {{ $t("course_screen.button.search") }}
                                </b-button>
                              </b-input-group-append>
                            </b-input-group>
                          </b-form-group>
                        </div>
                        <div class="custom-modal">
                          <template>
                            <div class="overflow-auto">
                              <b-table id="my-table" :items="subjects" :fields="fieldSubject">
                                <template #cell(index)="row">
                                  {{ ++row.index }}
                                </template>
                                <template #cell(is_active)="row">
                                  <p>
                                    {{
                                      row.item.is_active === 1
                                        ? $t("course_screen.label.active")
                                        : $t("course_screen.label.inactive")
                                    }}
                                  </p>
                                </template>
                                <template
                                  v-slot:cell(actions)="row">
                                  <input
                                    v-if="row.item.is_active === 1"
                                    type="checkbox"
                                    v-model="submitSubject"
                                    :value="row.item"
                                  />
                                  <span id="modal_action_subject" v-else>
                          x
                        </span>
                                </template>
                              </b-table>
                              <b-pagination
                                v-model="paginateSubject.page"
                                :total-rows="paginateSubject.total"
                                :per-page="paginateSubject.perPage"
                                aria-controls="my-table"
                                @change="changePageSubject"
                              ></b-pagination>
                            </div>
                          </template>
                        </div>
                      </b-modal>
                    </div>
                  </div>
                  <validation-provider
                    :name="$t('task_screen.label.task_deadline') "
                    rules="required|min:5|max:100 "
                    v-slot="{ errors }">
                    <div class="form-group">
                      <label>{{ $t("task_screen.label.task_deadline") }}</label>
                      <input type="date" v-model="task.deadline" class="form-control"/>
                    </div>
                    <span class="err">{{ errors[0] }}</span>
                  </validation-provider>
                  <div class="form-group">
                    <input type="checkbox" v-model="task.is_active"/>
                    <p>
                      {{
                        task.is_active == 1
                          ? $t("task_screen.label.task_active")
                          : $t("task_screen.label.task_inactive")
                      }}
                    </p>
                  </div>
                  <b-button
                    class="btn btn-danger float-right"
                    :to="{ name: 'tasks.list' }"
                    variant="danger">
                    {{ $t("course_screen.button.cancel") }}
                  </b-button>
                  <button class="btn btn-success float-right">
                    {{
                      id
                        ? $t("task_screen.button.update_btn")
                        : $t("task_screen.button.create_btn")
                    }}
                  </button>
                </form>
              </ValidationObserver>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</template>

<script>
import ProjectsTable from "@/layout/HeaderCard";
import {DEFAULT_PERPAGE_SUBJECT} from "../../definition/constants";
require("@/sass/modules/update-create-task.css");

export default {
  components: {
    ProjectsTable,
  },
  name: "UpdateTask",
  data() {
    return {
      paginateSubject: {
        page: 1,
        perPage: DEFAULT_PERPAGE_SUBJECT,
        name: "",
        total: 0
      },
      fieldSubject: [
        {key: "index", label: this.$i18n.t("common.label.index")},
        {key: "name", label: this.$i18n.t("list_subjects.label.name")},
        {key: "description", label: this.$i18n.t("list_subjects.label.description")},
        {key: "is_active", label: this.$i18n.t("list_subjects.label.is_active")},
        {key: "actions", label: this.$i18n.t("user_screen.label.action")},
      ],
      task: {
        name: "",
        content: "",
        description: "",
        deadline: "",
        is_active: true,
      },
      subjects: [],
      submitSubject: [],
      status: false,
    };
  },
  props: ["id"],
  created() {
    this.getAllSubject();
    if (this.id) {
      this.getTask();
      this.getSubjectOfTask();
      this.getUsersOfTask();
    }
  },
  methods: {
    changePageSubject(page) {
      this.paginateSubject.page = page;
      this.getAllSubject();
    },
    async getAllSubject() {
      await this.$store.dispatch("subject/GET_SUBJECTS", {params: this.paginateSubject})
        .then((res) => {
          this.subjects = res.data.data;
          this.paginateSubject.perPage = res.data.per_page;
          this.paginateSubject.total = res.data.total;
        });
    },
    removeTagSubject(index) {
      this.submitSubject.splice(index, 1);
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
    makeToast(message, variant) {
      this.$bvToast.toast(message, {
        variant: variant,
        solid: true,
      });
    },
    async onUpdateCreateTask() {
      if (this.submitSubject.length === 0 || this.submitUser.length === 0) {
        return this.status = true;
      }
      let subject_id = [];
      this.submitSubject.forEach((subject) => {
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
          this.$router.push({name: "tasks.list"});
          this.makeToast(
            this.$i18n.t("task_screen.message.task_msgUpdate"),
            "success"
          );
        });
      } else {
        await this.$store.dispatch("task/STORE_TASK", params).then(() => {
          this.$router.push({name: "tasks.list"});
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
