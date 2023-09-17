<script setup>
import { Link, Head } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import { ref, computed } from "vue";
import Avatar from "primevue/avatar";
import Layout from "@/Pages/Layout.vue";
import LinkItem from "@/Pages/LinkItem.vue";
import { baseUrl } from "@/utils/helper.js";

const props = defineProps(["account"]);

const linkItems = [
    {
        icon: "pi-facebook",
        link: "https://www.facebook.com/kakadangen",
    },
    {
        icon: "pi-youtube",
        link: "https://www.youtube.com/channel/UCs-b52HlUEKF16Bfwea3KMw",
    },
    {
        icon: "pi-instagram",
        link: "https://instagram.com/kakada____",
    },
    {
        icon: "pi-linkedin",
        link: "https://www.linkedin.com/in/kakada-ngen-963028159/",
    },
    {
        icon: "pi-twitter",
        link: "https://twitter.com/NgenKakada",
    },
    {
        icon: "pi-telegram",
        link: "https://t.me/kakadaNgen",
    },
];

const coverImage = computed(() => {
    return props.account.cover || baseUrl("images/covers/icon-no-image.png");
});

const profileImage = computed(() => {
    return (
        props.account.profile_picture ||
        baseUrl("images/covers/icon-no-image.png")
    );
});
</script>

<template>
    <Head>
        <title>{{ account.name }}</title>
        <meta
            name="description"
            content="Getting to know me by social media link."
        />
    </Head>

    <Layout>
        <div class="profile-page block">
            <!-- Cover Photo -->
            <div class="cover-photo">
                <img :src="coverImage" class="w-full" />
            </div>
            <div class="profile-content">
                <!-- Profile Picture -->
                <div class="profile-picture block -mt-7">
                    <Avatar
                        :image="profileImage"
                        shape="circle"
                        class="flex flex-wrap align-items-center justify-content-center m-auto avatar"
                    />
                </div>

                <!-- Account Info -->
                <div class="account-info">
                    <h6 class="text-center m-2 text-sm">{{ account.name }}</h6>
                    <p class="text-center text-xs mx-5 m-auto">
                        {{ account.bio }}
                    </p>
                </div>

                <!-- Link Items -->
                <div
                    v-for="(item, index) in linkItems"
                    :key="index"
                    class="my-3"
                >
                    <link-item :icon="item.icon" :link="item.link"></link-item>
                </div>
            </div>
        </div>
    </Layout>
</template>
