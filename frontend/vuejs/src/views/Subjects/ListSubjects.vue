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
                    {{ $t("list_subjects.title.list_subjects") }}
                  </h3>
                  <router-link
                    class="btn btn-primary"
                    :to="{ name: 'subject.create' }"
                  >
                    {{ $t("list_subjects.title.add_new_subject") }}
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
              :items="subjects"
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
                      ? $t("list_subjects.label.active")
                      : $t("list_subjects.label.inActive")
                  }}
                </p>
              </template>
              <template v-slot:cell(actions)="row">
                <b-icon
                  icon="trash"
                  variant="danger"
                  font-scale="2"
                  @click="deleteSubject(row.item.id)"
                  class="deleteSubject"
                >
                </b-icon>
                <b-icon
                  icon="info-circle"
                  variant="info"
                  font-scale="2"
                  @click="detailSubject(row.item.id)"
                  class="detailSubject"
                >
                </b-icon>
                <b-icon
                  icon="pencil-square"
                  variant="dark"
                  font-scale="2"
                  @click="
                    $router.push({
                      name: 'subject.edit',
                      params: { id: row.item.id },
                    })
                  "
                  class="updateSubject"
                >
                </b-icon>
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
  DEFAULT_PAGE,
  DEFAULT_PERPAGE,
  DEFAULT_OPTION,
} from "@/definition/constants";
import ProjectsTable from "@/layout/HeaderCard";
import swal from "sweetalert";
import notification from "@/js/sweetAlert.js";
require("@/sass/modules/list-subject.css");

export default {
  components: {
    ProjectsTable,
  },
  data() {
    return {
      options: DEFAULT_OPTION,
      paginate: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE,
        total: "",
        name: "",
      },
      subjects: [],
      fields: [
        { key: "index", label: this.$i18n.t("list_users.label.no") },
        {
          key: "name",
          label: this.$i18n.t("list_subjects.label.name"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "description",
          label: this.$i18n.t("list_subjects.label.description"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "is_active",
          label: this.$i18n.t("list_subjects.label.is_active"),
          sortable: true,
          sortDirection: "desc",
        },
        { key: "actions", label: this.$i18n.t("list_subjects.label.action") },
      ],
    };
  },
  created() {
    this.getData();
  },

  methods: {
    changePage(page) {
      this.getData();
      this.paginate.page = page;
    },
    async getData() {
      await this.$store
        .dispatch("subject/GET_SUBJECTS", { params: this.paginate })
        .then((response) => {
          this.subjects = response.data.data;
          this.paginate.perPage = response.data.per_page;
          this.paginate.total = response.data.total;
        });
    },
    detailSubject(id) {
      this.$router.push({ name: "subject.detail", params: { id: id } });
    },
    async deleteSubject(id) {
      swal(
        notification.notification(
          this.$i18n.t("list_subjects.label.delete_confirm"),
          this.$i18n.t("list_subjects.label.warning")
        )
      ).then((willDelete) => {
        if (willDelete) {
          swal(this.$i18n.t("list_subjects.label.delete_success"), {
            icon: "success",
          });
          this.$store
            .dispatch("subject/DESTROY_SUBJECT", id)
            .then(() => {
              this.getData();
            })
            .catch(() => {});
        } else {
          return;
        }
      });
    },
    async searchSubject() {
      this.getData();
      this.paginate.page = DEFAULT_PAGE;
    },

    async detail(id) {
      await this.$store
        .dispatch("subject/COUNT_COURSES_TASKS_USERS", id)
        .then((response) => {
          this.count.countCourse = response.data[1];
          this.count.countTask = response.data[0];
          this.count.countUser = response.data[2];
        });
      await this.$store
        .dispatch("subject/GET_SUBJECT_BY_ID", id)
        .then((response) => {
          this.subject = response.data;
        });
    },
    customPaginate() {
      this.getData();
    },
    async change(id) {
      await this.$store.dispatch("subject/UPDATE_ACTIVE", id);
      this.getData();
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
