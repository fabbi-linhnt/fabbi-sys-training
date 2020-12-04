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
                  <h3>{{ $t("user_detail.title.user_detail") }}</h3>
                </div>
              </div>
            </div>
            <div class="content-detail-user">
              <b-row>
                <b-col cols="4">
                  <img src="@/assets/imgs/khanh.jpg" class="image-user" />
                </b-col>
                <b-col cols="8">
                  <div class="form-group">
                    <label>{{ $t("user_detail.label.name") }}:</label>
                    {{ user.name }}
                  </div>
                  <div class="form-group">
                    <label>{{ $t("user_detail.label.email") }}:</label>
                    {{ user.email }}
                  </div>
                  <div class="form-group">
                    <label>{{ $t("user_detail.label.phone") }}:</label>
                    <p class="task-content">
                      {{ user.phone }}
                    </p>
                  </div>
                  <div class="form-group">
                    <label>{{ $t("user_detail.label.address") }}:</label>
                    {{ user.address }}
                  </div>
                </b-col>
              </b-row>

              <br />
              <div class="form-group">
                <label>{{ $t("user_detail.label.course_completed") }}</label>
                <b-table striped hover :items="courses" :fields="Field">
                  <template #cell(index)="row">
                    {{
                      ++row.index +
                      (Number(paginate.page) - 1) * Number(paginate.perPage)
                    }}
                  </template>
                </b-table>
              </div>
              <div class="pagination">
                <b-pagination
                  v-model="paginate.page"
                  :total-rows="paginate.total"
                  :per-page="paginate.perPage"
                  aria-controls="my-table"
                  @change="changePage(paginate.page)"
                ></b-pagination>
              </div>
              <router-link class="btn btn-primary" :to="{ name: 'users.list' }">
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
require("@/sass/modules/detail-user.css");
import ProjectsTable from "@/layout/HeaderCard";

export default {
  components: {
    ProjectsTable,
  },
  data() {
    return {
      user: {
        name: "",
        email: "",
        phone: "",
        address: "",
      },
      paginate: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE_USER,
        total: "",
      },
      courses: [],
      Field: [
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

  methods: {
    async getData() {
      await this.$store.dispatch("detailUser/GETDATA_ACTION", {
        params: {
          id: this.id,
        },
      });
    },
    async getCoursesOfUser() {
      await this.$store.dispatch("course/GET_COURSES", {}).then((res) => {
        this.courses = res.data;
        this.paginate.perPage = res.per_page;
        this.paginate.total = res.total;
      });
    },
  },
  created() {
    this.getCoursesOfUser();
  },
};
</script>
