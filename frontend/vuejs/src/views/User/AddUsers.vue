<template>
  <div class="add_users">
    <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
    </base-header>
    <header>
      <h1>{{ $t("user_screen.title.user_screen") }}</h1>
    </header>
    <div class="content">
      <div class="container">
        <div class="form-group">
          <label for="name">{{ $t("user_screen.label.name") }}</label
          ><br />
          <input v-model="name" type="text" class="form-control" id="name" />
        </div>
        <div class="form-group">
          <label for="img">{{ $t("user_screen.label.upload_image") }}</label
          ><br />
          <input type="file" id="img" name="img" accept="images/*" />
        </div>
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
        <div class="form-group">
          <label for="phone">{{ $t("user_screen.label.phone_number") }}</label
          ><br />
          <input
            v-model="phone"
            type="tel"
            class="form-control"
            id="phone"
            name="phone"
            pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}"
            placeholder="123-45-678"
          />
        </div>
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
        <div class="form-group">
          <label for="email">{{ $t("user_screen.label.email") }}</label
          ><br />
          <input v-model="email" type="email" class="form-control" id="email" />
        </div>

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
        <div class="form-group">
          <label for="course">{{ $t("user_screen.label.course") }}</label
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
        <br /><br />
        <div class="form-group button">
          <b-button @click="submit" type="button" class="btn btn-success">
            {{ $t("user_screen.button.submit") }}
          </b-button>
        </div>
      </div>
    </div>
  </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
require("@/sass/modules/add-user.css");

export default {
  data() {
    return {
      value: [],
      options: [
        { name: "Course1", id: 1 },
        { name: "Course2", id: 2 },
        { name: "Course3", id: 3 },
        { name: "Course4", id: 4 },
        { name: "Course5", id: 5 },
        { name: "Course6", id: 6 },
      ],
      name: "",
      birthday: "",
      phone: "",
      address: "",
      email: "",
      password: "",
      courseId: [],
      img_path: "",
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
  },
};
</script>
