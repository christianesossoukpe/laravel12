<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import ConctactCard from './ConctactCard.vue';
import ContactForm from './ContactForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const contacts = ref([]);
const searchQuery = ref('');
const showForm = ref(false);

onMounted(async () => {
  await fetchContacts();
});

const fetchContacts = async () => {
  try {
    const response = await axios.get('/getscontacts');
    contacts.value = response.data;
  } catch (error) {
    console.error('Error fetching contacts:', error);
  }
};

const filteredContacts = computed(() => {
  if (!searchQuery.value) return contacts.value;

  return contacts.value.filter(contact => 
    contact.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    contact.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    contact.job_title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const addContact = async (contactData) => {
  try {
    await axios.post('/contacts', contactData);
    await fetchContacts();
    showForm.value = false;
  } catch (error) {
    console.error('Error adding contact:', error);
  }
};
</script>

<template>
    <AuthenticatedLayout>

  <div class="p-5 max-w-7xl mx-auto bg-slate-100">
    <div class="mb-5">
      <h1 class="text-2xl font-semibold">Contacts</h1>
    </div>
    
    <div class="bg-white rounded-lg p-5 shadow-md">
      <div class="flex justify-between items-center mb-5">
        <button 
          class="bg-[#06708E] text-white rounded-md py-2 px-4 cursor-pointer hover:bg-blue-600"
          @click="showForm = true"
        >
          Ajouter un contact
        </button>
        
        <div class="relative flex">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Search..." 
            class="py-2 px-3 border border-gray-300 rounded-md w-52"
          />
          <button class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </button>
        </div>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <ConctactCard 
          v-for="contact in filteredContacts" 
          :key="contact.id" 
          :contact="contact"
          @refresh="fetchContacts"
        />
      </div>
    </div>
    
    <ContactForm 
      v-if="showForm" 
      @close="showForm = false"
      @save="addContact"
    />
  </div>
</AuthenticatedLayout>
</template>

<style scoped>
 </style>
