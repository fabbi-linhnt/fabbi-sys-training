<template>
  <div>
    <div>
      <projects-table></projects-table>
    </div>
    <div>
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">
                    {{ $t("calendar.title.schedule") }}
                  </h3>
                  <Fullcalendar :options="calendarOptions" />
                  <vmodal name="example" v-if="showModal">
                    <fieldset>
                      <legend>
                        {{ $t("calendar.label.detail") }}
                      </legend>
                      <b>{{ $t("calendar.label.title") }} :</b>
                      {{ event.title }}
                      <br />
                      <b>{{ $t("calendar.label.time_start") }} :</b>
                      {{ event.start }}
                      <br />
                      <b>{{ $t("calendar.label.time_end") }} :</b>
                      {{ event.end }}
                      <br />
                    </fieldset>
                    <fieldset v-if="event.backgroundColor == ''">
                      <legend>
                        {{ $t("calendar.label.update_time_end") }} :
                      </legend>
                      <b>{{ $t("calendar.label.time_end") }} :</b>
                      <input type="date" v-model="editEvent.end" />
                      <b-button
                        id="button-update-timeTaskEnd"
                        @click="updateTask()"
                      >
                        {{ $t("task_screen.button.update_btn") }}
                      </b-button>
                      <br />
                      <b v-if="checkTimeTaskEnd" id="error-time-end">
                        {{ $t("task_screen.label.time_task") }}
                      </b>
                    </fieldset>
                  </vmodal>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ProjectsTable from "@/layout/HeaderCard";
import Fullcalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import listPlugin from "@fullcalendar/list";
import timeGridPlugin from "@fullcalendar/timegrid";
require("@fullcalendar/timegrid/main.min.css");
require("@fullcalendar/daygrid/main.min.css");
require("@/sass/modules/calendar.css");

export default {
  components: {
    ProjectsTable,
    Fullcalendar,
  },
  props: ["courseId", "userId"],
  data() {
    return {
      event: [],
      editEvent: {
        title: "",
        start: "",
        end: "",
      },
      showModal: false,
      checkTimeTaskEnd: false,
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin, listPlugin, timeGridPlugin],
        initialView: "dayGridMonth",
        weekends: true,
        events: [],
        eventClick: this.handleClick,
      },
      notificationSystem: {
        options: {
          success: {
            position: "topCenter",
          },
        },
      },
    };
  },
  created() {
    this.getData();
  },
  methods: {
    getData() {
      this.$store
        .dispatch("course/GET_TIME_COURSE", {
          params: {
            courseId: this.courseId,
            userId: this.userId,
          },
        })
        .then((res) => {
          this.calendarOptions.events = res.data;
        });
    },
    handleClick(arg) {
      this.showModal = true;
      this.$modal.show("example");
      this.event = arg.event;
      this.editEvent.title = this.event.title;
      this.editEvent.start = this.formatDate(this.event.start);
      this.editEvent.end = this.formatDate(this.event.end);
    },
    formatDate(date) {
      var options = { year: "numeric", month: "2-digit", day: "2-digit" };
      let string = date
        .toLocaleDateString("ko-KR", options)
        .replace(/\. /g, "-");
      return string.substr(0, string.length - 1);
    },
    updateTask() {
      let timeEnd = new Date(this.editEvent.end);
      let timeStart = new Date(this.editEvent.start);
      let diff = timeEnd.getTime() - timeStart.getTime();
      if (diff <= 0) {
        this.checkTimeTaskEnd = true;
        return;
      } else {
        this.checkTimeTaskEnd = false;
        this.$store
          .dispatch("task/UPDATE_TIME_TASK", {
            id: this.event.id,
            start: this.editEvent.start,
            end: this.editEvent.end,
          })
          .then(() => this.getData());
        this.showModal = false;
        this.$toast.success(
          this.$i18n.t("calendar.label.update_time_end_success"),
          this.$i18n.t("list_subjects.label.success"),
          this.notificationSystem.options.success
        );
      }
    },
  },
};
</script>
