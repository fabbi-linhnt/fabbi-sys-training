<template>
  <div>
    <div class="node">
      <ul class="list-group">
        <span class="action">
          <li v-if="!input" class="list-group-item" id="parent">
            <div @dblclick="updateNameCategory()">
              <span @click="openCloseChildren()" v-if="node.children.length > 0">
                {{ children ? "-" : "+" }}
              </span>
              {{ node.name }}
              <b-icon
                icon="trash"
                variant="danger"
                font-scale="1.5"
                @click="deleteCategory(node.id)"
                class="deleteCategory"
              >
              </b-icon>
              <b-icon
                icon="plus-circle"
                variant="primary"
                font-scale="1.5"
                @click="addCategory(node.parent_id)"
                class="addCategory"
              >
              </b-icon>
            </div>
          </li>
          <li v-else class="list-group-item" id="parent">
            <b-input-group class="mt-3">
              <b-form-input v-model="node.name"></b-form-input>
              <b-input-group-append>
                <b-button
                  variant="info"
                  @click="update(node.name, node.id, node.parent_id)"
                >
                  {{ $t("categories.button.button_update") }}
                </b-button>
              </b-input-group-append>
            </b-input-group>
          </li>
        </span>
      </ul>
    </div>
    <template v-if="children">
      <tree-brower
        id="children"
        v-for="child in node.children"
        :key="child.id"
        :node="child"
        @reload="$emit('reload')"
      />
    </template>
  </div>
</template>

<script>
import swal from "sweetalert";
import notification from "@/js/sweetAlert.js";
require("@/sass/modules/tree-brower.css");

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
      input: false,
      category: {
        name: "",
        parent_id: "",
      },
    };
  },
  methods: {
    openCloseChildren() {
      this.children = !this.children;
    },
    updateNameCategory() {
      this.input = !this.input;
    },
    async deleteCategory(id) {
      swal(
        notification.notification(
          this.$i18n.t("categories.label.delete_confirm"),
          this.$i18n.t("categories.label.warning")
        )
      ).then(async (willDelete) => {
        if (willDelete) {
          swal(this.$i18n.t("categories.label.delete_success"), {
            icon: "success",
          });
          await this.$store.dispatch("category/DESTROY_CATEGORY", id);
          this.$emit("reload");
        } else {
          return;
        }
      });
    },
    async addCategory(id) {
      this.category.name = "new category";
      this.category.parent_id = id;
      await this.$store.dispatch("category/STORE_CATEGORY", this.category);
      this.$emit("reload");
    },
    async update(name, id, parent_id) {
      this.input = false;
      this.category.name = name;
      this.category.parent_id = parent_id;
      await this.$store.dispatch("category/UPDATE_CATEGORY", {
        data: this.category,
        id: id,
      });
      this.$emit("reload");
    },
  },
};
</script>
