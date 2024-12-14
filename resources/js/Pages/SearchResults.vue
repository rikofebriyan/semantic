<template>
    <div>
      <!-- Form pencarian -->
      <form @submit.prevent="search">
        <input v-model="query" type="text" placeholder="Cari pizza..." class="border p-2" />
        <button type="submit" class="bg-blue-500 text-white p-2">Cari</button>
      </form>

      <!-- Tampilkan hasil pencarian -->
      <div v-if="results.length">
        <h3>Hasil Pencarian:</h3>
        <ul>
          <li v-for="(result, index) in results" :key="index">
            {{ result.name }}
          </li>
        </ul>
      </div>
      <div v-else>
        <p>Tidak ada hasil yang ditemukan.</p>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        query: '',
        results: []
      };
    },
    methods: {
      async search() {
        try {
          // Kirim permintaan pencarian ke backend
          const response = await axios.get(`/search?query=${this.query}`);
          this.results = response.data.results;
        } catch (error) {
          console.error('Error fetching search results:', error);
        }
      }
    }
  };
  </script>
