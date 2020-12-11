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
        <td v-if="getUser != null && getUser.role === getAdminRole"
            class="px-6 py-4 whitespace-no-wrap text-base leading-5 font-medium text-gray-500 text-center">
            <button class="px-6 py-4 text-indigo-600" @click="$emit('edit-user', user.id)">Edit</button>
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

    &:focus {
        outline : none;
    }

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
