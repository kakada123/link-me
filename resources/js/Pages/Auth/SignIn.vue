<script setup>
import { Link, Head, router, useForm } from "@inertiajs/vue3";
import Layout from "../Layout.vue";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Button from "primevue/button";
import { ref, onMounted } from "vue";
import { UniversalSocialauth, Facebook, Google } from "universal-social-auth";
import axios from "axios";
import { socialLogin } from "@/utils/helper.js";

const email = ref(null);
const password = ref(null);
const hash = ref("");
const fauth = ref(false);
const responseData = ref({
    code: null,
});
const data = ref({
    tok: null,
});
const errorMessage = ref(""); // NEW

const options = {
    providers: { ...socialLogin },
};
const Oauth = new UniversalSocialauth(axios, options);

const useAuthProvider = async (provider, proData) => {
    try {
        const pro = proData;
        const ProData = pro || Providers[provider];

        const response = await Oauth.authenticate(provider, ProData);
        if (response && response.code) {
            responseData.value.code = response.code;
            responseData.value.provider = provider;

            await useSocialLogin();
        } else {
            console.error("Authentication failed:", response);
            errorMessage.value = "Authentication failed. Please try again."; // NEW
        }
    } catch (error) {
        console.error("Authentication error:", error);
        errorMessage.value = "Authentication error. Please try again."; // NEW
    }
};

const useLoginFirst = async (e) => {
    try {
        const isFirstLogin = await Oauth.firstlogin(e);

        if (isFirstLogin) {
            const welcomeMessage = "Welcome to My App";

            router.visit("/profile");
        }
    } catch (error) {
        console.error("Login error:", error);
        errorMessage.value = "Login error. Please try again."; // NEW
    }
};

const useSocialLogin = async () => {
    try {
        if (responseData.value.code) {
            const pdata = {
                code: responseData.value.code,
                otp: data.value.tok,
                hash: hash.value,
            };

            const response = await axios.post(
                "/social-login/" + responseData.value.provider,
                pdata
            );

            if (response.data.status === 444) {
                hash.value = response.data.hash;
                fauth.value = true;
            } else if (response.data.status === 445) {
                router.visit("/profile");
            } else {
                await useLoginFirst(response.data.u);
            }
        }
    } catch (error) {
        console.error("Social login error:", error);
        errorMessage.value = "Social login failed. Please try again."; // NEW
    }
};

const form = useForm({
    email: null,
    password: null,
});

const signInWithEmailPassword = () => {
    form.post("/login", {
        preserveState: true,
        onError: (errors) => {
            console.error("Login error:", errors);
            errorMessage.value =
                "Login failed. Please check your email and password."; // NEW
        },
        onSuccess: () => {
            router.visit("/profile");
        },
    });
};
</script>

<template>
    <Layout>
        <Head>
            <title>Sign In</title>
            <meta
                name="description"
                content="Getting to know me by social media link."
            />
        </Head>
        <div class="signin-form relative">
            <h2 class="m-0">Sign In</h2>
            <div class="mx-3 mt-7">
                <InputText
                    v-model="form.email"
                    placeholder="Email"
                    class="w-full mb-3"
                />

                <Password
                    v-model="form.password"
                    placeholder="Password"
                    class="w-full"
                    :feedback="false"
                />
                <p class="text-right">Forgot password?</p>

                <div class="mx-3 flex align-content-center flex-wrap">
                    <Button
                        label="Sign In"
                        rounded
                        class="w-full"
                        @click="signInWithEmailPassword"
                    />
                </div>
                <div class="flex mt-3">
                    <hr />
                    <span class="mx-3 secondary">Or</span>
                    <hr />
                </div>

                <div
                    class="flex mt-3 align-content-center flex-wrap justify-content-center"
                >
                    <Button
                        @click="useAuthProvider('facebook', Facebook)"
                        class="mx-2 facebook-button d-none"
                        icon="pi pi-facebook"
                        rounded
                        aria-label="Facebook"
                    />
                    <Button
                        @click="useAuthProvider('google', Google)"
                        class="mx-2 google-button"
                        icon="pi pi-google"
                        rounded
                        aria-label="Google"
                    />
                </div>
            </div>
            <div class="absolute footer">
                <span>Don't have an account?</span>
                <Link href="/signup" class="ml-2">Create new one</Link>
            </div>
        </div>
        <p class="text-danger">{{ errorMessage }}</p>
        <!-- NEW -->
    </Layout>
</template>
