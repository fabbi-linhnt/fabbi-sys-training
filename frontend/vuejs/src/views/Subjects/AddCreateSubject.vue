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
                  <h3 class="mb-0" v-if="id">
                    {{ $t("list_subjects.title.editSubject") }}
                  </h3>
                  <h3 class="mb-0" v-else>
                    {{ $t("list_subjects.title.addSubject") }}
                  </h3>
                </div>
              </div>
            </div>

            <form>
              <div class="form-group" id="subject-name">
                <label for="exampleInputEmail1">{{
                  $t("list_subjects.label.name")
                }}</label>
                <input
                  v-model="Subject.name"
                  id="name"
                  class="form-control"
                  aria-describedby="emailHelp"
                />
              </div>
              <div class="form-group" id="subject-description">
                <label for="exampleInputPassword1">{{
                  $t("list_subjects.label.description")
                }}</label>
                <textarea
                  v-model="Subject.description"
                  id="description"
                  class="form-control"
                  name=""
                  cols="50"
                  rows="10"
                ></textarea>
              </div>
              <label class="radio" for="exampleInputPassword1">{{
                $t("list_subjects.label.is_active")
              }}</label>
              <div class="radio">
                <label
                  ><input
                    v-if="Subject.is_active == false"
                    type="radio"
                    id="an"
                    name="exampleRadios"
                    value=""
                    checked
                  />
                  <input
                    v-else
                    type="radio"
                    id="an"
                    name="exampleRadios"
                    value=""
                  />{{ $t("list_subjects.label.unActive") }}</label>
              </div>
              <div class="radio">
                <label
                  ><input
                    v-if="Subject.is_active"
                    type="radio"
                    id="hien"
                    name="exampleRadios"
                    value=""
                    checked
                  />
                  <input
                    v-else
                    type="radio"
                    id="hien"
                    name="exampleRadios"
                    value=""
                  />{{ $t("list_subjects.label.active") }}</label>
              </div>
              <div class="form-group" id="multiselect-course">
                <label for="exampleInputPassword1">{{
                  $t("list_subjects.label.course")
                }}</label>
                <multiselect
                  v-model="value"
                  :options="courseData"
                  :multiple="true"
                  :close-on-select="false"
                  :clear-on-select="false"
                  :preserve-search="true"
                  :placeholder="$t('list_subjects.label.nameCourse')"
                  label="name"
                  track-by="name"
                  :preselect-first="true"
                >
                </multiselect>
              </div>

              <button
                v-if="id"
                id="update-subject"
                type="submit"
                class="btn btn-success"
                @click="update()"
              >
                {{ $t("list_subjects.button.update") }}
              </button>
              <button
                v-else
                id="add-subject"
                type="submit"
                class="btn btn-success"
                @click="add()"
              >
                {{ $t("list_subjects.button.add") }}
              </button>
              <router-link
                id="cancel"
                class="btn btn-success"
                :to="{ name: 'Subjects' }"
                >{{ $t("list_subjects.button.cancel") }}</router-link
              >
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import ProjectsTable from "@/components/HeaderCard";

export default {
  components: {
    ProjectsTable,
    Multiselect,
  },
  data() {
    return {
      Subject: {
        name: "",
        description: "",
        is_active: "",
        course_id: [],
      },
      value: [],
      courseData: [],
    };
  },
  props: ["id"],
  created() {
    this.getData();
    if (this.id) {
      this.getSubject();
      this.listCourse();
    }
  },

  methods: {
    async getData() {
      await this.$store.dispatch("subject/course").then((res) => {
        this.courseData = res.data;
      });
    },
    async getSubject() {
      await this.$store
        .dispatch("subject/getSubjectById", this.id)
        .then((response) => {
          this.Subject.name = response.data.name;
          this.Subject.description = response.data.description;
          this.Subject.is_active = response.data.is_active;
        });
    },
    async update() {
      for (let index = 0; index < this.value.length; index++) {
        this.Subject.course_id[index] = this.value[index].id;
      }
      this.Subject.is_active = document.getElementById("an").checked;
      await this.$store.dispatch("subject/updateData", {
        subjects: this.Subject,
        id: this.id,
      });
    },
    async add() {
      this.Subject.is_active = document.getElementById("an").checked;
      for (let index = 0; index < this.value.length; index++) {
        this.Subject.course_id[index] = this.value[index].id;
      }
      await this.$store.dispatch("subject/addData", this.Subject);
    },
    async listCourse() {
      await this.$store.dispatch("subject/listCourse", this.id).then((res) => {
        this.value = res.data;
      });
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style> 

<style>
.radio {
  margin-left: 10%;
}
#add-subject {
  float: right;
  margin-right: 10%;
}
#update-subject {
  float: right;
  margin-right: 10%;
}
#cancel {
  float: right;
  background-color: red;
  margin-right: 5%;
}
#multiselect-course {
  margin-left: 10%;
  margin-right: 10%;
}
#subject-name {
  margin-left: 10%;
  margin-right: 10%;
}
#subject-description {
  margin-left: 10%;
  margin-right: 10%;
}
</style>


