<template>
    <tr>
        <td class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            {{ user.name }}
        </td>
        <td class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            <div class="text-base leading-5 text-gray-500">{{ user.email == null ? "No email" : user.email }}</div>
            <div class="text-base leading-5 text-gray-500">{{ user.phone == null ? "" : user.phone }}</div>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            {{ user.created_at | correctDateView }}
        </td>
        <td class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500 text-center">
            {{ user.role }}
        </td>

        <td v-if="getUser != null && getUser.role === getAdminRole">
            <div class="hover-shadow-effect">
                 <button class="px-6 py-4 text-indigo-600" data-toggle="modal" data-target="#exampleModalCenter" @click="$emit('edit-user', user.id)">Edit</button>
            </div>

        </td>
    </tr>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "UserListItem",
    computed: {
        ...mapGetters(["getUser", "getAdminRole"])
    },
    props: {
        user: {
            required: true,
            type: Object
        }
    },
    filters: {
        correctDateView(value) {
            if (!value) {
                return '';
            }
            let date = value.split("T").join(" ");
            return date.split(".")[0];
        }
    }
}
</script>

<style scoped="scoped" lang="scss">

@import "./resources/sass/hover_effects";

td {
    padding : 5px 10px;
}

button {
    &:focus {
        outline : none;
    }
}

</style>
