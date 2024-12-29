<template>
    <div id="app">
      <h1>Movie Search</h1>
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search by title, genre, or cast"
        @keyup.enter="searchMovies"
      />
      <button @click="searchMovies">Search</button>

      <!-- Tabel hanya muncul setelah pencarian dan ada hasil -->
      <table v-if="filteredMovies.length > 0">
        <thead>
          <tr>
            <th>Title</th>
            <th>Overview</th>
            <th>Genres</th>
            <th>Producer</th>
            <th>Cast</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(movie, index) in filteredMovies" :key="index">
            <td>{{ movie.title }}</td>
            <td>{{ movie.overview }}</td>
            <td>{{ movie.genres }}</td>
            <td>{{ movie.producer }}</td>
            <td>{{ movie.cast }}</td>
          </tr>
        </tbody>
      </table>
      <p v-else v-if="searchQuery !== ''">No movies found</p> <!-- Pesan jika tidak ada hasil pencarian -->
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        searchQuery: '',
        movies: [],
        filteredMovies: [],
      };
    },
    created() {
      this.fetchMovies();
    },
    methods: {
      // Mengambil data dari API Laravel
      async fetchMovies() {
        try {
          const response = await axios.get('http://localhost:8000/csv'); // Gantilah dengan URL API yang sesuai
          if (response.data.success) {
            this.movies = Object.values(response.data.data); // Mengkonversi objek ke array
          } else {
            console.error('Error fetching movies:', response.data.message);
          }
        } catch (error) {
          console.error('Error fetching data:', error);
        }
      },

      // Fungsi untuk pencarian
      searchMovies() {
        if (this.searchQuery.trim() !== '') {
          this.filterMovies(); // Hanya lakukan pencarian jika searchQuery tidak kosong
        }
      },

      // Fungsi untuk memfilter data berdasarkan pencarian
      filterMovies() {
        const query = this.searchQuery.toLowerCase();
        this.filteredMovies = this.movies.filter((movie) => {
          return (
            movie.title.toLowerCase().includes(query) ||
            movie.genres.toLowerCase().includes(query) ||
            movie.cast.toLowerCase().includes(query)
          );
        });
      },
    },
  };
  </script>

  <style scoped>
  #app {
    font-family: Arial, sans-serif;
    padding: 20px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
  }

  input {
    padding: 10px;
    margin-bottom: 10px;
    width: 100%;
    max-width: 600px;
    border: 1px solid #ccc;
  }

  button {
    padding: 10px;
    margin-left: 10px;
    cursor: pointer;
    border: 1px solid #ccc;
    background-color: #4CAF50;
    color: white;
  }
  </style>
