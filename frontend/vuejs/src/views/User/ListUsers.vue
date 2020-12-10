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
                    {{ $t("list_users.title.list_user") }}
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
                v-model="paginate.search"
              >
              </b-form-input>
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
              :items="users"
              :fields="fields"
            >
              <template #cell(index)="row">
                {{
                  ++row.index +
                  (Number(paginate.page) - 1) * Number(paginate.perPage)
                }}
              </template>
              <template v-slot:cell(actions)="row">
                <b-icon
                  icon="trash"
                  variant="danger"
                  font-scale="2"
                  @click="onDeleteUser(row.item.id)"
                  class="deleteUser"
                >
                </b-icon>
                <b-icon
                  icon="info-circle"
                  variant="info"
                  font-scale="2"
                  @click="onDetailUser(row.item.id)"
                  class="detailUser"
                >
                </b-icon>
                <b-icon
                  icon="pencil-square"
                  variant="dark"
                  font-scale="2"
                  @click="
                    $router.push({
                      name: 'user.create',
                      params: { id: row.item.id },
                    })
                  "
                  class="updateUser"
                >
                </b-icon>
              </template>
              <template #cell(name)="row">
                <div class="row">
                  <b-img
                    class="imgUser"
                    rounded="circle"
                    src="https://firebasestorage.googleapis.com/v0/b/fabbi-training.appspot.com/o/imagesCourse%2FScreenshot%20from%202020-11-27%2016-43-37.png?alt=media&token=8ac12b50-a42a-40e7-ada2-9454ef032efe">
                  </b-img>
                  <p class="nameUser"> {{ row.item.name }} </p>
                </div>
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
import notification from "@/js/sweetAlert.js"
import ProjectsTable from "@/layout/HeaderCard";
require("@/sass/modules/list-user.css");

export default {
  components: {
    ProjectsTable,
  },
  name: "ListUsers",
  data() {
    return {
      options: DEFAULT_OPTION,
      users: [],
      paginate: {
        page: DEFAULT_PAGE,
        perPage: DEFAULT_PERPAGE,
        total: "",
        search: "",
      },
      fields: [
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
  created() {
    this.getData();
  },
  methods: {
    changePage(page) {
      this.paginate.page = page;
      this.getData();
    },
    onDetailUser(id) {
      this.$router.push({ name: "user.detail", params: { id: id } });
    },
    async getData() {
      if (this.paginate.search) {
        this.paginate.page = DEFAULT_PAGE;
      }
      await this.$store
        .dispatch("user/GET_USER", { params: this.paginate })
        .then((response) => {
          this.users = response.data.data;
          this.paginate.perPage = response.data.per_page;
          this.paginate.total = response.data.total;
        });
    },
    async onDeleteUser(id) {
      swal(
        notification.notification(
          this.$i18n.t("user_screen.label.delete_confirm"),
          this.$i18n.t("user_screen.label.warning"),
        )
      ).then((willDelete) => {
        if (willDelete) {
          swal(this.$i18n.t("user_screen.label.delete_success"), {
            icon: "success",
          });
          this.$store
            .dispatch("user/DELETE_USER", id)
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
