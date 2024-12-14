<template>
    <div class="search-container">
      <h1>Search DBpedia for Movies</h1>

      <!-- Form Input -->
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search for a movie..."
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
            <a :href="result.url" target="_blank">Read More</a>
          </li>
        </ul>
      </div>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        searchQuery: '',
        results: null,
        loading: false,
        error: null,
      };
    },
    methods: {
      async searchData() {
        if (this.searchQuery.trim() === '') return;

        this.loading = true;
        this.error = null;
        this.results = null;

        try {
          // Mengirim request ke DBpedia dengan query SPARQL yang difokuskan pada film
          const response = await axios.get(`https://dbpedia.org/sparql`, {
            params: {
              query: `
                SELECT DISTINCT ?label ?comment ?url WHERE {
                  ?subject a dbo:Film;
                            rdfs:label ?label;
                            rdfs:comment ?comment.
                  FILTER (lang(?label) = 'en' && lang(?comment) = 'en')
                  ?subject <http://www.w3.org/2002/07/owl#sameAs> ?url.
                  FILTER CONTAINS(LCASE(?label), LCASE("${this.searchQuery}"))
                }
                LIMIT 5000
              `,
              format: 'json',
            },
          });

          // Memproses hasil pencarian dan menghilangkan duplikasi jika ada
          this.results = [
            ...new Map(
              response.data.results.bindings.map((item) => [
                item.label.value,
                {
                  label: item.label.value,
                  comment: item.comment.value,
                  url: item.url.value,
                },
              ])
            ).values(),
          ];
        } catch (err) {
          this.error = 'An error occurred while fetching data.';
        } finally {
          this.loading = false;
        }
      },
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
