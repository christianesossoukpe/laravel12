<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const emit = defineEmits(['close', 'save']);

const form = ref({
  name: '',
  company: 'Best_comp',
  job_title: '',
  phone: '',
  email: '',
  country_code: ''
});

const countries = ref([]);
const errors = ref({});
const isLoading = ref(false);

onMounted(async () => {
  await fetchCountries();
});

const fetchCountries = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get('https://restcountries.com/v3.1/all');
    countries.value = response.data.map(country => ({
      code: country.cca2,
      name: country.name.common,
      flag: country.flags.svg
    })).sort((a, b) => a.name.localeCompare(b.name));
    isLoading.value = false;
  } catch (error) {
    console.error('Error fetching countries:', error);
    isLoading.value = false;
  }
};

const saveContact = async () => {
  try {
    errors.value = {};
    isLoading.value = true;
    
    // Validation
    if (!form.value.name) errors.value.name = "Le nom est requis";
    if (!form.value.job_title) errors.value.job_title = "Le poste est requis";
    if (!form.value.phone) errors.value.phone = "Le téléphone est requis";
    if (!form.value.email) errors.value.email = "L'email est requis";
    if (!form.value.country_code) errors.value.country_code = "Le pays est requis";
    
    if (Object.keys(errors.value).length > 0) {
      isLoading.value = false;
      return;
    }
    
    emit('save', form.value);
    isLoading.value = false;
  } catch (error) {
    console.error('Error saving contact:', error);
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg max-w-sm w-full shadow-lg">
      <div class="flex justify-between items-center p-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold">Ajouter un contact</h2>
        <button @click="emit('close')" class="text-gray-600 p-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <div class="p-4">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
          <input 
            type="text" 
            id="name" 
            v-model="form.name" 
            :class="{ 'border-red-500': errors.name }"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <div v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</div>
        </div>
        
        <div class="mb-4">
          <label for="company" class="block text-sm font-medium text-gray-700">Entreprise</label>
          <input 
            type="text" 
            id="company" 
            v-model="form.company" 
            :class="{ 'border-red-500': errors.company }"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <div v-if="errors.company" class="text-red-500 text-sm mt-1">{{ errors.company }}</div>
        </div>
        
        <div class="mb-4">
          <label for="job_title" class="block text-sm font-medium text-gray-700">Poste</label>
          <input 
            type="text" 
            id="job_title" 
            v-model="form.job_title" 
            :class="{ 'border-red-500': errors.job_title }"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <div v-if="errors.job_title" class="text-red-500 text-sm mt-1">{{ errors.job_title }}</div>
        </div>
        
        <div class="mb-4">
          <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
          <input 
            type="text" 
            id="phone" 
            v-model="form.phone" 
            :class="{ 'border-red-500': errors.phone }"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <div v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone }}</div>
        </div>
        
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input 
            type="email" 
            id="email" 
            v-model="form.email" 
            :class="{ 'border-red-500': errors.email }"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <div v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</div>
        </div>
        
        <div class="mb-4">
          <label for="country" class="block text-sm font-medium text-gray-700">Pays</label>
          <select 
            id="country" 
            v-model="form.country_code"
            :class="{ 'border-red-500': errors.country_code }"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="">Sélectionnez un pays</option>
            <option 
              v-for="country in countries" 
              :key="country.code" 
              :value="country.code"
            >
              {{ country.name }}
            </option>
          </select>
          <div v-if="errors.country_code" class="text-red-500 text-sm mt-1">{{ errors.country_code }}</div>
        </div>
      </div>
      
      <div class="flex justify-end items-center p-4 border-t border-gray-200">
        <button @click="emit('close')" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-md mr-2">
          Annuler
        </button>
        <button @click="saveContact" :disabled="isLoading" class="px-4 py-2 bg-[#06708E] text-white rounded-md disabled:opacity-50">
          <span v-if="isLoading">Chargement...</span>
          <span v-else>Enregistrer</span>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Plus besoin de styles personnalisés avec Tailwind, tout est géré par les classes utilitaires */
</style>
