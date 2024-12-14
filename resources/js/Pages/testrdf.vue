<template>
    <div class="search-container">
      <h1>Search RDF for Pizzas</h1>

      <!-- Form Input -->
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search for a pizza..."
        @keyup.enter="searchData"
        class="search-input"
      />
      <button @click="searchData" class="search-button">Search</button>

      <!-- Hasil Pencarian -->
      <div v-if="loading" class="loading">Loading...</div>

      <div v-if="error" class="error">
        <p>{{ error }}</p>
      </div>

      <div v-if="results && !loading" class="results">
        <h2>Results:</h2>
        <ul>
          <li v-for="(result, index) in results" :key="index">
            <h3>{{ result.label }}</h3>
            <p>{{ result.comment }}</p>
          </li>
        </ul>
      </div>
    </div>
  </template>

  <script>
  import rdflib from 'rdflib';

  export default {
    data() {
      return {
        searchQuery: '',
        results: [],
        loading: false,
        error: null,
        rdfGraph: rdflib.graph(), // Graph untuk menyimpan data RDF
      };
    },
    methods: {
      // Fungsi untuk memuat file RDF dan menjalankan query
      async loadRdfFile() {
        try {
          // Memuat file RDF lokal
          const response = await fetch('/path/to/your/file.rdf'); // Sesuaikan path file RDF
          const text = await response.text();

          // Memuat data RDF ke dalam graph
          rdflib.parse(text, this.rdfGraph, 'http://example.org/', 'application/rdf+xml');
        } catch (err) {
          this.error = 'Error loading RDF file: ' + err.message;
        }
      },

      // Fungsi untuk mencari data berdasarkan query
      async searchData() {
        if (this.searchQuery.trim() === '') return;

        this.loading = true;
        this.error = null;
        this.results = [];

        try {
          // Memastikan RDF file sudah dimuat
          if (this.rdfGraph.size === 0) {
            await this.loadRdfFile();
          }

          // Query SPARQL untuk mencari pizza berdasarkan nama
          const query = `
            SELECT ?pizza ?label ?comment WHERE {
              ?pizza a <http://www.semanticweb.org/michaeldebellis/ontologies/2020/PizzaTutorial#NamedPizza>;
                     rdfs:label ?label;
                     rdfs:comment ?comment.
              FILTER (CONTAINS(LCASE(?label), LCASE("${this.searchQuery}")))
            }
            LIMIT 10
          `;

          const results = rdflib.SPARQLToQuery(query, false, this.rdfGraph);

          // Ambil hasil dari query
          const bindings = results.map((binding) => {
            return {
              label: binding.label.value,
              comment: binding.comment ? binding.comment.value : 'No description available.',
            };
          });

          this.results = bindings;
        } catch (err) {
          this.error = 'An error occurred while searching data: ' + err.message;
        } finally {
          this.loading = false;
        }
      },
    },

    mounted() {
      // Load RDF file when component is mounted
      this.loadRdfFile();
    },
  };
  </script>

  <style scoped>
  .search-container {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
  }

  .search-input {
    width: 70%;
    padding: 10px;
    margin-right: 10px;
    font-size: 16px;
  }

  .search-button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    font-size: 16px;
    cursor: pointer;
  }

  .results {
    margin-top: 20px;
  }

  .results ul {
    list-style: none;
    padding: 0;
  }

  .results li {
    margin-bottom: 20px;
  }

  .error {
    color: red;
    font-size: 18px;
  }

  .loading {
    font-size: 18px;
  }
  </style>
