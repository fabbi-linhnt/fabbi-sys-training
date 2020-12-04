<template>
  <div class="add_users">
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <div id="content">
      <div class="container">
        <header>
          <h1>{{ $t("user_detail.title.user_detail") }}</h1>
        </header>
        <div class="row">
          <div class="col-4">
            <div class="img-thumb">
              <img src="../../assets/imgs/khanh.jpg" alt="">
            </div>
          </div>
          <div class="col-7">
            <div class="info">
              <div class="name">
                <label for="name">{{ $t("user_detail.label.name") }}: </label>
                <h2>{{ fakeData.name }}</h2>
              </div>
              <div class="course">
                <label for="course">{{ $t("user_detail.label.course_participated") }}:</label>
                <h2 v-for="(data,index) in fakeData.courseParticipatedName" :key="index">{{ data }},</h2>
              </div>
              <div class="subject">
                <label for="subject">{{ $t("user_detail.label.number_of_subject_participated") }}:</label>
                <h2>{{ fakeData.numberSubject }}</h2>
              </div>
              <div class="task">
                <label for="task">{{ $t("user_detail.label.number_of_task_participated") }}:</label>
                <h2>{{ fakeData.numberTask }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
require("@/sass/modules/add-user.css")

export default {
  data() {
    return {}
  },
  props: [
    'id'
  ],
  computed: {
    ...mapGetters({
        fakeData: "detailUser/GET_DETAIL_STATE"
    })
  },
  methods: {
    async getData() {
      await this.$store.dispatch("detailUser/GETDATA_ACTION",{
        params: {
          id: this.id
        }
      });
    }
  },
  created() {
    this.getData();
  }
}
</script>
