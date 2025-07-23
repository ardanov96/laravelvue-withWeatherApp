<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios'; 

// Variabel reaktif untuk menyimpan nama kota dan data cuaca
const city = ref('');
const weatherData = ref(null);
const errorMessage = ref('');
const isLoading = ref(false);

// Fungsi untuk mengambil data cuaca
const fetchWeather = async () => {
    errorMessage.value = ''; // Reset pesan error
    weatherData.value = null; // Reset data cuaca
    isLoading.value = true; // Aktifkan indikator loading

    if (!city.value) {
        errorMessage.value = 'Silakan masukkan nama kota.';
        isLoading.value = false;
        return;
    }

    try {
        // Panggil endpoint API Laravel
        const response = await axios.get(`/api/weather-data?city=${city.value}`);
        weatherData.value = response.data;
        console.log('Data Cuaca:', weatherData.value); 
    } catch (error) {
        console.error('Error fetching weather data:', error);
        if (error.response && error.response.data && error.response.data.error) {
            errorMessage.value = error.response.data.error;
        } else {
            errorMessage.value = 'Gagal mengambil data cuaca. Silakan coba lagi.';
        }
    } finally {
        isLoading.value = false; 
    }
};

// Fungsi pembantu untuk mendapatkan URL ikon cuaca
const getWeatherIconUrl = (iconCode) => {
    if (iconCode) {
        return `https://openweathermap.org/img/wn/${iconCode}@2x.png`;
    }
    return '';
};
</script>

<template>
    <Head title="Aplikasi Cuaca" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Aplikasi Cuaca</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <!-- Input Kota -->
                        <div class="w-full max-w-md">
                            <input
                                type="text"
                                v-model="city"
                                @keyup.enter="fetchWeather"
                                placeholder="Masukkan nama kota..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700"
                            />
                        </div>

                        <!-- Tombol Pencarian -->
                        <button
                            @click="fetchWeather"
                            :disabled="isLoading"
                            class="w-full max-w-md bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="isLoading">Mencari...</span>
                            <span v-else>Cari Cuaca</span>
                        </button>

                        <!-- Pesan Error -->
                        <div v-if="errorMessage" class="w-full max-w-md text-red-600 text-center mt-4">
                            {{ errorMessage }}
                        </div>

                        <!-- Tampilan Data Cuaca -->
                        <div v-if="weatherData" class="w-full max-w-md bg-gradient-to-br from-blue-400 to-blue-600 text-white p-6 rounded-lg shadow-xl mt-6">
                            <h3 class="text-3xl font-bold text-center mb-2">{{ weatherData.name }}</h3>
                            <p class="text-lg text-center mb-4">{{ weatherData.sys.country }}</p>

                            <div class="flex items-center justify-center mb-4">
                                <img
                                    v-if="weatherData.weather && weatherData.weather[0] && weatherData.weather[0].icon"
                                    :src="getWeatherIconUrl(weatherData.weather[0].icon)"
                                    alt="Ikon Cuaca"
                                    class="w-24 h-24"
                                />
                                <p class="text-5xl font-extrabold">{{ Math.round(weatherData.main.temp) }}°C</p>
                            </div>

                            <p class="text-xl text-center capitalize mb-4">
                                {{ weatherData.weather[0].description }}
                            </p>

                            <div class="grid grid-cols-2 gap-4 text-center text-sm">
                                <div>
                                    <p class="font-semibold">Kelembaban:</p>
                                    <p>{{ weatherData.main.humidity }}%</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Kecepatan Angin:</p>
                                    <p>{{ weatherData.wind.speed }} m/s</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Tekanan:</p>
                                    <p>{{ weatherData.main.pressure }} hPa</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Terasa Seperti:</p>
                                    <p>{{ Math.round(weatherData.main.feels_like) }}°C</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pesan Jika Belum Ada Data -->
                        <div v-else-if="!isLoading && !errorMessage" class="w-full max-w-md text-gray-500 text-center mt-6">
                            Masukkan nama kota untuk melihat informasi cuaca.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Menambahkan gaya kustom di sini jika diperlukan.  */
</style>