<template>
    <div class="absolute inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
        <div class="flex-column items-center justify-center w-2/5 bg-white border-0 rounded">
            <p class="text-center pt-4 text-lg">Edit profile</p>
            <form @submit.prevent="">
                <div class="col-span-6 sm:col-span-4">
                    <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                        User's name
                    </label>
                    <input id="name" v-model="curUser.name" name="name" required
                           class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <label for="avatar" class="block text-sm font-medium leading-5 text-gray-700">
                        Choose a new avatar
                    </label>
                    <input id="avatar" v-model="curUser.avatar" name="avatar" type="file"
                           class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <label for="phone" class="block text-sm font-medium leading-5 text-gray-700">
                        Phone number
                    </label>
                    <input id="phone" v-model="curUser.phone" name="phone"
                           class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <label for="address" class="block text-sm font-medium leading-5 text-gray-700">
                        Address
                    </label>
                    <input id="address" v-model="curUser.address" name="address"
                           class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                </div>
                <div class="flex items-center pt-4 justify-start w-full pr-2">
                    <button @click="$emit('edit-user-info', user.id)" type="submit"
                            class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2
                            focus:outline-none hover:bg-indigo-600 rounded text-xs mr-2 ml-2">
                        Confirm
                    </button>
                </div>
            </form>
            <div class="flex items-center pt-4 justify-end w-full pr-2">
                <button @click="$emit('cancel-editing-user-info', user.id)"
                        class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2
                            focus:outline-none hover:bg-indigo-600 rounded text-xs mr-2 ml-2">
                    Cancel
                </button>
                <button @click="$emit('delete-user', user.id)"
                        class="flex items-center justify-center text-white bg-red-500 border-0 py-1 px-2
                            focus:outline-none hover:bg-red-600 rounded text-xs m-2">
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "EditProfile",
    data() {
        return {
            curUser: {
                name: '',
                email: '',
                avatar: '',
                phone: '',
                address: '',
                id: ''
            },
        }
    },
    props: {
        user: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
