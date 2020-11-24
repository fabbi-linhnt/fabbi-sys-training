<template>
  <base-nav
    class="navbar-top navbar-dark"
    id="navbar-main"
    :show-toggle-button="false"
    expand
  >
    <form
      class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto"
    >
      <div class="form-group mb-0">
        <base-input
          placeholder="Search"
          class="input-group-alternative"
          alternative=""
          addon-right-icon="fas fa-search"
        >
        </base-input>
      </div>
    </form>
    <li class="lang" id="switch_language">
      <a @click="changeLanguage(true)" class="switch_language_vi active">VI</a>
      <a @click="changeLanguage(false)" class="switch_language_en">EN</a>
    </li>
    <ul class="navbar-nav align-items-center d-none d-md-flex">
      <li class="nav-item dropdown">
        <base-dropdown class="nav-link pr-0">
          <div class="media align-items-center _avatar" slot="title">
            <span class="avatar avatar-sm rounded-circle">
              <img
                v-if="!user.avatar"
                alt="Image placeholder"
                src="img/avatar-default.jpg"
              />
              <img v-else alt="Image placeholder" :src="user.avatar" />
            </span>
            <div class="media-body ml-2 d-none d-lg-block">
              <span class="mb-0 text-sm font-weight-bold"
                >Welcome! {{ user.name }}</span
              >
            </div>
          </div>

          <template>
            <div class="dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <router-link to="/profile" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </router-link>
            <router-link to="/profile" class="dropdown-item">
              <i class="ni ni-settings-gear-65"></i>
              <span>Settings</span>
            </router-link>
            <router-link to="/profile" class="dropdown-item">
              <i class="ni ni-calendar-grid-58"></i>
              <span>Activity</span>
            </router-link>
            <router-link to="/profile" class="dropdown-item">
              <i class="ni ni-support-16"></i>
              <span>Support</span>
            </router-link>
            <div class="dropdown-divider"></div>
            <a @click="onLogout()" class="dropdown-item"
              ><i class="ni ni-user-run"></i><span>Logout</span></a
            >
          </template>
        </base-dropdown>
      </li>
    </ul>
  </base-nav>
</template>
<script>
import { mapMutations, mapActions, mapGetters } from "vuex";

export default {
  data() {
    return {
      activeNotifications: false,
      showMenu: false,
      searchQuery: "",
      lang: true
    };
  },
  computed: {
    ...mapGetters({ user: "auth/auth" }),
  },
  methods: {
    ...mapActions("auth", ["logout"]),
    ...mapMutations(["setLoading"]),

    onLogout() {
      this.logout().then(() => {
        this.$router.push({ name: "login" });
      });
    },
    toggleSidebar() {
      this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
    },
    hideSidebar() {
      this.$sidebar.displaySidebar(false);
    },
    toggleMenu() {
      this.showMenu = !this.showMenu;
    },
    changeLanguage(val) {
      this.lang = val;
    }
  },
  watch: {
    lang(val) {
      if (val) {
        document.querySelector('#switch_language .switch_language_vi').classList.add('active');
        document.querySelector('#switch_language .switch_language_en').classList.remove('active');
      } else {
        document.querySelector('#switch_language .switch_language_vi').classList.remove('active');
        document.querySelector('#switch_language .switch_language_en').classList.add('active');
      }
    }
  },
};
</script>

<style lang="scss" scoped>
a.dropdown-item,
div._avatar {
  cursor: pointer;
}
li.lang {
  line-height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
li.lang a.active {
    color: #fff;
    background-color: #1290a7;
}
li.lang a {
    color: #fff;
    background-color: #e9ecef;
    display: inline-block;
    width: 36px;
    height: 28px;
    text-align: center;
}
li a {
    padding: 8px;
    text-decoration: none;
    display: inline-block;
    color: #fff;
    font-weight: normal;
    font-size: 15px;
    outline: none;
    cursor: pointer;
}
</style>
