<script setup>
import { Link, Head } from "@inertiajs/vue3";
import Layout from "../Layout.vue";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Button from "primevue/button";
import { ref } from "vue";

const username = ref(null);
const email = ref(null);
const password = ref(null);
const confirmPassword = ref(null);
</script>

<template>
    <Layout>
        <Head>
            <title>Sign Up</title>
            <meta
                name="description"
                content="Getting to know me by social media link."
            />
        </Head>
        <div class="signin-form relative">
            <h2 class="m-0">Sign Up</h2>
            <div class="mx-3 mt-7">
                <InputText
                    v-model="username"
                    placeholder="Username"
                    class="w-full mb-3"
                />

                <InputText
                    v-model="email"
                    placeholder="Email"
                    class="w-full mb-3"
                />

                <Password
                    v-model="password"
                    placeholder="Password"
                    class="w-full mb-3"
                    :feedback="false"
                />
                <Password
                    v-model="confirmPassword"
                    placeholder="Confirm Password"
                    class="w-full"
                    :feedback="false"
                />
                <p class="text-right">Forgot password?</p>

                <div class="mx-3 flex align-content-center flex-wrap">
                    <Button label="Sign Up" rounded class="w-full" />
                </div>
            </div>
            <div class="absolute footer">
                <span>Already have an account?</span>
                <Link href="/" class="ml-2">Sign In</Link>
            </div>
        </div>
    </Layout>
</template>
