<template>
    <div class="flex flex-col">
        <div class="my-1 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-1 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="table-container">
                    <table class="min-w-full divide-y divide-gray-200 text-xl">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th v-if="getUser != null && getUser.role === getAdminRole && getAdminId == null"
                                class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Emulate
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Contact info
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Date of registration
                            </th>
                            <th v-if="userList.length !== 0 && userList[0].role === getStudentRole"
                                class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Year
                            </th>
                            <th v-if="userList.length !== 0 && userList[0].role === getStudentRole"
                                class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Discipline
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-base leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th v-if="getUser != null && getUser.role === getAdminRole" class="px-6 py-3 bg-gray-50"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <UserListItem v-for="user in userList" :key="user.id" :user="user" @edit-user="editUserData"/>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import UserListItem from "./UserListItem";

export default {
    name: "UserTable",
    components: {
        UserListItem
    },
    props: {
        userList: {
            type: Array,
            required: true
        }
    },
    computed: {
        ...mapGetters(["getUser", "getAdminRole", "getTeacherRole", "getStudentRole", "getAdminId"])
    },
    methods: {
        ...mapActions(["saveErrors", "confirm"]),
        editUserData(userEditedId) {
            this.$emit('edit-user', userEditedId);
        }
    }
}
</script>

<style scoped="scoped" lang="scss">
$margin : 10px;

table {
    text-align      : center;
    margin-top      : $margin;
    margin-bottom   : $margin;
    border-radius   : 10px;
    overflow        : hidden;
    border-collapse : collapse;
}
</style>
