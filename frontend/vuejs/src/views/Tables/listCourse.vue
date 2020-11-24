<template>
    <div class="card shadow"
         :class="type === 'dark' ? 'bg-default': ''">
        <div class="card-header border-0"
             :class="type === 'dark' ? 'bg-transparent': ''">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0" :class="type === 'dark' ? 'text-white': ''">
                        {{ $t("course_screen.title.list_course") }}
                    </h3>
                </div>
                <div class="col text-right">
                    <div class="paginate">
                        <label class="typo__label">{{ $t("course_screen.label.record") }}</label>
                        <multiselect
                            v-model="paginate.perPage"
                            :options="options"
                            :searchable="false"
                            :close-on-select="false"
                            :show-labels="false"
                            @select="customPaginate()">
                        </multiselect>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <b-row>
                <b-col>
                    <b-button :to="{name: 'AddCourse'}" class="my-1" size="sm" variant="primary">
                        {{ $t("course_screen.button.add_course") }}
                    </b-button>
                </b-col>
                <b-col lg="5" class="my-1" style="float: right">
                    <b-form-group
                        label=""
                        label-cols-sm="3"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="filterInput"
                        class="mb-2"
                    >
                        <b-input-group size="sm">
                            <b-form-input
                                v-model="paginate.name"
                                type="search"
                                id="filterInput"
                                :placeholder="$t('course_screen.message.search_by_name')"
                            ></b-form-input>
                            <b-input-group-append>
                                <b-button variant="primary" @click="getData()">
                                    {{
                                        $t("course_screen.button.search")
                                    }}
                                </b-button>
                            </b-input-group-append>
                        </b-input-group>
                    </b-form-group>
                </b-col>
            </b-row>
        </div>
        <div>
            <b-table striped hover :items="data" :fields="fields">
                <template v-slot:cell(is_active)="row">
                    <p>
                        {{row.item.is_active == 1 ? $t("course_screen.label.active") : $t("course_screen.label.inactive")}}
                    </p>
                </template>
                <template v-slot:cell(actions)="row">
                    <b-button class="btn btn-danger" @click="destroyCourse(row.item.id)">
                        {{ $t("course_screen.button.delete") }}
                    </b-button>
                    <b-button class="btn btn-success" :to="{ name: 'EditCourse', params: {id:row.item.id}}">
                        {{ $t("course_screen.button.update") }}
                    </b-button>
                    <b-button v-b-modal.modal-center @click="getData(row.item.id)">
                        {{ $t("course_screen.button.detail") }}
                    </b-button>
                </template>

            </b-table>
            <b-modal id="modal-center" centered title="BootstrapVue">
                <p class="my-4" v-if="course">{{ $t("course_screen.label.name") }}: {{ course.name }} </p>
            </b-modal>
        </div>
        <div class="pagination">
            <b-pagination
                v-model="paginate.page"
                :total-rows="paginate.total"
                :per-page="paginate.perPage"
                aria-controls="my-table"
                @change="changePage"
            ></b-pagination>
        </div>
    </div>
</template>
<script>
import {DEFAULT_OPTION, DEFAULT_PERPAGE} from "../../definition/constants";

export default {
    name: 'projects-table',

    data() {
        return {
            fields: [
                {
                    key: 'name', label: this.$i18n.t("course_screen.label.name")
                },
                {
                    key: 'description', label: this.$i18n.t("course_screen.label.description")
                },
                {
                    key: 'is_active', label: this.$i18n.t("course_screen.label.is_active"),
                },
                {
                    key: 'actions', label: this.$i18n.t("course_screen.label.actions"),
                }
            ],
            data: null,
            type: '',
            filter: '',
            options: DEFAULT_OPTION,
            paginate:
                {
                    perPage: DEFAULT_PERPAGE,
                    total: '0',
                    name: '',
                    Page: '1'
                },
            course: null
        }
    },
    created() {
        this.getData();
    },
    methods: {
        async getData() {
            if (this.paginate.name) {
                this.paginate.page = 1;
            }
            await this.$store.dispatch('course/getData', {params: this.paginate}).then(
                res => {
                    this.data = res.data;
                    this.paginate.perPage = res.per_page;
                    this.paginate.total = res.total;
                }
            )
        },
        changePage(page) {
            this.paginate.page = page;
            this.getData();
        },
        customPaginate() {
            this.getData();
        },
        async destroyCourse(id) {
            if (confirm(this.$i18n.t("course_screen.message.are_you_sure"))) {
                await this.$store.dispatch('course/destroyCourse', id).then(() => {
                    this.getData();
                })
            }
        }
    },
    computed: {
        rows() {
            return this.courseData.length
        }
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.paginate {
    float: right;
    margin-right: 50px;
    width: 80px;
}
</style>
