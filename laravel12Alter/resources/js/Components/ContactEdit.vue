<script setup>
import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const props = defineProps({
    contact: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["refresh", "close"]);
const name = ref(props.contact.name);
const company = ref(props.contact.company);
const jobTitle = ref(props.contact.job_title);
const phone = ref(props.contact.phone);
const email = ref(props.contact.email);
const countryCode = ref(props.contact.country_code);

const handleSubmit = async () => {
    try {
        // Envoi de la requête PUT pour mettre à jour le contact
        const response = await axios.put(`/contacts/${props.contact.id}`, {
            name: name.value,
            company: company.value,
            job_title: jobTitle.value,
            phone: phone.value,
            email: email.value,
            country_code: countryCode.value,
        });

        // Afficher une notification de succès avec SweetAlert
        await Swal.fire({
            title: "Mis à jour !",
            text: "Le contact a bien été mis à jour.",
            icon: "success",
            timer: 2000,
            showConfirmButton: false,
        })
        .then(() => {
            window.location.reload();
        });

        // Fermer le formulaire d'édition
        emit("close");

        // Rafraîchir la liste des contacts
        emit("refresh");
    } catch (error) {
        console.error("Erreur lors de la mise à jour :", error);
        Swal.fire({
            title: "Erreur",
            text: "Une erreur est survenue lors de la mise à jour.",
            icon: "error",
        });
    }
};
</script>
<template>
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
    >
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 relative animate-fade-in-down"
        >
            <!-- Bouton de fermeture -->
            <button
                @click="emit('close')"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

            <h2 class="text-xl font-bold mb-4 text-gray-800">
                Modifier le contact
            </h2>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div>
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                        >Nom</label
                    >
                    <input
                        type="text"
                        id="name"
                        v-model="name"
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:border-blue-400"
                        required
                    />
                </div>

                <div>
                    <label
                        for="company"
                        class="block text-sm font-medium text-gray-700"
                        >Entreprise</label
                    >
                    <input
                        type="text"
                        id="company"
                        v-model="company"
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:border-blue-400"
                        required
                    />
                </div>

                <div>
                    <label
                        for="jobTitle"
                        class="block text-sm font-medium text-gray-700"
                        >Poste</label
                    >
                    <input
                        type="text"
                        id="jobTitle"
                        v-model="jobTitle"
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:border-blue-400"
                        required
                    />
                </div>

                <div>
                    <label
                        for="phone"
                        class="block text-sm font-medium text-gray-700"
                        >Téléphone</label
                    >
                    <input
                        type="text"
                        id="phone"
                        v-model="phone"
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:border-blue-400"
                        required
                    />
                </div>

                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                        >Email</label
                    >
                    <input
                        type="email"
                        id="email"
                        v-model="email"
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:border-blue-400"
                        required
                    />
                </div>

                <div>
                    <label
                        for="countryCode"
                        class="block text-sm font-medium text-gray-700"
                        >Code pays</label
                    >
                    <input
                        type="text"
                        id="countryCode"
                        v-model="countryCode"
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:border-blue-400"
                        required
                    />
                </div>

                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full py-2 px-4 bg-[#06708E] hover:bg-blue-700 text-white rounded-lg transition"
                    >
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in-down {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-down {
    animation: fade-in-down 0.3s ease-out;
}
</style>
