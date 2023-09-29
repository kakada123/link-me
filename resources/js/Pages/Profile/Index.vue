<script setup>
import { ref, computed, watch } from "vue";
import { useForm, Head, router } from "@inertiajs/vue3";
import Button from "primevue/button";
import Layout from "@/Pages/Layout.vue";
import LinkItem from "@/Pages/Profile/LinkItem.vue";
import { baseUrl, getIconForType } from "@/utils/helper.js";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();
const props = defineProps(["account"]);
const loading = ref(false);

const linkItems = computed(() => {
    const linkDetails = props.account.link?.link_details;
    if (!linkDetails) return [];

    return linkDetails.map((linkDetail) => ({
        id: linkDetail.id,
        icon: getIconForType(linkDetail.type),
        link: linkDetail.link,
        type: linkDetail.type,
        link_id: linkDetail.link_id,
    }));
});

const getImageUrl = (image) => {
    if (image && image.startsWith("https://lh3.googleusercontent.com")) {
        return image;
    }
    return baseUrl(
        `storage/${image ? image : "images/covers/icon-no-image.png"}`
    );
};

const coverImage = computed(() => getImageUrl(props.account.cover));
const profileImage = computed(() => getImageUrl(props.account.profile_picture));

const form = useForm({ profile: null, cover: null });
const profilePictureInputRef = ref(null);
const coverInputRef = ref(null);

function updateImage(type, file) {
    if (!file.type.startsWith("image/") || file.size > 2 * 1024 * 1024) {
        showError("Invalid file type or file is too large.");
        return;
    }

    loading.value = true;
    form[type] = file;
    form.post(`/profile/upload-${type}`, {
        onError: (errors) => {
            showError(errors[0]);
            loading.value = false;
        },
        onSuccess: () => {
            loading.value = false;
        },
    });
}

function showError(message) {
    toast.add({
        severity: "error",
        summary: "Error Message",
        detail: message,
        life: 3000,
    });
}

function browse(type) {
    if (type === "cover" && coverInputRef.value) {
        coverInputRef.value.click();
    } else if (type === "profile" && profilePictureInputRef.value) {
        profilePictureInputRef.value.click();
    }
}

function logout() {
    router.post("/logout");
}

function share() {}

const name = ref(props.account.name);
const bio = ref(props.account.bio);

function updateAccountDetails() {
    // ... update the name and bio in the database
    // for example, using axios
    axios
        .post("/profile/update", { name: name.value, bio: bio.value })
        .then((response) => {
            // handle success
            console.log(response);
        })
        .catch((error) => {
            // handle error
            console.log(error);
        });
}

watch(name, updateAccountDetails);
watch(bio, updateAccountDetails);
</script>

<template>
    <Toast />
    <Head>
        <title>{{ account?.name }}</title>
        <meta
            name="description"
            content="Getting to know me by social media link."
        />
    </Head>

    <Layout>
        <div class="profile-page block">
            <div class="cover-photo">
                <input
                    class="hidden"
                    type="file"
                    ref="coverInputRef"
                    @change="updateImage('cover', $event.target.files[0])"
                />
                <div class="relative cover-wrap">
                    <img :src="coverImage" class="w-full" />
                    <Button
                        icon="pi pi-camera"
                        rounded
                        aria-label="Filter"
                        class="camera-button"
                        label="Edit cover"
                        @click="browse('cover')"
                        :disabled="loading"
                    />

                    <Button
                        icon="pi pi-sign-out"
                        rounded
                        aria-label="Filter"
                        class="logout-button"
                        @click="logout()"
                        :disabled="loading"
                    />

                    <Button
                        icon="pi pi-share-alt"
                        rounded
                        aria-label="Filter"
                        class="share-button"
                        @click="share()"
                        :disabled="loading"
                    />
                </div>
            </div>
            <div class="profile-content">
                <div class="profile-picture block -mt-7">
                    <input
                        class="hidden"
                        type="file"
                        ref="profilePictureInputRef"
                        @change="updateImage('profile', $event.target.files[0])"
                    />
                    <div
                        class="relative profile-wrap flex flex-wrap align-items-center justify-content-center m-auto"
                    >
                        <img :src="profileImage" class="avatar" />
                        <Button
                            icon="pi pi-camera"
                            rounded
                            aria-label="Filter"
                            class="camera-button"
                            @click="browse('profile')"
                            :disabled="loading"
                        />
                    </div>
                </div>

                <!-- <div class="account-info">
                    <input v-model="name" class="text-center m-2 text-sm" />
                    <textarea
                        v-model="bio"
                        class="text-center text-xs mx-5 m-auto"
                    />
                </div> -->

                <div class="account-info">
                    <h6 class="text-center m-2 text-sm">{{ account.name }}</h6>
                    <p class="text-center text-xs mx-5 m-auto">
                        {{ account.bio }}
                    </p>
                </div>
                <div v-if="linkItems.length">
                    <div
                        v-for="(item, index) in linkItems"
                        :key="index"
                        class="my-3"
                    >
                        <link-item
                            :link_id="item.link_id"
                            :id="item.id"
                            :icon="item.icon"
                            :link="item.link"
                            :type="item.type"
                            :isLast="index === linkItems.length - 1"
                        ></link-item>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
