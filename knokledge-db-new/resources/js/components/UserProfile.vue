<template>
    <div class="flex flex-row pt-4">
        <preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <div v-if="getShowModalConfirm"
             class="absolute inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
            <div class="flex-column items-center justify-center w-1/5 bg-white border-0 rounded">
                <p v-if="passwordConfirm" class="text-center pt-4 text-lg">Password has been changed!</p>
                <p v-if="profileConfirm" class="text-center pt-4 text-lg">Profile has been changed!</p>
                <div class="flex items-center pt-4 justify-end w-full pr-2">
                    <button @click="confirmModal"
                            class="flex items-center justify-center text-white bg-indigo-500 border-0 py-1 px-2
                            focus:outline-none hover:bg-indigo-600 rounded text-xs mb-2">
                        Confirm
                    </button>
                </div>
            </div>
        </div>

        <div class="h-full w-1/3 flex flex-col items-center justify-center">
            <div class="flex-shrink-0 h-56 w-56 mb-2">
                <img id="ava" class="h-56 w-56 rounded-full" src="" alt="">
            </div>
            <div class="flex flex-col items-center justify-center">
                <button @click="showEditProfile"
                        class="flex items-center justify-center text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg mb-2 w-full">
                    Edit my profile
                </button>
                <button @click="showChangePassword"
                        class="flex text-center text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg w-full">
                    Change my password
                </button>
            </div>
        </div>
        <div class="h-full w-2/3">
            <div v-if="editProfile">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 text-center text-3xl">
                                <h2>Edit your profile</h2>
                                <h3 v-if="getErrors">{{ getErrors }}</h3>
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="name" class="block text-sm font-medium leading-5 text-gray-700">Your
                                    name</label>
                                <input id="name"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                       v-model="curUser.name" @change="checkName" name="name">
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <input id="avatar"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                       type="file" ref="myFile" @change="selectAvatar" name="avatar">
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <label for="phone" class="block text-sm font-medium leading-5 text-gray-700">Phone
                                    number</label>
                                <input id="phone"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                       v-model="curUser.phone" name="phone">
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <label for="address"
                                       class="block text-sm font-medium leading-5 text-gray-700">Address</label>
                                <input id="address"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                       v-model="curUser.address" name="address">
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button :disabled="!curUser.name" @click.prevent="saveUserProfile"
                                class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 shadow-sm hover:bg-indigo-500 focus:outline-none focus:shadow-outline-blue active:bg-indigo-600 transition duration-150 ease-in-out">
                            Save
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="changePassword">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 text-center text-3xl">
                                <h2>Change your password</h2>
                                <h3 v-if="getErrors">{{ getErrors }}</h3>
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="password"
                                       class="block text-sm font-medium leading-5 text-gray-700">Password</label>
                                <input id="password"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                       v-model="password" name="password">
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <label for="repeat_password" class="block text-sm font-medium leading-5 text-gray-700">Repeat
                                    password</label>
                                <input id="repeat_password"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                       v-model="password_confirmation" name="repeatPassword">
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button @click.prevent="saveUserPassword"
                                class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 shadow-sm hover:bg-indigo-500 focus:outline-none focus:shadow-outline-blue active:bg-indigo-600 transition duration-150 ease-in-out">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions, mapState, mapMutations} from 'vuex';
import Preloader from "./Preloader";

export default {
    name: "UserProfile",
    components: {Preloader},
    data() {
        return {
            editProfile: false,
            changePassword: false,
            curUser: {
                name: '',
                email: '',
                avatar: '',
                phone: '',
                address: '',
                id: ''
            },
            password: '',
            password_confirmation: '',
            loading: true,
            profileConfirm: false,
            passwordConfirm: false,

        }
    },
    methods: {
        ...mapActions(['getLoggedInUser', 'saveErrors', 'confirm']),
        async showEditProfile() {
            if (this.getUser) {
                this.curUser.name = this.getUser['name'];
                this.curUser.email = this.getUser['email'];
                this.curUser.address = this.getUser['address'];
                this.curUser.phone = this.getUser['phone'];
            } else {
                this.curUser.name = this.curUser.email = this.curUser.avatar = this.curUser.phone = this.curUser.address = '';
            }

            if (this.changePassword === true) this.changePassword = false;
            this.editProfile === false ? this.editProfile = true : this.editProfile = false;
        },
        async showChangePassword() {
            this.password = this.password_confirmation = '';
            if (this.editProfile === true) this.editProfile = false;
            this.changePassword === false ? this.changePassword = true : this.changePassword = false;
        },
        async saveUserProfile() {

            let formData = new FormData();
            formData.append('name', this.curUser.name);
            formData.append('email', this.curUser.email);
            if (this.curUser.avatar) {
                formData.append('avatar', this.curUser.avatar, this.curUser.avatar.name);
            }
            formData.append('phone', this.curUser.phone);
            formData.append('address', this.curUser.address);
            formData.append('id', this.getUser['id']);
            // formData.append('_method', 'put');

            await axios.post('api/saveuser', formData, {
                headers: {'Content-Type': 'multipart/form-data'}
            })
                .then(async () => {
                    this.passwordConfirm = false;
                    this.profileConfirm = true;
                    this.editProfile = false;
                    await this.getLoggedInUser();
                    this.confirmModal();
                })
                .catch(errors => {
                    this.saveErrors(errors);
                })
        },
        async saveUserPassword() {
            let formData = new FormData();
            formData.append('password', this.password);
            formData.append('password_confirmation', this.password_confirmation);
            formData.append('id', this.getUser['id']);

            await axios.post('api/saveuser', formData, {
                headers: {'Content-Type': 'multipart/form-data'}
            }).then(() => {
                this.passwordConfirm = true;
                this.profileConfirm = false;
                this.changePassword = false;
                this.confirmModal();
            }).catch(errors => {
                this.saveErrors(errors);
            })
        },
        selectAvatar() {
            this.curUser.avatar = this.$refs.myFile.files[0];
        },
        async setAvatarAndUser() {
            this.loading = true;
            await this.getLoggedInUser();
            if (this.getUser['hasavatar'] === "1") {
                document.getElementById("ava").src = 'http://127.0.0.1:8000/api/image/avatar' + this.getUser['id'] + '.jpg';
            } else {
                document.getElementById("ava").src = 'http://127.0.0.1:8000/api/image/avatarP.jpg';
            }
            this.loading = false;
        },
        confirmModal() {
            this.confirm();
        },
        checkName() {

        }
    },
    computed: {
        ...mapGetters(['getUser', 'getErrors', 'getShowModalConfirm']),
    },
    created() {
        // this.fetchData();
    },
    async mounted() {
        await this.setAvatarAndUser();
    }
}
</script>

<style scoped>

</style>
