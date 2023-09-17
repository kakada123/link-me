<script setup>
import Card from "primevue/card";
import { ref } from "vue";
import { Link, Head, useForm } from "@inertiajs/vue3";
import Button from "primevue/button";

const props = defineProps(["id", "icon", "link", "type", "isLast", "link_id"]);
const iconClass = ref("pi mr-2");
const icon = ref(props.icon);
const link = ref(props.link);
const loading = ref(false);

function determineType(url) {
    if (!url) return null;

    const types = [
        { name: "facebook", domain: "facebook.com" },
        { name: "linkedin", domain: "linkedin.com" },
        { name: "pinterest", domain: "pinterest.com" },
        { name: "instagram", domain: "instagram.com" },
        { name: "youtube", domain: "youtube.com" },
    ];

    for (let i = 0; i < types.length; i++) {
        if (url.includes(types[i].domain)) {
            return types[i].name;
        }
    }

    return null;
}

const form = useForm({
    link: props.link,
    type: determineType(props.link),
});

function updateLink() {
    const newType = determineType(form.link);
    form.type = newType;
    icon.value = newType ? `pi-${newType}` : props.icon;

    loading.value = true;
    form.put(`/links/${props.id}`, {
        preserveState: true,
        onError: (errors) => {
            loading.value = false;
        },
        onSuccess: () => {
            loading.value = false;
        },
    });
}

const formNewLink = useForm({
    link_id: props.link_id,
});

function createNewLink() {
    formNewLink.link = form.link;
    formNewLink.type = determineType(form.link);

    loading.value = true;
    formNewLink.post("/link-details/create", {
        onError: (errors) => {
            loading.value = false;
        },
        onSuccess: (response) => {
            loading.value = false;
            formNewLink.reset();
        },
    });
}
</script>

<template>
    <Card
        class="mx-3 card-item flex align-items-center justify-content-between"
    >
        <template #content>
            <div
                class="relative flex align-items-center justify-content-between w-full"
            >
                <div
                    class="flex align-items-center justify-content-left w-full"
                >
                    <i :class="[iconClass, icon]"></i>
                    <input
                        v-model="form.link"
                        @change="updateLink"
                        class="p-1 rounded w-full link-input"
                    />
                </div>
                <div
                    v-if="props.isLast"
                    class="absolute add-button-div flex justify-center items-center"
                >
                    <Button
                        icon="pi pi-plus"
                        rounded
                        aria-label="Filter"
                        class="add-button"
                        @click="createNewLink"
                        :disabled="loading"
                    />
                </div>
            </div>
        </template>
    </Card>
</template>
