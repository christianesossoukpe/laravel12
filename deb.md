composer create-project laravel/laravel vite
composer require laravel/breeze --dev
php artisan breeze:install vue
npm install


php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear



Techniquement c'est ça l'instruction ?

Créer un projet laravel
Dernière version de laravel
Front et Back en laravel
Api des pays pour les lister (rest contry api)
CRUD pour Enregistrer un contact

Name, Email, phone, company, country , Contry_fly (du contact)



Les composants:
voici cette image de ce que je  ceux faire dans laravel inertia:
voici les composants que je veux utiliser:
ContactComponent
ContactCardComponent
ContactFormComponent


voici lapi que je veux utiliser pour les pays:
 https://restcountries.com/

 genere moi le code pour le faire et qu'il soit structuré, utilise des props , vue  script setup sans des exports.





























 Je vais vous générer le code pour créer une interface de gestion de contacts comme dans l'image, en utilisant Laravel avec Inertia.js et Vue 3 (composition API avec script setup). Je vais structurer les composants demandés et intégrer l'API restcountries.com pour les drapeaux de pays.

Voici l'architecture des composants que je propose :

```vue
<script setup>
import { ref, onMounted } from 'vue';
import ContactCard from './ContactCard.vue';
import ContactForm from './ContactForm.vue';
import axios from 'axios';

const contacts = ref([]);
const searchQuery = ref('');
const showForm = ref(false);

onMounted(async () => {
  await fetchContacts();
});

const fetchContacts = async () => {
  try {
    const response = await axios.get('/api/contacts');
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
    await axios.post('/api/contacts', contactData);
    await fetchContacts();
    showForm.value = false;
  } catch (error) {
    console.error('Error adding contact:', error);
  }
};
</script>

<template>
  <div class="dashboard-container">
    <div class="dashboard-header">
      <h1>Dashboard</h1>
    </div>
    
    <div class="contacts-container">
      <div class="toolbar">
        <button 
          class="btn-add-contact" 
          @click="showForm = true"
        >
          Ajouter un contact
        </button>
        
        <div class="search-container">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Search..." 
            class="search-input"
          />
          <button class="search-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </button>
        </div>
      </div>
      
      <div class="contacts-grid">
        <ContactCard 
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
</template>

<style scoped>
.dashboard-container {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.dashboard-header {
  margin-bottom: 20px;
}

.contacts-container {
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.btn-add-contact {
  background-color: #3490dc;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 8px 16px;
  cursor: pointer;
}

.search-container {
  display: flex;
  position: relative;
}

.search-input {
  padding: 8px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  width: 200px;
}

.search-button {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: #718096;
}

.contacts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}
</style>

```

```vue
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
      await axios.delete(`/api/contacts/${props.contact.id}`);
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

```

```vue
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
  <div class="form-overlay">
    <div class="form-container">
      <div class="form-header">
        <h2>Ajouter un contact</h2>
        <button @click="emit('close')" class="btn-close">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <div class="form-body">
        <div class="form-group">
          <label for="name">Nom</label>
          <input 
            type="text" 
            id="name" 
            v-model="form.name" 
            :class="{ 'has-error': errors.name }"
          />
          <div class="error-message" v-if="errors.name">{{ errors.name }}</div>
        </div>
        
        <div class="form-group">
          <label for="company">Entreprise</label>
          <input 
            type="text" 
            id="company" 
            v-model="form.company" 
            :class="{ 'has-error': errors.company }"
          />
          <div class="error-message" v-if="errors.company">{{ errors.company }}</div>
        </div>
        
        <div class="form-group">
          <label for="job_title">Poste</label>
          <input 
            type="text" 
            id="job_title" 
            v-model="form.job_title" 
            :class="{ 'has-error': errors.job_title }"
          />
          <div class="error-message" v-if="errors.job_title">{{ errors.job_title }}</div>
        </div>
        
        <div class="form-group">
          <label for="phone">Téléphone</label>
          <input 
            type="text" 
            id="phone" 
            v-model="form.phone" 
            :class="{ 'has-error': errors.phone }"
          />
          <div class="error-message" v-if="errors.phone">{{ errors.phone }}</div>
        </div>
        
        <div class="form-group">
          <label for="email">Email</label>
          <input 
            type="email" 
            id="email" 
            v-model="form.email" 
            :class="{ 'has-error': errors.email }"
          />
          <div class="error-message" v-if="errors.email">{{ errors.email }}</div>
        </div>
        
        <div class="form-group">
          <label for="country">Pays</label>
          <select 
            id="country" 
            v-model="form.country_code"
            :class="{ 'has-error': errors.country_code }"
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
          <div class="error-message" v-if="errors.country_code">{{ errors.country_code }}</div>
        </div>
      </div>
      
      <div class="form-footer">
        <button @click="emit('close')" class="btn-cancel">Annuler</button>
        <button @click="saveContact" class="btn-save" :disabled="isLoading">
          <span v-if="isLoading">Chargement...</span>
          <span v-else>Enregistrer</span>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.form-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.form-container {
  background-color: white;
  border-radius: 8px;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 20px;
  border-bottom: 1px solid #e2e8f0;
}

.form-header h2 {
  margin: 0;
  font-size: 1.25rem;
}

.btn-close {
  background: none;
  border: none;
  cursor: pointer;
  color: #6b7280;
}

.form-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  font-weight: 500;
  color: #374151;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  font-size: 1rem;
}

.form-group input.has-error,
.form-group select.has-error {
  border-color: #ef4444;
}

.error-message {
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 4px;
}

.form-footer {
  padding: 16px 20px;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.btn-cancel {
  padding: 8px 16px;
  background-color: white;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  color: #6b7280;
  cursor: pointer;
}

.btn-save {
  padding: 8px 16px;
  background-color: #3490dc;
  border: none;
  border-radius: 4px;
  color: white;
  cursor: pointer;
}

.btn-save:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
</style>

```

Pour intégrer ces composants dans votre application Laravel avec Inertia, voici le code côté backend:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Contacts/Index', [
            'contacts' => Contact::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'country_code' => 'required|string|size:2',
        ]);

        Contact::create($validated);

        return redirect()->back();
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        
        return redirect()->back();
    }
}

```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'job_title',
        'phone',
        'email',
        'country_code',
    ];
}

```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company');
            $table->string('job_title');
            $table->string('phone');
            $table->string('email');
            $table->string('country_code', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};

```

```php
<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::get('/api/contacts', [ContactController::class, 'getContacts']);
Route::post('/api/contacts', [ContactController::class, 'store']);
Route::delete('/api/contacts/{contact}', [ContactController::class, 'destroy']);

```

```vue
<script setup>
import { Head } from '@inertiajs/vue3';
import Contact from '@/Components/Contacts/Contact.vue';

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

```

Pour installer et exécuter ce projet, vous devrez :

1. Créer un projet Laravel avec Inertia et Vue
2. Créer les migrations et modèles
3. Mettre en place les routes
4. Créer les composants Vue avec la structure ci-dessus
5. Exécuter les migrations

Quelques points clés sur cette implémentation :

1. Les composants sont structurés selon votre demande (Contact, ContactCard, ContactForm)
2. Utilisation de l'API REST Countries pour les drapeaux de pays
3. Les composants utilisent la composition API avec `<script setup>` sans export
4. Props sont utilisées pour passer les données entre composants
5. Les composants incluent des fonctionnalités complètes (recherche, ajout, suppression)
6. Le style est inspiré de la capture d'écran que vous avez fournie

Cette implémentation offre une interface de gestion de contacts élégante et fonctionnelle avec la possibilité de visualiser, rechercher, ajouter et supprimer des contacts.