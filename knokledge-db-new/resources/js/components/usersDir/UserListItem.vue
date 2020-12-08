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
        <td v-if="getUser != null && getUser.role === getAdminRole"
            class="px-6 py-4 whitespace-no-wrap text-right text-base leading-5 font-medium">
            <button class="px-6 py-4 text-indigo-600 hover:text-indigo-900" @click="$emit('edit-user', user.id)">
                Edit
            </button>
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
$hoverColor : #dde9f5;
$fontSize   : 18px;

button {
    font-size : $fontSize;

    &:hover {
        font-weight                : bold;
        background-color           : darken($color : $hoverColor, $amount : 10%);
        box-shadow                 : darken($color: $hoverColor, $amount: 5%) -1px 1px,
        darken($color: $hoverColor, $amount: 5%) -2px 2px,
        darken($color: $hoverColor, $amount: 5%) -3px 3px,
        darken($color: $hoverColor, $amount: 5%) -4px 4px,
        darken($color: $hoverColor, $amount: 5%) -5px 5px;
        transform                  : translate3d(5px, -5px, 0);

        transition-delay           : 0s;
        transition-duration        : 0.5s;
        transition-property        : all;
        transition-timing-function : linear;
    }
}
</style>
