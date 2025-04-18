<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  contact: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['refresh']);

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
  if (confirm('Êtes-vous sûr de vouloir supprimer ce contact ?')) {
    try {
      await axios.delete(`/contacts/${props.contact.id}`);
      emit('refresh');
    } catch (error) {
      console.error('Error deleting contact:', error);
    }
  }
};
</script>

<template>
  <div class="contact-card">
    <div class="card-header">
      <div class="star-container">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
        </svg>
      </div>
      <div class="name">{{ contact.name }}</div>
      <div class="flag" v-if="countryFlag">
        <img :src="countryFlag" alt="Country flag" />
      </div>
    </div>
    
    <div class="card-body">
      <div class="company">{{ contact.company }}</div>
      <div class="job-title">{{ contact.job_title }}</div>
      
      <div class="contact-info">
        <div class="phone">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
          {{ contact.phone }}
        </div>
        
        <div class="email">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
          {{ contact.email }}
        </div>
      </div>
    </div>
    
    <div class="card-actions">
      <button class="btn-more">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="1"></circle>
          <circle cx="19" cy="12" r="1"></circle>
          <circle cx="5" cy="12" r="1"></circle>
        </svg>
      </button>
    </div>
  </div>
</template>

<style scoped>
.contact-card {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 16px;
  position: relative;
}

.card-header {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
}

.star-container {
  margin-right: 8px;
  color: #f59e0b;
}

.name {
  font-weight: 600;
  font-size: 1.1rem;
  flex-grow: 1;
}

.flag {
  width: 24px;
  height: 16px;
}

.flag img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.company {
  color: #4f46e5;
  font-size: 0.9rem;
  margin-bottom: 4px;
}

.job-title {
  color: #6b7280;
  font-size: 0.9rem;
  margin-bottom: 12px;
}

.contact-info {
  margin-top: 16px;
}

.phone, .email {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
  color: #6b7280;
  font-size: 0.9rem;
}

.phone svg, .email svg {
  margin-right: 8px;
  color: #9ca3af;
}

.card-actions {
  position: absolute;
  top: 16px;
  right: 16px;
}

.btn-more {
  background: none;
  border: none;
  cursor: pointer;
  color: #9ca3af;
}
</style>