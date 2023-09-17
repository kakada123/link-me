<script setup>
import Card from "primevue/card";
import { ref } from "vue";
import { Link, Head } from "@inertiajs/vue3";

const props = defineProps(["icon", "link"]);
const iconClass = ref("pi mr-2");
const icon = ref(props.icon);
const link = ref(props.link);
</script>

<template>
    <a :href="link" target="blank" class="no-underline">
        <Card
            class="mx-3 card-item flex align-items-center justify-content-left"
        >
            <template #content>
                <div class="flex align-items-center justify-content-left">
                    <i :class="[iconClass, icon]"></i>
                    <small>{{ link }}</small>
                </div>
            </template>
        </Card>
    </a>
</template>
