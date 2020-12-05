<template>
    <div>
        <preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-if="users.length" class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Contact info
                                </th>
<!--                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">-->
<!--                                    Status-->
<!--                                </th>-->
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Date of registration
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Role
                                </th>
                                <th v-if="getUser != null && getUser.role === 'admin'"
                                    class="px-6 py-3 bg-gray-50"></th>
                            </tr>
                            </thead>
                            <user-list-item v-for="user in users" :key="user.id" :user="user"></user-list-item>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UserListItem from "./UserListItem";
import Preloader from "./Preloader";
import {mapGetters} from 'vuex';

export default {
    name: "UserList",
    components: {
        UserListItem, Preloader
    },
    data() {
        return {
            users: [],
            loading: true
        }
    },
    computed: {
        ...mapGetters(["getUser"])
    },
    async mounted() {
        await axios.get("http://127.0.0.1:8000/api/users").then(resp => resp.data).then(value => {
            this.users = value;
            this.loading = false;
        });
        console.log(this.users);
        console.log(this.getUser);
        // console.log(this.$store.state.admin); //undef
    }
}
</script>

<style scoped>

</style>
