<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import ConctactCard from "./ConctactCard.vue";
import ContactForm from "./ContactForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ContactEdit from "./ContactEdit.vue";

const contacts = ref([]);
const searchQuery = ref("");
const showForm = ref(false);
const currentPage = ref(1);
const perPage = ref(6); // Nombre d'éléments par page


const props = defineProps({
  contacts: Array
  });
onMounted(async () => {
    await fetchContacts();
});

const fetchContacts = async () => {
    try {
        const response = await axios.get("/getscontacts");
        contacts.value = response.data;
    } catch (error) {
        console.error("Error fetching contacts:", error);
    }
};

const filteredContacts = computed(() => {
    if (!searchQuery.value) return contacts.value;

    return contacts.value.filter(
        (contact) =>
            contact.name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            contact.email
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            contact.job_title
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase())
    );
});

const paginatedContacts = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    const end = start + perPage.value;
    return filteredContacts.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredContacts.value.length / perPage.value);
});

const addContact = async (contactData) => {
    try {
        await axios.post("/contacts", contactData);
        await fetchContacts();
        showForm.value = false;
    } catch (error) {
        console.error("Error adding contact:", error);
    }
};

const goToPage = (page) => {
    if (page < 1 || page > totalPages.value) return;
    currentPage.value = page;
};
const showEditModal = ref(false);
const selectedContact = ref(null);

const handleEdit = (contact) => {
  selectedContact.value = contact;
  showEditModal.value = true;
};
const refreshContacts = () => {
  // à implémenter : ex. appeler une route Laravel via Inertia
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
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5"
                >
                    <ConctactCard
                        v-for="contact in paginatedContacts"
                        :key="contact.id"
                        :contact="contact"
                        @edit="handleEdit"
                        @refresh="fetchContacts"
                    />

                    
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-5">
                <button
                    :disabled="currentPage === 1"
                    @click="goToPage(currentPage - 1)"
                    class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400 disabled:opacity-50"
                >
                    Précédent
                </button>

                <div class="flex space-x-2">
                    <button
                        v-for="page in totalPages"
                        :key="page"
                        :class="{
                            'bg-[#06708E] text-white': currentPage === page,
                            'bg-white text-blue-500': currentPage !== page
                        }"
                        class="px-4 py-2 border border-gray-300 rounded-md hover:bg-blue-200"
                        @click="goToPage(page)"
                    >
                        {{ page }}
                    </button>
                </div>

                <button
                    :disabled="currentPage === totalPages"
                    @click="goToPage(currentPage + 1)"
                    class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400 disabled:opacity-50"
                >
                    Suivant
                </button>
            </div>

            <ContactForm
                v-if="showForm"
                @close="showForm = false"
                @save="addContact"
            />
            <ContactEdit
      v-if="showEditModal"
      :contact="selectedContact"
      @close="showEditModal = false"
      @refresh="refreshContacts"
    />
        </div>
    </AuthenticatedLayout>
</template>
