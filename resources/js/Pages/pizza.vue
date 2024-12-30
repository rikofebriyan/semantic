<template>
    <div class="container mx-auto p-6">
      <h1 class="text-3xl font-bold mb-4">Pizza Ontology</h1>

      <!-- Form pencarian -->
      <div class="mb-4">
        <input
          type="text"
          v-model="searchQuery"
          @input="searchPizza"
          class="border rounded p-2 w-full"
          placeholder="Search for pizza"
        />
      </div>

      <!-- Menampilkan hasil pencarian -->
      <div v-if="searchResults.length > 0">
        <ul>
          <li v-for="(result, index) in searchResults" :key="index" class="mb-4">
            <div class="text-lg font-semibold">{{ result.name }}</div>
            <div class="text-sm text-gray-500">{{ result.comment || 'No comment available' }}</div>
          </li>
        </ul>
      </div>

      <!-- Menampilkan pesan ketika tidak ada hasil -->
      <div v-else-if="searchQuery.trim() !== ''">
        <p class="text-center text-lg font-bold text-red-500">No results found</p>
      </div>

      <!-- Menampilkan loading jika data belum ada -->
      <div v-else>
        <p class="text-center text-lg font-bold">Loading...</p>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        searchQuery: '',  // Input pencarian
        searchResults: [], // Hasil pencarian
        ontologyData: {}   // Data dari API
      };
    },
    mounted() {
      this.fetchOntologyData();  // Ambil data saat komponen dimuat
    },
    methods: {
      // Fungsi untuk mengambil data dari API
      async fetchOntologyData() {
        try {
          const response = await axios.get('/csv2'); // Sesuaikan dengan endpoint API Anda
          this.ontologyData = response.data;
        } catch (error) {
          console.error('Error fetching ontology data:', error);
        }
      },

      // Fungsi untuk mencari pizza berdasarkan query
      searchPizza() {
  try {
    if (this.searchQuery.trim() === '') {
      this.searchResults = [];  // Jika pencarian kosong, reset hasil
      return;
    }

    const searchTerm = this.searchQuery.toLowerCase();
    this.searchResults = [];

    // Loop melalui data untuk mencari nama atau komentar yang cocok
    Object.keys(this.ontologyData).forEach(key => {
      const pizza = this.ontologyData[key];

      // Pastikan komentar ada dan bisa diakses
      const pizzaComment = pizza['http://www.w3.org/2000/01/rdf-schema#comment']
        ? pizza['http://www.w3.org/2000/01/rdf-schema#comment'][0]?.value
        : '';
      const pizzaName = key.split('#')[1] || '';  // Mengambil nama pizza dari key jika ada

      // Cek apakah nama pizza dan komentar valid dan cocok dengan searchTerm
      if (
        pizzaName.toLowerCase().includes(searchTerm) || // Nama pizza cocok
        pizzaComment.toLowerCase().includes(searchTerm)  // Komentar cocok
      ) {
        this.searchResults.push({
          name: pizzaName,
          comment: pizzaComment
        });
      }
    });
  } catch (error) {
    console.error('Error during searchPizza:', error);
  }
}

    }
  };
  </script>

  <style scoped>
  /* Tailwind styling sudah mencakup sebagian besar styling */
  </style>
