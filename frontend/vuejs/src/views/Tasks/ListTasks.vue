<template>
  <div class="main-content">
    <base-header
      type="gradient-success"
      class="pb-6 pb-8 pt-5 pt-md-8"
    ></base-header>
    <div class="content">
      <router-link to="/tasks/create" class="btn btn-primary">
        {{ $t("task_screen.label.create_task") }}
      </router-link>
      <form
        class="form-inline d-flex justify-content-center md-form form-sm mt-0 col-4"
        @submit.prevent="getData()"
      >
        <label>{{ $t("task_screen.label.search_task") }}</label>
        <input
          class="form-control form-control-sm ml-3 w-75"
          type="text"
          v-model="paginate.name"
          v-bind:placeholder="$t('task_screen.label.search_task')"
          aria-label="Search"
        />
        <button class="btn">{{ $t("task_screen.button.search_btn") }}</button>
      </form>
      <div class="custom-paginate">
        <label class="typo__label">{{ $t("task_screen.label.custom_paginate") }}</label>
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
      <b-table show-empty small stacked="md" :items="tasks" :fields="fields">
        <template #cell(index)="row">
          {{ ++row.index }}
        </template>
        <template v-slot:cell(is_active)="row">
          <p>
            {{ row.item.is_active == 1 ? $t("task_screen.label.task_active") : $t("task_screen.label.task_inactive") }}
          </p>
        </template>
        <template v-slot:cell(actions)="row">
          <b-button class="btn btn-danger" @click="onDeleteTask(row.item.id)"
          >
            {{ $t("task_screen.label.task_delete") }}
          </b-button
          >
          <router-link
            class="btn btn-success"
            :to="{ name: 'task.update', params: { id: row.item.id }}"
          >
            {{ $t("task_screen.label.task_update") }}
          </router-link
          >
          <router-link
            class="btn btn-primary"
            :to="{ name: 'task.detail', params: { id: row.item.id }}"
          >
            {{ $t("task_screen.label.task_detail") }}
          </router-link
          >
        </template>
        <template v-slot:cell(user_task)="row">
          <router-link
            class="btn btn-primary"
            :to="{ name: 'report.list', params: { id: row.item.id}}"
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
          @change="changePage"
        ></b-pagination>
      </div>
    </div>
  </div>
</template>

<script>

import {DEFAULT_OPTION, DEFAULT_PERPAGE} from "../../definition/constants";

export default {
  name: "Tasks",
  data() {
    return {
      options: DEFAULT_OPTION,
      tasks: [],
      paginate: {
        page: 1,
        perPage: DEFAULT_PERPAGE,
        total: 0,
        name: '',
      },
      fields: [
        {key: 'index', label: this.$i18n.t("task_screen.label.task_index")},
        {key: 'name', label: this.$i18n.t("task_screen.label.task_name"), sortable: true, sortDirection: 'desc'},
        {
          key: 'description',
          label: this.$i18n.t("task_screen.label.task_description"),
          sortable: true,
          sortDirection: 'desc'
        },
        {key: 'content', label: this.$i18n.t("task_screen.label.task_content"), sortable: true, sortDirection: 'desc'},
        {
          key: 'deadline',
          label: this.$i18n.t("task_screen.label.task_deadline"),
          sortable: true,
          sortDirection: 'desc'
        },
        {key: 'is_active', label: this.$i18n.t("task_screen.label.task_isActive")},
        {key: 'user_task', label: this.$i18n.t("task_screen.label.task_user")},
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
      if (this.paginate.name) {
        this.paginate.page = 1;
      }
      this.$store.dispatch("task/getTasks", {params: this.paginate})
        .then(response => {
          this.tasks = response.data;
          this.paginate.perPage = response.per_page;
          this.paginate.total = response.total;
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
      await this.$store.dispatch("task/destroy", id)
        .then(() => {
          this.getData();
          this.makeToast(this.$i18n.t("task_screen.message.task_msgDelete"), 'success');
        })
        .catch(() => {
        })
    },
    customPaginate() {
      this.getData();
    }
  },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
.content {
  padding: 50px;
}

.custom-paginate {
  float: right;
  margin-right: 50px;
  width: 200px;
}
</style>
