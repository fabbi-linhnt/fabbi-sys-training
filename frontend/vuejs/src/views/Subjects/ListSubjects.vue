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
                    id="add"
                    class="btn btn-success"
                    :to="{ name: 'subject.create' }"
                  >
                    {{ $t("list_subjects.title.add_new_subject") }}
                  </router-link>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">{{
                    $t("list_subjects.label.selectRecords")
                  }}</label>
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
                <div class="col text-right">
                  <input
                    :placeholder="$t('list_subjects.label.name')"
                    v-model="paginate.name"
                    name="key"
                    id="nameSubject"
                  />
                  <base-button
                    type="primary"
                    size="sm"
                    @click="searchSubject()"
                  >
                    {{ $t("list_subjects.button.search") }}
                  </base-button>
                </div>
              </div>
            </div>
            <div>
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">{{ $t("list_subjects.label.name") }}</th>
                    <th scope="col">
                      {{ $t("list_subjects.label.description") }}
                    </th>
                    <th scope="col">{{ $t("list_subjects.label.time") }}</th>
                    <th scope="col">
                      {{ $t("list_subjects.label.is_active") }}
                    </th>
                    <th scope="col">{{ $t("list_subjects.label.action") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="sub in tableData" :key="sub.id">
                    <td>{{ sub.name }}</td>
                    <td>{{ sub.description }}</td>
                    <td>{{ sub.time }}</td>
                    <td v-if="sub.is_active == 1">
                      <base-button
                        @click="change(sub.id)"
                        icon="ni ni-check-bold"
                        id="icon-active"
                      ></base-button>
                    </td>
                    <td v-else>
                      <base-button
                        @click="change(sub.id)"
                        icon="ni ni-fat-remove"
                        id="icon-unactive"
                      ></base-button>
                    </td>
                    <b-button
                      id="delete"
                      type="button"
                      class="btn btn-primary"
                      @click="deleteSubject(sub.id)"
                    >
                      {{ $t("list_subjects.button.delete") }}
                    </b-button>
                    <b-button
                      id="detail"
                      v-b-modal.modal-detail-subject
                      class="btn btn-primary"
                      @click="detail(sub.id)"
                      >{{ $t("list_subjects.button.detail") }}
                    </b-button>
                    <router-link
                      id="update"
                      class="btn btn-success"
                      :to="{
                        name: 'subject.edit',
                        params: { id: sub.id },
                      }"
                    >
                      {{ $t("list_subjects.button.update") }}
                    </router-link>
                  </tr>
                </tbody>
              </table>
              <div class="overflow-auto" id="paginate">
                <b-pagination
                  v-model="paginate.page"
                  :total-rows="paginate.total"
                  :per-page="paginate.perPage"
                  aria-controls="my-table"
                  @change="changePage"
                ></b-pagination>
              </div>
              <b-modal
                id="modal-detail-subject"
                centered
                :title="$t('list_subjects.label.detailSubject')"
              >
                <p class="my-4">
                  {{ $t("list_subjects.label.nameSubject") }}
                  {{ subject.name }}
                </p>
                <p class="my-4">
                  {{ $t("list_subjects.label.countCourse") }}
                  {{ this.count.countCourse }}
                  {{ $t("list_subjects.label.courses") }}
                </p>
                <p class="my-4">
                  {{ $t("list_subjects.label.countTask") }}
                  {{ this.count.countTask }}
                  {{ $t("list_subjects.label.task") }}
                </p>
                <p class="my-4">
                  {{ $t("list_subjects.label.countTask") }}
                  {{ this.count.countUser }}
                  {{ $t("list_subjects.label.student") }}
                </p>
                <p class="my-4">
                  {{ $t("list_subjects.label.countTask") }} 5
                  {{ $t("list_subjects.label.finish") }}
                </p>
              </b-modal>
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
import Multiselect from "vue-multiselect";
require("@/sass/modules/list-subject.css");

export default {
  components: {
    ProjectsTable,
    Multiselect,
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
      subject: {
        name: "",
        description: "",
        is_active: "",
      },
      tableData: null,
      count: {
        countCourse: "",
        countTask: "",
        countUser: "",
      },
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
          this.tableData = response.data.data;
          this.paginate.perPage = response.data.per_page;
          this.paginate.total = response.data.total;
        });
    },
    async deleteSubject(id) {
      if (confirm(this.$i18n.t("list_subjects.label.deleteConfirm"))) {
        await this.$store.dispatch("subject/DESTROY_SUBJECT", id).then(() => {
          this.getData();
        });
      }
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
