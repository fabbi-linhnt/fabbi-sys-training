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
                  <router-link
                    id="add"
                    class="btn btn-success"
                    :to="{ name: 'listCategories' }"
                    >{{ $t("categories.title.add_new_category") }}</router-link
                  >
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">
                        {{ $t("categories.label.name_category") }}
                        <span id = "action-category">{{
                          $t("categories.label.action_category")
                        }}</span>
                      </th>
                    </tr>
                  </thead>
                  <tr v-for="category in categoryData" :key="category.id">
                    <tree-brower :node="category"> </tree-brower>
                  </tr>
                </table>
              </div>
            </div>

            <div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ProjectsTable from "@/components/HeaderCard";
import TreeBrower from "../../components/TreeBrower.vue";
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
      this.$store.dispatch("category/getData").then((res) => {
        this.categoryData = res.data;
      });
    },
  },
};
</script>

<style>
#action-category {
  float : right;
}
</style>

