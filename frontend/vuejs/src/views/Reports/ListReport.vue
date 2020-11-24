<template>
  <div class="main-content">
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8"></base-header>
    <div class="content">
      <h3 class="title">{{ $t("report_screen.label.title") }}</h3>
      <p>{{ $t("task_screen.label.task_name") }}: {{ user_task[0].name }}</p>
      <p>{{ $t("task_screen.label.task_deadline") }}: {{ user_task[0].deadline }}</p>
      <ul class="nav nav-pills switch-btn">
        <li class="nav-item" @click="switchTable(true)">
          <button :class="['btn nav-link ', { active: status }]">{{ $t("report_screen.label.done") }}</button>
        </li>
        <li class="nav-item" @click="switchTable(false)">
          <button :class="['btn nav-link', { active: !status }]">{{ $t("report_screen.label.todo") }}</button>
        </li>
      </ul>
      <div class="overflow-auto">
        <b-table
          id="my-table"
          :items="user_task"
          :fields="customFields"
          :tbody-tr-class="rowClass"
          small
        >
          <template #cell(index)="row">
            {{ ++row.index }}
          </template>
          <template #cell(actions)="row" v-show="status">
            <b-button v-b-modal.modal-center @click="openComment(row.item)">
              {{ $t("report_screen.label.comment") }}
            </b-button>
          </template>
        </b-table>
        <b-pagination
          v-model="paginate.page"
          :total-rows="paginate.total"
          :per-page="paginate.perPage"
          aria-controls="my-table"
        ></b-pagination>
      </div>
    </div>
    <b-modal
      id="modal-center"
      centered
      :ok-title="$t('report_screen.label.pass')"
      :cancel-title="$t('report_screen.label.redo')"
      @cancel="submitTaskDone(DEFAULT_LATE)"
      @ok="submitTaskDone(DEFAULT_ONTIME)"
    >
      <form action="">
        <label>{{ $t("report_screen.label.username") }}:</label> {{ username }}
        <h5>{{ $t("report_screen.label.comment") }}:</h5>
        <textarea v-model="submitComment.comment" class="form-control"></textarea>
      </form>
    </b-modal>
  </div>
</template>
<script>
import {DEFAULT_LATE, DEFAULT_ONTIME, DEFAULT_OPTION, DEFAULT_PERPAGE} from "../../definition/constants";

export default {
  name: "ListReport",
  data() {
    return {
      DEFAULT_LATE : DEFAULT_LATE,
      DEFAULT_ONTIME: DEFAULT_ONTIME,
      user_task: [
        {
          name: '',
          deadline: ''
        }
      ],
      status: true,
      options: DEFAULT_OPTION,
      paginate: {
        page: 1,
        perPage: DEFAULT_PERPAGE,
        total: 0,
        name: '',
      },
      fields: [
        {key: 'index', label: this.$i18n.t("task_screen.label.task_index")},
        {key: 'username', label: this.$i18n.t("report_screen.label.username"), sortable: true, sortDirection: 'desc'},
        {key: 'report', label: this.$i18n.t("report_screen.label.report")},
        {key: 'date_submit', label: this.$i18n.t("report_screen.label.date_submit")},
        {key: 'comment', label: 'comment'},
        {key: 'actions', label: 'Actions', status: true}
      ],
      customFields: [],
      submitComment: {
        id: null,
        comment: null,
        status: null
      },
      username: null
    }
  },
  props: [
    'id'
  ],
  created() {
    this.getUserTask();
  },
  methods: {
    async getUserTask() {
      await this.$store.dispatch('user_task/getUserTask', this.id)
        .then(res => {
          if (this.status) {
            this.customFields = this.fields.filter(field => !field.status)
            this.user_task = res.filter(item => {
              if (item.status === DEFAULT_LATE) {
                return item;
              }
            });
          } else {
            this.customFields = this.fields;
            this.user_task = res.filter(item => {
              if (item.status === DEFAULT_ONTIME) {
                return item;
              }
            })
          }
        })
    },
    switchTable(status) {
      this.status = status;
      this.getUserTask();
    },
    openComment(report) {
      this.username = report.username;
      this.submitComment.id = report.id;
    },
    submitTaskDone(status) {
      this.submitComment.status = status;
      this.$store.dispatch('user_task/storeComment', this.submitComment)
        .then(() => {
          this.submitComment.comment = '';
          this.getUserTask();
          this.makeToast(this.$i18n.t("report_screen.message.comment_success"), 'success');
        })
        .catch(() => {
          this.makeToast(this.$i18n.t("report_screen.message.comment_failed"), 'danger')
        })
    },
    rowClass(item, type) {
      if (!item || type !== 'row') return;
      if (!item.date_submit) return 'table-danger';
      if (this.checkTime(item.date_submit, item.deadline)) {
        return 'table-danger';
      }
    },
    checkTime(date_submit, deadline) {
      let date_submitArr = date_submit.split("-");
      let deadlineArr = deadline.split("-");
      let length = date_submit.length;
      for (let i = 0; i < length; i++) {
        if (parseInt(date_submitArr[i]) > deadlineArr[i]) {
          return true;
        }
      }
      return false;
    },
    makeToast(message, variant) {
      this.$bvToast.toast(message, {
        variant: variant,
        solid: true
      })
    }
  },
}
</script>

<style scoped>
.content {
  padding: 50px;
}

.title {
  text-align: center;
}

.switch-btn {
  margin: 10px;
}
</style>
