<template>
  <div class="add_users">
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
      <div class="row">
        <div class="col-xl-3 col-lg-6">
          <stats-card
            title="Total traffic"
            type="gradient-red"
            sub-title="350,897"
            icon="ni ni-active-40"
            class="mb-4 mb-xl-0"
          >

            <template slot="footer">
              <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
              <span class="text-nowrap">Since last month</span>
            </template>
          </stats-card>
        </div>
        <div class="col-xl-3 col-lg-6">
          <stats-card
            title="Total traffic"
            type="gradient-orange"
            sub-title="2,356"
            icon="ni ni-chart-pie-35"
            class="mb-4 mb-xl-0"
          >

            <template slot="footer">
              <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 12.18%</span>
              <span class="text-nowrap">Since last month</span>
            </template>
          </stats-card>
        </div>
        <div class="col-xl-3 col-lg-6">
          <stats-card
            title="Sales"
            type="gradient-green"
            sub-title="924"
            icon="ni ni-money-coins"
            class="mb-4 mb-xl-0"
          >

            <template slot="footer">
              <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> 5.72%</span>
              <span class="text-nowrap">Since last month</span>
            </template>
          </stats-card>
        </div>
        <div class="col-xl-3 col-lg-6">
          <stats-card
            title="Performance"
            type="gradient-info"
            sub-title="49,65%"
            icon="ni ni-chart-bar-32"
            class="mb-4 mb-xl-0"
          >

            <template slot="footer">
              <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 54.8%</span>
              <span class="text-nowrap">Since last month</span>
            </template>
          </stats-card>
        </div>
      </div>
    </base-header>
    <div id="content">
      <div class="container">
        <header>
          <h1>{{ $t("user_detail.title.user_detail") }}</h1>
        </header>
        <div class="row">
          <div class="col-4">
            <div class="img-thumb">
              <img src="../../assets/khanh.jpg" alt="">
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

<style scoped>
  header
  {
    text-align: center;
  }

  #content .container
  {
    width: 1100px;
  }

  #content .container header
  {
    text-align: center;
    margin-bottom: 50px;
  }

  label
  {
    font-size: 30px;
    padding-right: 10px;
    margin-bottom: 30px;
  }

  h2
  {
    display: inline-block;
  }

  .img-thumb
  {
    width: 200px;
    height: 200px;
    overflow: hidden;
  }

  img
  {
    width: 100%;
    height: 100%;
  }
</style>
