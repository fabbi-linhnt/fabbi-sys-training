<template>
  <div class="card shadow">
    <div class="card-header border-0">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-0">
            {{ $t("course_screen.title.list_course") }}
          </h3>
          <router-link class="btn btn-primary" :to="{ name: 'course.create' }">
            {{ $t("course_screen.button.add_course") }}
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
    <b-table show-empty small stacked="md" :items="courses" :fields="fields">
      <template #cell(index)="row">
        {{
          ++row.index + (Number(paginate.page) - 1) * Number(paginate.perPage)
        }}
      </template>
      <template #cell(is_active)="row">
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
          @click="destroyCourse(row.item.id)"
          class="deleteCousre"
        >
        </b-icon>
        <b-icon
          icon="info-circle"
          variant="info"
          font-scale="2"
          @click="detailCourse(row.item.id)"
          class="detailCourse"
        >
        </b-icon>
        <b-icon
          icon="pencil-square"
          variant="dark"
          font-scale="2"
          @click="
            $router.push({
              name: 'course.edit',
              params: { id: row.item.id },
            })
          "
          class="updateCourse"
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
</template>

<script>
import {
  DEFAULT_OPTION,
  DEFAULT_PERPAGE,
  DEFAULT_PAGE,
} from "@/definition/constants";
import swal from "sweetalert";
import notification from "@/js/sweetAlert.js";
require("@/sass/modules/list-course.css");

export default {
  name: "projects-table",
  data() {
    return {
      fields: [
        { key: "index", label: this.$i18n.t("list_users.label.no") },
        {
          key: "name",
          label: this.$i18n.t("course_screen.label.name"),
        },
        {
          key: "description",
          label: this.$i18n.t("course_screen.label.description"),
        },
        {
          key: "is_active",
          label: this.$i18n.t("course_screen.label.is_active"),
        },
        {
          key: "actions",
          label: this.$i18n.t("course_screen.label.actions"),
        },
      ],
      courses: null,
      type: "",
      filter: "",
      options: DEFAULT_OPTION,
      paginate: {
        perPage: DEFAULT_PERPAGE,
        total: "",
        name: "",
        page: DEFAULT_PAGE,
      },
      course: null,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      await this.$store
        .dispatch("course/GET_COURSES", { params: this.paginate })
        .then((res) => {
          this.courses = res.data;
          this.paginate.perPage = res.per_page;
          this.paginate.total = res.total;
        });
    },
    changePage(page) {
      this.paginate.page = page;
      this.getData();
    },
    customPaginate() {
      this.getData();
    },
    detailCourse(id) {
      this.$router.push({ name: "course.detail", params: { id: id } });
    },
    async destroyCourse(id) {
      swal(
        notification.notification(
          this.$i18n.t("course_screen.label.delete_confirm"),
          this.$i18n.t("course_screen.label.warning")
        )
      ).then((willDelete) => {
        if (willDelete) {
          swal(this.$i18n.t("course_screen.label.delete_success"), {
            icon: "success",
          });
          this.$store
            .dispatch("course/DESTROY_COURSE", id)
            .then(() => {
              this.getData();
            })
            .catch(() => {});
        } else {
          return;
        }
      });
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
