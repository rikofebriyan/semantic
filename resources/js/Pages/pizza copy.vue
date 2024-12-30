<template>
    <div v-if="ontologyData && ontologyData['http://www.semanticweb.org/michaeldebellis/ontologies/2020/PizzaTutorial']">
      <div class="container mx-auto p-6">
        <!-- Form input pencarian -->
        <div class="mb-6 flex justify-center">
          <div class="relative w-full max-w-lg">
            <input
              type="text"
              id="search"
              v-model="searchQuery"
              @input="searchPizza"
              class="block w-full px-4 py-2 text-lg rounded-lg border border-gray-300 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent"
              placeholder="Search for a pizza (e.g., AmericanaHotPizza)"
            />
            <div v-if="searchQuery" class="absolute right-2 top-2 flex items-center text-gray-500 cursor-pointer" @click="clearSearch">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Menampilkan hasil pencarian -->
        <div v-if="searchResults.length > 0" class="space-y-6">
          <h2 class="text-2xl font-semibold text-gray-800">Search Results</h2>
          <ul>
            <li v-for="(result, index) in searchResults" :key="index" class="bg-white p-4 rounded-lg shadow-md hover:bg-gray-100 transition">
              <h3 class="text-xl font-semibold text-indigo-600">{{ result.name }}</h3>
              <p class="text-sm text-gray-500">{{ result.comment }}</p>
            </li>
          </ul>
        </div>

        <!-- Menampilkan semua informasi pizza jika tidak ada pencarian -->
        <div v-else class="space-y-6">
          <h1 class="text-3xl font-bold text-indigo-700 mb-4">Pizza Ontology</h1>

          <p class="text-lg mb-6 text-gray-700">
            {{ ontologyData['http://www.semanticweb.org/michaeldebellis/ontologies/2020/PizzaTutorial']['http://www.w3.org/2000/01/rdf-schema#comment']?.[0]?.value || 'No comment available' }}
          </p>

          <div v-if="getType()" class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800">Type:</h2>
            <p class="text-sm text-gray-600">{{ getType() }}</p>
          </div>

          <div v-if="getSubClassOf()" class="bg-white p-4 rounded-lg shadow-md mt-4">
            <h2 class="text-xl font-semibold text-gray-800">Sub Class Of:</h2>
            <p class="text-sm text-gray-600">{{ getSubClassOf() }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Menampilkan loading jika data belum tersedia -->
    <div v-else class="text-center text-lg font-bold text-gray-700">
      <p>Loading...</p>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        ontologyData: {}, // Menyimpan data RDF dari API
        searchQuery: '',  // Menyimpan query pencarian
        searchResults: [] // Menyimpan hasil pencarian
      };
    },
    mounted() {
      this.fetchOntologyData(); // Ambil data dari API ketika komponen dimuat
      console.log('Ontology Data:', this.ontologyData); // Debugging
    },
    methods: {
      // Fungsi untuk mengambil data dari API
      async fetchOntologyData() {
        try {
          const response = await axios.get('/csv2');
          this.ontologyData = response.data;  // Simpan data yang diterima
          console.log('Fetched Data:', response.data);  // Debugging
        } catch (error) {
          console.error('Error fetching ontology data:', error);
        }
      },

      // Mengambil nilai type
      getType() {
        const pizzaTutorial = this.ontologyData['http://www.semanticweb.org/michaeldebellis/ontologies/2020/PizzaTutorial'];
        if (pizzaTutorial && pizzaTutorial['http://www.w3.org/1999/02/22-rdf-syntax-ns#type']) {
          return pizzaTutorial['http://www.w3.org/1999/02/22-rdf-syntax-ns#type']
            .map(item => item.value)
            .join(', ');
        }
        return 'No type available';
      },

      // Mengambil nilai subClassOf
      getSubClassOf() {
        const pizzaTutorial = this.ontologyData['http://www.semanticweb.org/michaeldebellis/ontologies/2020/PizzaTutorial'];
        if (pizzaTutorial && pizzaTutorial['http://www.w3.org/2000/01/rdf-schema#subClassOf']) {
          return pizzaTutorial['http://www.w3.org/2000/01/rdf-schema#subClassOf']
            .map(item => item.value || 'Unknown')
            .join(', ');
        }
        return 'No subclass available';
      },

      // Fungsi pencarian pizza berdasarkan query
      searchPizza() {
        if (this.searchQuery.trim() === '') {
          this.searchResults = [];
          return;
        }

        const searchTerm = this.searchQuery.toLowerCase();
        this.searchResults = [];

        // Loop melalui data pizza dan mencari yang sesuai dengan query
        Object.keys(this.ontologyData).forEach(key => {
          const pizza = this.ontologyData[key];
          if (pizza['http://www.w3.org/2000/01/rdf-schema#comment']) {
            const pizzaName = key.split('#')[1];  // Mengambil nama pizza dari URI
            const pizzaComment = pizza['http://www.w3.org/2000/01/rdf-schema#comment'][0].value.toLowerCase();

            // Jika nama pizza atau comment mengandung kata kunci pencarian
            if (pizzaName.toLowerCase().includes(searchTerm) || pizzaComment.includes(searchTerm)) {
              this.searchResults.push({
                name: pizzaName,
                comment: pizzaComment
              });
            }
          }
        });
      },

      // Fungsi untuk menghapus pencarian
      clearSearch() {
        this.searchQuery = '';
        this.searchResults = [];
      }
    }
  };
  </script>

  <style scoped>
  /* Styling tambahan jika diperlukan */
  </style>
