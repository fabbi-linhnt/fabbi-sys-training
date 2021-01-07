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
                  <img src="@/assets/imgs/khanh.jpg" class="image-user"/>
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

              <br/>
              <div class="form-group">
                <label>{{ $t("user_detail.label.course_participated") }}</label>
                <b-table striped hover :items="courses" :fields="Field">
                  <template #cell(index)="row">
                    {{
                      ++row.index +
                      (Number(paginate.page) - 1) * Number(paginate.perPage)
                    }}
                  </template>
                  <template #cell(status)="row">
                    <div v-if="checkStatusCourse == true">
                      <b-button
                        v-if="row.item.status == 0"
                        @click.prevent="assignUserToCourse(row.item.id)"
                        disabled
                      >
                        {{ $t("user_detail.label.assign") }}
                      </b-button>
                      <b-button
                        id="button_completed_course"
                        v-else-if="row.item.status == 2"
                        disabled
                      >
                        {{ $t("user_detail.label.completed_course") }}
                      </b-button>
                      <b-button id="button_completed_course" v-else disabled>
                        {{ $t("user_detail.label.coming_course") }}
                      </b-button>
                    </div>
                    <div v-else>
                      <b-button
                        v-if="row.item.status == 0"
                        @click.prevent="assignUserToCourse(row.item.id)"
                      >
                        {{ $t("user_detail.label.assign") }}
                      </b-button>
                      <b-button id="button_completed_course" v-else disabled>
                        {{ $t("user_detail.label.completed_course") }}
                      </b-button>
                    </div>
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
import {DEFAULT_PERPAGE_USER, DEFAULT_PAGE} from "@/definition/constants";

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
      checkStatusCourse: false,
      paginate: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE_USER,
        total: "",
      },
      courses: [],
      Field: [
        {key: "index", label: this.$i18n.t("common.label.index")},
        {key: "name", label: this.$i18n.t("list_subjects.label.name")},
        {
          key: "description",
          label: this.$i18n.t("list_subjects.label.description"),
        },
        { key: "status", label: this.$i18n.t("task_screen.label.action") },
      ],
    };
  },
  props: ["id"],
  methods: {
    async getData() {
      await this.$store.dispatch("user/GET_USER_BY_ID", this.id).then((res) => {
        this.user = res.data;
      });
    },
    async getCoursesOfUser() {
      await this.$store
        .dispatch("user/GET_COURSES_OF_USER", this.id)
        .then((res) => {
          this.courses = res.data.data;
          this.paginate.perPage = res.data.per_page;
          this.paginate.total = res.data.total;
          for (let i = 0; i < this.courses.length; i++) {
            if (this.courses[i].status == 1) {
              this.checkStatusCourse = true;
              break;
            }
          }
        });
    },
    async assignUserToCourse(courseId) {
      await this.$store
        .dispatch("user/ASSIGN_USER_TO_COURSE", {
          id: courseId,
          user: { userId: this.id },
        })
        .then(() => {
          this.getCoursesOfUser();
        });
      this.$router.push({
        name: "calendar",
        params: {
          courseId: courseId,
          userId: this.id,
        },
      });
    },
  },
  created() {
    this.getCoursesOfUser();
    this.getData();
  },
};
</script>
