<template>
  <div class="main-content">
    <base-header
      type="gradient-success"
      class="pb-6 pb-8 pt-5 pt-md-8"
    >
    </base-header>
    <div class="content">
      <router-link class="btn btn-primary" :to="{ name: 'users.add'}">
        {{ $t("list_users.label.add") }}
      </router-link>
      <form
        class="form-inline d-flex justify-content-center md-form form-sm mt-0 col-4"
        @submit.prevent="getData()"
      >
        <label>{{ $t("list_users.label.search_task") }}</label>
        <input
          class="form-control form-control-sm ml-3 w-75"
          type="text"
          v-model="paginate.search"
          v-bind:placeholder="$t('list_users.label.search_task')"
          aria-label="Search"
        />
        <button class="btn">
          {{ $t("list_users.button.search_btn") }}
        </button>
      </form>
      <div class="custom-paginate">
        <label class="typo__label">{{ $t("list_users.label.custom_paginate") }}</label>
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
      <div style="clear: both"></div>
      <br/>
      <b-table show-empty small stacked="md" :items="users" :fields="fields">
        <template #cell(index)="row">
          {{ ++row.index }}
        </template>
        <template v-slot:cell(actions)="row">
          <b-button class="btn btn-danger" @click="onDeleteTask(row.item.id)">
            {{ $t("list_users.button.delete") }}
          </b-button>
          <router-link
            class="btn btn-success"
            :to="{ name: 'users.add', params: { id: row.item.id }}"
          >
            {{ $t("list_users.button.update") }}
          </router-link>
          <router-link
            class="btn btn-primary"
            :to="{ name: 'users.detail', params: { id: row.item.id }}"
          >
            {{ $t("list_users.button.show") }}
          </router-link>
        </template>
      </b-table>
      <div class="pagination">
        <b-pagination
          v-model="paginate.page"
          :total-rows="paginate.total"
          :per-page="paginate.perPage"
          aria-controls="my-table"
          @change="changePage"
        >
        </b-pagination>
      </div>
    </div>
  </div>
</template>

<script>
import {DEFAULT_OPTION, DEFAULT_PERPAGE} from "../../definition/constants";

export default {
  name: "ListUsers",
  data() {
    return {
      options: DEFAULT_OPTION,
      users: [],
      paginate: {
        page: 1,
        perPage: DEFAULT_PERPAGE,
        total: 0,
        search: '',
      },
      fields: [
        {key: 'index', label: this.$i18n.t("list_users.label.no")},
        {key: 'name', label: this.$i18n.t("list_users.label.name"), sortable: true, sortDirection: 'desc'},
        {key: 'phone', label: this.$i18n.t("list_users.label.phone_number"), sortable: true, sortDirection: 'desc'},
        {key: 'email', label: this.$i18n.t("list_users.label.gmail"), sortable: true, sortDirection: 'desc'},
        {key: 'address', label: this.$i18n.t("list_users.label.address"), sortable: true, sortDirection: 'desc'},
        {key: 'actions', label: 'Actions'}
      ],
    }
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
      if (this.paginate.search) {
        this.paginate.page = 1;
      }
      this.$store.dispatch("user/GET_USER", {params: this.paginate})
        .then(response => {
          this.users = response.data.data;
          this.paginate.perPage = response.data.per_page;
          this.paginate.total = response.data.total;
        })
    },
    makeToast(message, variant) {
      this.$bvToast.toast(message, {
        variant: variant,
        solid: true
      })
    },
    async onDeleteTask(id) {
      if (!confirm('Xóa?')) {
        return;
      }
      await this.$store.dispatch("user/DELETE_USER", id)
        .then(() => {
          this.getData();
          this.makeToast(this.$i18n.t("list_users.message.task_msgDelete"), 'success');
        })
        .catch(() => {})
    },
    customPaginate() {
      this.getData();
    }
  },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
.content
{
  padding: 50px;
}

.custom-paginate
{
  float: right;
  margin-right: 50px;
  width: 200px;
}
</style>
