<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
  contact: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['refresh', 'edit']);
const showDropdown = ref(false);
const countryFlag = ref('');

onMounted(async () => {
  if (props.contact.country_code) {
    try {
      await fetchCountryFlag(props.contact.country_code);
    } catch (error) {
      console.error('Error fetching country flag:', error);
    }
  }
});

const fetchCountryFlag = async (countryCode) => {
  try {
    const response = await axios.get(`https://restcountries.com/v3.1/alpha/${countryCode}`);
    if (response.data && response.data.length > 0) {
      countryFlag.value = response.data[0].flags.svg;
    }
  } catch (error) {
    console.error('Error fetching country flag:', error);
  }
};

const deleteContact = async () => {
  const result = await Swal.fire({
    title: 'Supprimer le contact ?',
    text: 'Cette action est irréversible.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e3342f',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Oui, supprimer',
    cancelButtonText: 'Annuler',
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/contacts/${props.contact.id}`);

      // Afficher une notification de succès après la suppression
      await Swal.fire({
        title: 'Supprimé !',
        text: 'Le contact a bien été supprimé.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false,
      });

      // Émettre l'événement 'refresh' pour indiquer au parent qu'il doit actualiser la liste des contacts
      emit('refresh');
    } catch (error) {
      // Afficher une notification d'erreur en cas d'échec
      console.error('Erreur lors de la suppression :', error);
      Swal.fire({
        title: 'Erreur',
        text: 'Une erreur est survenue lors de la suppression.',
        icon: 'error',
      });
    }
  }
};

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value;
};

const handleEdit = () => {
  emit('edit', props.contact);
  showDropdown.value = false;
};
</script>

<template>
  <div class="bg-white rounded-lg shadow-md p-4 relative">
    <div class="flex items-start mb-3">
      <div class="mr-2 text-yellow-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
        </svg>
      </div>
      <div class="font-semibold text-lg flex-grow">{{ contact.name }}</div>
      <div class="flex flex-col items-center ml-2">
        <div class="w-6 h-4 mb-1" v-if="countryFlag">
          <img :src="countryFlag" alt="Country flag" class="w-full h-full object-cover rounded-sm" />
        </div>
        <div class="relative">
          <button @click="toggleDropdown" class="text-gray-400 hover:text-gray-600 p-1 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="1"></circle>
              <circle cx="19" cy="12" r="1"></circle>
              <circle cx="5" cy="12" r="1"></circle>
            </svg>
          </button>
          <div v-if="showDropdown" class="absolute right-0 top-full mt-1 bg-white rounded shadow-md min-w-[120px] z-10 overflow-hidden">
            <button @click="handleEdit" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 border-b border-gray-200">Modifier</button>
            <button @click="deleteContact" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Supprimer</button>
          </div>
        </div>
      </div>
    </div>

    <div class="text-indigo-600 text-sm mb-1">{{ contact.company }}</div>
    <div class="text-gray-600 text-sm mb-3">{{ contact.job_title }}</div>

    <div class="mt-4">
      <div class="flex items-center text-sm text-gray-600 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
        </svg>
        {{ contact.phone }}
      </div>

      <div class="flex items-center text-sm text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
          <polyline points="22,6 12,13 2,6" />
        </svg>
        {{ contact.email }}
      </div>
    </div>
  </div>
</template>
