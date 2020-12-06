<template>
    <tr>
        <td class="px-6 py-4 whitespace-no-wrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <img class="h-10 w-10 rounded-full" alt=""
                         src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=60"/>
                </div>
                <div class="ml-4">
                    <div class="text-base leading-5 font-medium text-gray-900">{{ user.name }}</div>
                    <!--                    <div class="text-sm leading-5 text-gray-500">{{ user.email }}</div>-->
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap">
            <div class="text-base leading-5 text-gray-500">{{ user.email == null ? "No email" : user.email }}</div>
            <div class="text-base leading-5 text-gray-500">{{ user.phone == null ? "" : user.phone }}</div>
        </td>
        <td class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500">
            {{ user.created_at | correctDateView }}
        </td>
        <td class="px-6 py-4 whitespace-no-wrap text-base leading-5 text-gray-500">
            {{ user.role }}
        </td>
        <td v-if="getUser != null && getUser.role === 'admin'"
            class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
        </td>
    </tr>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "UserListItem",
    computed: {
        ...mapGetters(["getUser"])
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

<style scoped>

</style>
