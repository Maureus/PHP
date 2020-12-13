<template>
    <div class="flex flex-row pt-4">
        <Preloader v-if="loading" class="absolute inset-0 flex items-center justify-center"/>
        <Confirm v-if="profileConfirm" :mess="mess"/>
        <Confirm v-else-if="passwordConfirm" :mess="mess"/>

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
                                <h3 v-if="getProfileErrors">{{ getProfileErrors }}</h3>
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                                    Your name
                                </label>
                                <input id="name" v-model="curUser.name" @change="checkName" name="name"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                                    Choose a new avatar
                                </label>
                                <div class="col-span-6 sm:col-span-4">
                                    <input id="avatar" type="file" ref="myFile" @change="selectAvatar" name="avatar"
                                           class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                                </div>
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="phone" class="block text-sm font-medium leading-5 text-gray-700">
                                    Phone number
                                </label>
                                <input id="phone" v-model="curUser.phone" name="phone"
                                       @keyup="checkPhone($event.target.value)"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="address"
                                       class="block text-sm font-medium leading-5 text-gray-700">Address</label>
                                <input id="address" v-model="curUser.address" name="address"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
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
                                <h3 v-if="getProfileErrors">{{ getProfileErrors }}</h3>
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="password"
                                       class="block text-sm font-medium leading-5 text-gray-700">Password</label>
                                <input id="password" type="password" v-model="password" name="password"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="repeat_password" class="block text-sm font-medium leading-5 text-gray-700">
                                    Repeat password</label>
                                <input id="repeat_password" type="password" name="repeatPassword"
                                       v-model="password_confirmation"
                                       class="mt-1 form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"/>
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
import {mapGetters, mapActions} from 'vuex';
import Preloader from "../Preloader";
import Confirm from "../Confirm";

export default {
    name: "UserProfile",
    components: {
        Preloader, Confirm
    },
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
            mess: ''
        }
    },
    methods: {
        ...mapActions(['getLoggedInUser', 'saveErrors', 'confirm', 'saveProfileErrors']),
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
            }).then(async () => {
                this.passwordConfirm = false;
                this.profileConfirm = true;
                this.editProfile = false;
                await this.getLoggedInUser();
                this.mess = "Profile has been changed!";
                this.confirmModal();
            }).catch(errors => {
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
                this.mess = "Password has been changed!";
                this.confirmModal();
            }).catch(errors => {
                this.saveProfileErrors(errors);
            })
        },
        selectAvatar() {
            this.curUser.avatar = this.$refs.myFile.files[0];
        },
        async setAvatarAndUser() {
            this.loading = true;
            if (this.getUser === undefined) {
                await this.getLoggedInUser();
            }
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
        checkPhone(value) {
            // console.log(value);
            const phoneRegex = new RegExp(/^\+?[0-9-() ]{1,15}$/);
            if (!value.match(phoneRegex)) {
                this.curUser.phone = value.substr(0, value.length - 1);
            }
        },
        checkName() {

        }
    },
    computed: {
        ...mapGetters(['getUser', 'getErrors', 'getShowModalConfirm', 'getProfileErrors']),
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
