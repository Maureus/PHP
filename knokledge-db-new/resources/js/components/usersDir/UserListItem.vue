<template>
    <tr>
        <td class="px-3 py-2 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            <div v-if="getUser != null && (getUser.role === getAdminRole || getUser.role === getTeacherRole)"
                 class="hover-shadow-effect" style="padding-top: 0.7rem; padding-bottom: 0.7rem">
                <router-link class="whitespace-no-wrap text-right text-base leading-5 font-medium"
                             title="Click to open user's details"
                             :to="{name: 'UserContent', params: {user_id: user.id} }">{{ user.name }}
                </router-link>
            </div>
            <div v-else>{{ user.name }}</div>
        </td>
        <td v-if="getUser != null && getUser.role === getAdminRole && getAdminId==null"
            class="px-3 py-2 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            <div class="hover-shadow-effect" style="padding-top: 0.7rem; padding-bottom: 0.7rem">
                <button @click="emulate" class="whitespace-no-wrap text-right text-base leading-5 font-medium"
                        title="Click to start emulation">Emulate {{ user.name }}
                </button>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            <div class="text-base leading-5 text-gray-500">{{ user.email }}</div>
            <div class="text-base leading-5 text-gray-500">{{ user.phone == null ? "" : user.phone }}</div>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            {{ user.created_at | correctDateView }}
        </td>
        <td v-if="user.role === getStudentRole"
            class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            {{ user.year }}
        </td>
        <td v-if="user.role === getStudentRole || user.role === getTeacherRole"
            class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            {{ user.obor == null ? "Not chosen" : user.obor }}
        </td>
        <td class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            {{ user.role }}
        </td>

        <td v-if="getUser != null && getUser.role === getAdminRole">
            <div class="hover-shadow-effect">
                <button class="px-3 py-3 whitespace-no-wrap text-right text-base leading-5 font-medium"
                        data-toggle="modal" data-target="#userEditModal" title="Click to start editing user"
                        @click="$emit('edit-user', user.id)">Edit
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';

export default {
    name: "UserListItem",
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole", "getAdminId", "getStudentRole"])
    },
    props: {
        user: {
            required: true,
            type: Object
        }
    },
    filters: {
        correctDateView(value) {
            if (!value) return '';
            let date = value.split("T").join(" ");
            return date.split(".")[0];
        }
    },
    methods: {
        ...mapActions(["getLoggedInUser", "emulateUser"]),
        async emulate() {
            await this.emulateUser(this.user.id);
        }
    }
}
</script>

<style scoped="scoped" lang="scss">

@import "./resources/sass/hover_effects";

td {
    padding : 5px 10px;
}

</style>
