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
              <div class="add_users">
                <header class="text-center">
                  <h1>{{ $t("user_screen.title.user_screen") }}</h1>
                </header>
                <div class="container ">
                  <ValidationObserver v-slot="{ handleSubmit }">
                    <b-form @submit.prevent="handleSubmit(onSubmit)">
                      <ValidationProvider
                        :name="$t('user_screen.label.name')"
                        rules="required|min:4|max:25"
                        v-slot="{ errors }"
                      >
                        <div class="form-group">
                        <b-form-group
                          id="input-group-1"
                          :label="$t('user_screen.label.name')"
                          label-for="input-1">
                          <b-form-input
                            id="input-1"
                            v-model="user.name"
                            type="text"
                            :placeholder="$t('course_screen.message.enter_name_course')"
                          ></b-form-input>
                          <span class="err">{{ errors[0] }}</span>
                        </b-form-group>
                        </div>
                      </ValidationProvider>
                      <ValidationProvider
                        :name="$t('user_screen.label.birth_day')"
                        rules="required|min:4|max:25"
                        v-slot="{ errors }"
                      >
                        <div class="form-group">
                          <label for="birthday">{{ $t("user_screen.label.birth_day") }}</label
                          ><br/>
                          <input
                            v-model="user.birthday"
                            type="date"
                            class="form-control"
                            id="birthday"
                          />
                        </div>
                        <span class="err">{{ errors[0] }}</span>
                      </ValidationProvider>
                      <ValidationProvider
                        :name="$t('user_screen.label.phone_number')"
                        :rules="{ regex: /^(0)[0-9]{9}$/, required: true }"
                        v-slot="{ errors }"
                      >
                        <div class="form-group">
                          <label for="phone">
                            {{ $t("user_screen.label.phone_number") }}
                          </label>
                          <br />
                          <input
                            v-model="user.phone"
                            type="tel"
                            class="form-control"
                            id="phone"
                            name="phone"
                            placeholder="0123-45-678"
                          />
                        </div>
                        <span class="err">{{ errors[0] }}</span>
                      </ValidationProvider>
                      <ValidationProvider
                        :name="$t('user_screen.label.address')"
                        rules="required|min:10|max:30"
                        v-slot="{ errors }"
                      >
                        <div class="form-group">
                          <label for="address">
                            {{ $t("user_screen.label.address") }}
                          </label>
                          <br />
                          <input
                            v-model="user.address"
                            type="text"
                            class="form-control"
                            id="address"
                            :placeholder="$t('user_screen.label.address')"
                          />
                        </div>
                        <span class="err">{{ errors[0] }}</span>
                      </ValidationProvider>
                      <ValidationProvider
                        :name="$t('user_screen.label.email')"
                        rules="required|min:10|max:30"
                        v-slot="{ errors }"
                      >
                        <div class="form-group">
                          <label for="email">{{ $t("user_screen.label.email") }}</label
                          ><br />
                          <input v-model="user.email"
                                 type="email"
                                 class="form-control"
                                 id="email"
                                 :placeholder="$t('user_screen.label.email')"
                          />
                        </div>
                        <span class="err">{{ errors[0] }}</span>
                      </ValidationProvider>
                      <ValidationProvider
                        :name="$t('user_screen.label.password')"
                        rules="required|min:10|max:30"
                        v-slot="{ errors }"
                      >
                        <div class="form-group">
                          <label for="password">
                            {{ $t("user_screen.label.password") }}
                          </label>
                          <br />
                          <input
                            v-model="user.password"
                            type="password"
                            class="form-control"
                            id="password"
                            :placeholder="$t('user_screen.label.password')"
                          />
                        </div>
                        <span class="err">{{ errors[0] }}</span>
                      </ValidationProvider>
                      <ValidationProvider
                        :name="$t('user_screen.label.password')"
                        rules="required"
                        v-slot="{ errors }"
                      >
                        <div class="form-group">
                          <label>{{ $t("user_screen.label.course") }}</label>
                          <br />
                          <multiselect
                            v-model="value"
                            :options="options"
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
                        <span class="err">{{ errors[0] }}</span>
                      </ValidationProvider>
                      <b-form-group :label="$t('course_screen.label.image')">
                        <div>
                          <b-form-file
                            v-model="imageData"
                            :state="Boolean(imageData)"
                            :placeholder="$t('course_screen.message.choose_a_file_or_drop_it_here')"
                            @change="previewImage">
                          ></b-form-file>
                          <div class="mt-3" v-if="pictureUrl">
                            <img id="imgUser" :src="pictureUrl">
                          </div>
                        </div>
                      </b-form-group>
                        <b-button  type="submit" class="btn btn-success float-right">
                          {{ $t("user_screen.button.submit") }}
                        </b-button>
                        <b-button
                        :to="{ name: 'users.list' }"
                        class="btn btn-danger float-right"
                      >
                        {{ $t("course_screen.button.cancel") }}
                      </b-button>
                    </b-form>
                  </ValidationObserver>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
import Firebase from "firebase";
import ProjectsTable from "@/layout/HeaderCard";
require("@/sass/modules/add-user.css");

export default {
  components: {
    ProjectsTable,
  },
  created() {
    this.getCourse();
  },
  data() {
    return {
      value: [],
      options:[],
      user:
        {
          name: "",
          birthday: "",
          phone: "",
          address: "",
          email: "",
          password: "",
          course_id: [],
          img_path: "",
        },
      imageData: null,
      pictureUrl: null,
      uploadValue: 0,
      defaultImage: require("@/assets/imgs/default.jpeg"),
      notificationSystem: {
        options: {
          success: {
            position: "topCenter",
          },
          error: {
            position: "topRight",
          },
        },
      },
    };
  },
  methods: {
    async getCourse(){
      await this.$store.dispatch('user/GET_COURSE',{}).then(
        (response)=>{
          this.options = response.data.data;
        })
    },
    async onSubmit() {
      this.user.img_path = this.pictureUrl ? this.pictureUrl : this.defaultImage;
      this.user.course_id = this.value.map((obj) => obj.id);
      let params = {
        user: this.user,
      }
      await this.$store.dispatch("user/STORE_USER", params).then(() => {
         {
          this.$toast.success(
            this.$i18n.t("list_subjects.label.update_success"),
            "OK",
            this.notificationSystem.options.success
          )
            this.$router.push({ name: "users.list" });
        }
      });
    },
    previewImage(event) {
      this.imageData = event.target.files[0];
      const storageRef = Firebase.storage().ref();
      const imgRef = storageRef.child(`imagesUser/${this.imageData.name}`);
      imgRef.put(this.imageData).then(() => {
        imgRef.getDownloadURL().then(url => {
          this.pictureUrl = url;
        })
      })
    }
  },
};
</script>
