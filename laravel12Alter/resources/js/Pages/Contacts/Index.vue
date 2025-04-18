<script setup>
import { Head } from '@inertiajs/vue3';
import Contact from '@/Components/Contact.vue';
const props = defineProps({
  contacts: Array
});
</script>

<template>
  <div>
    <Head title="Contacts" />
    
    <Contact :initial-contacts="contacts" />
  </div>
</template>