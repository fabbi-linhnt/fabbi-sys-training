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
                    {{ $t("task_screen.label.list_tasks") }}
                  </h3>
                  <router-link
                    class="btn btn-primary"
                    :to="{ name: 'user.create' }"
                  >
                    {{ $t("list_users.label.add") }}
                  </router-link>
                </div>
                <div class="col text-right">
                  <div class="paginate">
                    <label class="typo__label">
                      {{ $t("course_screen.label.record") }}
                    </label>
                    <multiselect
                      v-model="paginate.perPage"
                      :options="options"
                      :searchable="false"
                      :close-on-select="false"
                      :show-labels="false"
                      @select="customPaginate()"
                    >
                    </multiselect>
                  </div>
                </div>
              </div>
            </div>
            <b-input-group class="mt-3">
              <b-form-input
                :placeholder="$t('list_users.label.search_user')"
                v-model="paginate.name"
              ></b-form-input>
              <b-input-group-append>
                <b-button variant="info" @click="getData()">
                  {{ $t("list_users.label.search") }}
                </b-button>
              </b-input-group-append>
            </b-input-group>
            <br />
            <b-table
              show-empty
              small
              stacked="md"
              :items="tasks"
              :fields="fields"
            >
              <template #cell(index)="row">
                {{
                  ++row.index +
                  (Number(paginate.page) - 1) * Number(paginate.perPage)
                }}
              </template>
              <template v-slot:cell(is_active)="row">
                <p>
                  {{
                    row.item.is_active == 1
                      ? $t("task_screen.label.task_active")
                      : $t("task_screen.label.task_inactive")
                  }}
                </p>
              </template>
              <template v-slot:cell(actions)="row">
                <b-icon
                  icon="trash"
                  font-scale="2"
                  variant="danger"
                  @click="onDeleteTask(row.item.id)"
                  class="deleteTask"
                >
                </b-icon>
                <b-icon
                  variant="info"
                  font-scale="2"
                  icon="info-circle"
                  class="detailTask"
                  @click="
                    $router.push({
                      name: 'task.detail',
                      params: { id: row.item.id },
                    })
                  "
                >
                  {{ $t("task_screen.label.task_update") }}
                </b-icon>
                <b-icon
                  variant="dark"
                  font-scale="2"
                  icon="pencil-square"
                  class="updateTask"
                  @click="
                    $router.push({
                      name: 'task.edit',
                      params: { id: row.item.id },
                    })
                  "
                >
                </b-icon>
              </template>
              <template v-slot:cell(user_task)="row">
                <router-link
                  class="btn btn-primary"
                  :to="{ name: 'reports.list', params: { id: row.item.id } }"
                >
                  {{ $t("report_screen.label.list_report") }}
                </router-link>
              </template>
            </b-table>
            <div class="pagination">
              <b-pagination
                v-model="paginate.page"
                :total-rows="paginate.total"
                :per-page="paginate.perPage"
                aria-controls="my-table"
                @change="changePage(paginate.page)"
              >
              </b-pagination>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  DEFAULT_OPTION,
  DEFAULT_PERPAGE,
  DEFAULT_PAGE,
} from "@/definition/constants";
import swal from "sweetalert";
import ProjectsTable from "@/layout/HeaderCard";
import notification from "@/js/sweetAlert.js";
require("@/sass/modules/list-task.css");

export default {
  components: {
    ProjectsTable,
  },
  name: "Tasks",
  data() {
    return {
      options: DEFAULT_OPTION,
      tasks: [],
      paginate: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE,
        total: "",
        name: "",
      },
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
        {
          key: "is_active",
          label: this.$i18n.t("task_screen.label.task_isActive"),
        },
        {
          key: "user_task",
          label: this.$i18n.t("task_screen.label.task_user"),
        },
        { key: "actions", label: this.$i18n.t("task_screen.label.action") },
      ],
    };
  },
  created() {
    this.getData();
  },
  methods: {
    changePage(page) {
      this.paginate.page = page;
      this.getData();
    },
    getData() {
      if (this.paginate.name) {
        this.paginate.page = DEFAULT_PAGE;
      }
      this.$store
        .dispatch("task/GET_TASKS", { params: this.paginate })
        .then((response) => {
          this.tasks = response.data;
          this.paginate.perPage = response.per_page;
          this.paginate.total = response.total;
        });
    },
    async onDeleteTask(id) {
      swal(
        notification.notification(
          this.$i18n.t("task_screen.message.delete_confirm"),
          this.$i18n.t("task_screen.message.warning")
        )
      ).then((willDelete) => {
        if (willDelete) {
          swal(this.$i18n.t("task_screen.message.delete_success"), {
            icon: "success",
          });
          this.$store
            .dispatch("task/DESTROY_TASK", id)
            .then(() => {
              this.getData();
            })
            .catch(() => {});
        } else {
          return;
        }
      });
    },
    customPaginate() {
      this.getData();
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
