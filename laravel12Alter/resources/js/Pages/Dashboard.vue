<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Tableau de bord" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Tableau de bord</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Section principale du tableau de bord -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
          <!-- Carte Utilisateurs Totaux par Pays -->
          <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
              <div class="text-2xl font-semibold text-gray-800">Utilisateurs Totaux</div>
              <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"></path>
                </svg>
              </div>
            </div>
            <div class="mt-4">
              <div class="text-sm text-gray-600">Utilisateurs par pays</div>
              <div class="flex space-x-4 mt-4">
                <div class="flex-1 bg-green-200 p-4 rounded-lg">
                  <div class="font-semibold text-lg">États-Unis</div>
                  <div class="text-sm text-gray-500">1 254 utilisateurs</div>
                </div>
                <div class="flex-1 bg-yellow-200 p-4 rounded-lg">
                  <div class="font-semibold text-lg">Allemagne</div>
                  <div class="text-sm text-gray-500">899 utilisateurs</div>
                </div>
                <div class="flex-1 bg-red-200 p-4 rounded-lg">
                  <div class="font-semibold text-lg">Inde</div>
                  <div class="text-sm text-gray-500">1 350 utilisateurs</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Graphique de performance par pays -->
          <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="text-2xl font-semibold text-gray-800">Performance par pays</div>
            <div class="mt-4">
              <!-- Vous pouvez intégrer ici une bibliothèque de graphiques comme Chart.js, ApexCharts, etc. -->
              <div class="h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                <span class="text-gray-500">Graphique montrant la performance par pays</span>
              </div>
            </div>
          </div>

          <!-- Progression des tâches -->
          <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="text-2xl font-semibold text-gray-800">Progression des tâches</div>
            <div class="mt-4">
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Tâche 1</span>
                <span class="text-sm font-medium text-gray-900">80%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full mt-2 h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 80%"></div>
              </div>
            </div>
            <div class="mt-4">
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Tâche 2</span>
                <span class="text-sm font-medium text-gray-900">60%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full mt-2 h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 60%"></div>
              </div>
            </div>
          </div>

          <!-- Activités récentes -->
          <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="text-2xl font-semibold text-gray-800">Activités récentes</div>
            <div class="mt-4">
              <ul class="space-y-3 text-sm text-gray-600">
                <li>Activité aux États-Unis - Il y a 1 heure</li>
                <li>Activité en Allemagne - Il y a 3 heures</li>
                <li>Activité en Inde - Il y a 5 heures</li>
                <li>Activité en France - Il y a 12 heures</li>
              </ul>
            </div>
          </div>

          <!-- État du système -->
          <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="text-2xl font-semibold text-gray-800">État du système</div>
            <div class="mt-4">
              <div class="flex items-center space-x-2">
                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                <span class="text-sm text-gray-600">Tous les systèmes sont opérationnels</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Bannière d'information -->
        <div class="bg-blue-50 p-6 mt-6 rounded-lg">
          <div class="text-blue-600">
            <svg class="inline w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"></path>
            </svg>
            <span class="font-semibold">Nouvelles fonctionnalités à venir !</span> Découvrez nos prochaines mises à jour et fonctionnalités.
          </div>
        </div>

        <!-- Footer (facultatif) -->
        <div class="mt-8 text-center text-gray-500 text-sm">
          <p>&copy; 2025 Nom de votre entreprise. Tous droits réservés.</p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
