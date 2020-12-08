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
                  <h3 class="mb-0">
                    {{ $t("categories.title.list_category") }}
                  </h3>
                </div>
                <table class="table">
                  <tr v-for="category in categoryData" :key="category.id">
                    <tree-brower :node="category" @reload="reload()"></tree-brower>
                  </tr>
                </table>
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
import TreeBrower from "@/components/TreeBrower.vue";
require("@/sass/modules/category.css");

export default {
  components: {
    ProjectsTable,
    TreeBrower,
  },
  data() {
    return {
      categoryData: null,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    getData() {
      this.$store.dispatch("category/GET_CATEGORIES").then((res) => {
        this.categoryData = res.data;
      });
    },
    reload() {
      this.getData();
    }
  },
};
</script>
