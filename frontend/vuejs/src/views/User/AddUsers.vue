<template>
  <div class="add_users">
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <header class="text-center">
      <h1>{{ $t("user_screen.title.user_screen") }}</h1>
    </header>
    <div class="container ">
      <validation-observer v-slot="{ handleSubmit }">
       <form @submit.prevent="handleSubmit(Submit)">
         <validation-provider
         :name="$t('user_screen.label.name')"
         rules="required|min:4|max:25"
         v-slot="{ errors }">
           <div class="form-group">
             <label for="name">{{ $t("user_screen.label.name") }}</label
             ><br />
             <input v-model="name" type="text" class="form-control" id="name" />
           </div>
           <span class="err">{{ errors[0] }}</span>
         </validation-provider>
         <validation-provider
           :name="$t('user_screen.label.birth_day')"
           rules="required|min:4|max:25"
           v-slot="{ errors }">
           <div class="form-group">
           <label for="birthday">{{ $t("user_screen.label.birth_day") }}</label
           ><br />
           <input
             v-model="birthday"
             type="date"
             class="form-control"
             id="birthday"
           />
         </div>
           <span class="err">{{ errors[0] }}</span>
         </validation-provider>
         <validation-provider
           :name="$t('user_screen.label.phone_number')"
           :rules="{ regex:/^(0)[0-9]{9}$/, required: true}"
           v-slot="{ errors }">
           <div class="form-group">
           <label for="phone">{{ $t("user_screen.label.phone_number") }}</label
           ><br />
           <input
             v-model="phone"
             type="tel"
             class="form-control"
             id="phone"
             name="phone"
             placeholder="0123-45-678"
           />
         </div>
           <span class="err">{{ errors[0] }}</span>
         </validation-provider>
         <validation-provider
           :name="$t('user_screen.label.address')"
           rules="required|min:10|max:30"
           v-slot="{ errors }">
           <div class="form-group">
           <label for="address">{{ $t("user_screen.label.address") }}</label
           ><br />
           <input
             v-model="address"
             type="text"
             class="form-control"
             id="address"
           />
         </div>
           <span class="err">{{ errors[0] }}</span>
         </validation-provider>
         <validation-provider
           :name="$t('user_screen.label.email')"
           rules="required|min:10|max:30"
           v-slot="{ errors }">
           <div class="form-group">
           <label for="email">{{ $t("user_screen.label.email") }}</label
           ><br />
           <input v-model="email" type="email" class="form-control" id="email" />
         </div>
           <span class="err">{{ errors[0] }}</span>
         </validation-provider>
         <validation-provider
           :name="$t('user_screen.label.password')"
           rules="required|min:10|max:30"
           v-slot="{ errors }">
           <div class="form-group">
           <label for="password">{{ $t("user_screen.label.password") }}</label
           ><br />
           <input
             v-model="password"
             type="password"
             class="form-control"
             id="password"
           />
         </div>
           <span class="err">{{ errors[0] }}</span>
         </validation-provider>
         <validation-provider
           :name="$t('user_screen.label.password')"
           rules="required"
           v-slot="{ errors }">
           <div class="form-group">
           <label>{{ $t("user_screen.label.course") }}</label
           ><br />
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
         </validation-provider>
         <b-form-group :label="$t('course_screen.label.image')">
           <div>
             <b-form-file
               v-model="picture"
               :state="Boolean(picture)"
               :placeholder="$t('course_screen.message.choose_a_file_or_drop_it_here')"
               @change="previewImage"
             ></b-form-file>
             <div class="mt-3" v-if="picture">
               <b-img id="imgCourse" :src="picture"></b-img>
             </div>
           </div>
         </b-form-group>
           <div class="form-group button">
             <b-button  type="button" class="btn btn-success">
               {{ $t("user_screen.button.submit") }}
             </b-button>
           </div>
       </form>
      </validation-observer>
    </div>
  </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
import Firebase from 'firebase';
require("@/sass/modules/add-user.css");

export default {
  data() {
    return {
      value: [],
      options: [
        {name: "Course1", id: 1},
        {name: "Course2", id: 2},
        {name: "Course3", id: 3},
        {name: "Course4", id: 4},
        {name: "Course5", id: 5},
        {name: "Course6", id: 6},
      ],
      name: "",
      birthday: "",
      phone: "",
      address: "",
      email: "",
      password: "",
      courseId: [],
      img_path: "",
      imageData: null,
      picture: null,
      uploadValue: 0,
    };
  },
  methods: {
    async submit() {
      for (var i = 0; i < this.value.length; i++) {
        this.courseId[i] = this.value[i].id;
      }

      await this.$store.dispatch("user/ADD_USER", {
        params: {
          name: this.name,
          birthday: this.birthday,
          phone: this.phone,
          address: this.address,
          email: this.email,
          password: this.password,
          course: this.courseId,
          img_path: this.img_path,
        },
      });
    },
    previewImage(event) {
      this.uploadValue = 0;
      this.picture = null;
      this.imageData = event.target.files[0];
      this.picture = null;
      const storageRef = Firebase.storage().ref();
      const imgRef = storageRef.child(`imagesUser/${this.imageData.name}`)
      imgRef.put(this.imageData).then(() => {
        imgRef.getDownloadURL().then(url => {
          this.picture = url;
        })
      })
    }
  },
};
</script>
