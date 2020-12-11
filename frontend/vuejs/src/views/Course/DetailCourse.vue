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
                  <h3>{{ $t("course_screen.label.course_detail") }}</h3>
                </div>
              </div>
            </div>
            <div class="content-detail-course">
              <b-row>
                <b-col cols="4">
                  <img src="@/assets/imgs/khanh.jpg" class="image-course" />
                </b-col>
                <b-col cols="8">
                  <div class="form-group">
                    <label>{{ $t("course_screen.label.name") }}:</label>
                    {{ course.name }}
                  </div>
                  <div class="form-group">
                    <label>{{ $t("course_screen.label.description") }}:</label>
                    <p class="task-content">
                      {{ course.description }}
                    </p>
                  </div>
                </b-col>
              </b-row>
              <br />
              <div class="form-group">
                <label>{{ $t("course_screen.label.user_course") }}</label>
                <b-table striped hover :items="users" :fields="Field">
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
              <router-link class="btn btn-primary" :to="{ name: 'courses.list' }">
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
require("@/sass/modules/detail-course.css");
import ProjectsTable from "@/layout/HeaderCard";

export default {
  components: {
    ProjectsTable,
  },
  data() {
    return {
      users: [],
      paginate: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE_USER,
        total: "",
      },
      course: {
        name: "",
        description: "",
      },
      Field: [
        { key: "index", label: this.$i18n.t("list_users.label.no") },
        {
          key: "name",
          label: this.$i18n.t("list_users.label.name"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "phone",
          label: this.$i18n.t("list_users.label.phone_number"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "email",
          label: this.$i18n.t("list_users.label.gmail"),
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "address",
          label: this.$i18n.t("list_users.label.address"),
          sortable: true,
          sortDirection: "desc",
        },
        { key: "actions", label: this.$i18n.t("task_screen.label.action") },
      ],
    };
  },
  props: ["id"],
  created() {
    this.getData(),
    this.getCoursesOfUser()
  },
  methods: {
    async getData() {
      await this.$store
        .dispatch("course/GET_ID_COURSE", this.id)
        .then((res) => {
          this.course = res.data
        });
    },
    async getCoursesOfUser() {
      await this.$store.dispatch("course/GET_USER_ID_COURSE", this.id).then((res) => {
        this.users = res.data.data;
        this.paginate.perPage = res.data.per_page;
        this.paginate.total = res.data.total;
      });
    },
  },
};
</script>
