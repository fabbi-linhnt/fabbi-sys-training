<template>
  <div>
    <div
      class="node"
      id="parent"
      draggable="true"
      @dragstart="
        dragStart(node.id, node.parent_id, node.name, node.level, $event)
      "
      @dragover="allowDrop($event)"
      @drop="dragFinish(node.id, node.parent_id, node.level, $event)"
    >
      <div>
        <span v-if="!input" class="list-group-item">
          <div @dblclick="updateNameCategory()">
            <span @click="openCloseChildren()" v-if="node.children.length > 0">
              {{ children ? "-" : "+" }}
            </span>
            {{ node.name }}
            <b-icon
              v-b-tooltip.hover.top="$t('categories.label.delete')"
              icon="trash"
              variant="danger"
              font-scale="1.5"
              @click="deleteCategory(node.id)"
              class="deleteCategory"
            >
            </b-icon>
            <b-icon
              v-b-tooltip.hover.top="$t('categories.label.add')"
              icon="plus-circle"
              variant="primary"
              font-scale="1.5"
              @click="addCategory(node.parent_id)"
              class="addCategory"
            >
            </b-icon>
          </div>
        </span>
        <span v-else class="list-group-item">
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
        </span>
      </div>
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
import { DEFAULT_RAGE_CATEGORY } from "../definition/constants";
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
      original_positionX: "",
    };
  },
  methods: {
    openCloseChildren() {
      this.children = !this.children;
    },
    updateNameCategory() {
      this.input = !this.input;
    },
    dragStart(id, parent_id, name, level, ev) {
      ev.dataTransfer.setData("chidren_id", id);
      ev.dataTransfer.setData("chidren_name", name);
      if (parent_id == 0) {
        let positionX = document.getElementById("parent");
        let rangeMouse_Card =
          ev.clientX - Number(DEFAULT_RAGE_CATEGORY) - positionX.offsetLeft;
        ev.dataTransfer.setData("rangeMouse_Card", rangeMouse_Card);
      } else {
        let positionX_parent = document.getElementById("parent");
        let positionX = document.getElementById("children");
        let rangeMouse_Card =
          ev.clientX -
          Number(DEFAULT_RAGE_CATEGORY) -
          positionX_parent.offsetLeft -
          positionX.offsetLeft * level;
        ev.dataTransfer.setData("rangeMouse_Card", rangeMouse_Card);
      }
    },
    allowDrop(ev) {
      ev.preventDefault();
    },
    async dragFinish(id, parent_id, level, ev) {
      ev.preventDefault();
      let children_id = ev.dataTransfer.getData("chidren_id");
      let children_name = ev.dataTransfer.getData("chidren_name");
      let rangeMouse_Card = ev.dataTransfer.getData("rangeMouse_Card");
      if (parent_id == 0) {
        let positionX = document.getElementById("parent");
        this.original_positionX =
          Number(DEFAULT_RAGE_CATEGORY) + positionX.offsetLeft;
      } else {
        let positionX_parent = document.getElementById("parent");
        let positionX = document.getElementById("children");
        this.original_positionX =
          Number(DEFAULT_RAGE_CATEGORY) +
          positionX_parent.offsetLeft +
          level * positionX.offsetLeft;
      }
      if (id != children_id) {
        if (ev.clientX - rangeMouse_Card > this.original_positionX) {
          this.category.name = children_name;
          this.category.parent_id = id;
          await this.$store.dispatch("category/UPDATE_CATEGORY", {
            data: this.category,
            id: children_id,
          });
          this.$emit("reload");
        }
      }
      if (ev.clientX - rangeMouse_Card <= this.original_positionX) {
        this.category.name = children_name;
        this.category.parent_id = parent_id;
        await this.$store.dispatch("category/UPDATE_CATEGORY", {
          data: this.category,
          id: children_id,
        });
        this.$emit("reload");
      }
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
