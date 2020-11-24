<template>
  <div>
    <div class="node">
      <div>
        <span class="action">
          <td class="category-name" @click="change()">{{ node.name }}</td>
          <button
            v-if="node.name"
            class="btn btn-success"
            @click="changePage()"
          >
            {{ $t("categories.button.button_update") }}
          </button>
          <button
            v-if="node.name"
            id="delete-category"
            class="btn btn-primary"
            @click="deleteCategory(node.id)"
          >
            {{ $t("categories.button.button_delete") }}
          </button>
        </span>
      </div>
    </div>
    <template v-if="children">
      <tree-brower
        id="children"
        v-for="child in node.children"
        :key="child.id"
        :node="child"
      />
    </template>
  </div>
</template>
<script>
export default {
  name: "tree-brower",
  props: {
    type: {
      type: String,
    },
    title: String,
    node: Object,
  },
  data() {
    return {
      children: false,
    };
  },
  methods: {
    change() {
      this.children = !this.children;
    },
    changePage() {
      this.$router.push({ name: "Subjects" });
    },
    async deleteCategory(id) {
      if (confirm(this.$i18n.t("list_subjects.label.deleteConfirm")));
      await this.$store.dispatch("category/deleteData", id);
      window.location.reload();
    },
  },
};
</script>

<style>
.action button {
  float: right;
}
#delete-category {
  background-color: red;
}
#children {
  margin-left: 10%;
}
</style>