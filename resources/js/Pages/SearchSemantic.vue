<template>
    <div id="app" class="bg-cover bg-center h-screen w-screen m-0 bg-black"
        style="background-image: url('/storage/background.bmp'); background-size: contain; background-repeat: no-repeat; background-position: center; max-width: 100vw; max-height: 100vh;">
        <!-- Teks berjalan di bagian atas -->
        <div class="marquee">
            <span>
                Tugas Web Semantik Kelompok 6 : Meilisa Rozita Dewi | Ikhsan Fauzi Akbar | Rifaa Ayu Saskara | Riko
                Febriyan | Sutrisno | Teguh Tri Prasetya | Wahyu Heneng Murjiyanti |
            </span>
            <span>
                Tugas Web Semantik Kelompok 6 : Meilisa Rozita Dewi | Ikhsan Fauzi Akbar | Rifaa Ayu Saskara | Riko
                Febriyan | Sutrisno | Teguh Tri Prasetya | Wahyu Heneng Murjiyanti |
            </span>
        </div>

        <!-- Form pencarian -->
        <div class="search-container">
            <div class="search-form">
                <h1 class="SearchMovies">Search Movies Versi 1</h1>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search search-icon"></i>
                    </span>
                    <input type="text" class="form-control search-input" placeholder="Search your movies here..."
                        v-model="searchQuery" @keyup.enter="searchMovies" />
                </div>
                <button class="btn search-btn mt-3" @click="searchMovies">
                    Search <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Hasil pencarian dengan highlight -->
        <div id="results" class="results-container">
            <h3>Search Results</h3>
            <ul class="movie-list">
                <li v-for="movie in filteredMovies" :key="movie.id" class="movie-item">
                    <h2 v-html="highlightText(movie.title)"></h2>
                    <p><strong>Overview:</strong> <span v-html="highlightText(movie.overview)"></span></p>
                    <p><strong>Genres:</strong> <span v-html="highlightText(movie.genres)"></span></p>
                    <p><strong>Producer:</strong> <span v-html="highlightText(movie.producer)"></span></p>
                    <p><strong>Cast:</strong> <span v-html="highlightText(movie.cast)"></span></p>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            searchQuery: "",
            movies: [],
            filteredMovies: [],
        };
    },
    created() {
        this.fetchMovies();
    },
    methods: {
        async fetchMovies(query = "") {
            try {
                // Kirimkan query pencarian jika ada
                const response = await axios.get(`http://localhost:8000/csv?search=${query}`);
                if (response.data.success) {
                    this.movies = response.data.data;
                    this.filteredMovies = this.movies; // Setel data yang difilter awalnya ke semua film
                } else {
                    console.error("Error fetching movies:", response.data.message);
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },
        searchMovies() {
            if (this.searchQuery.trim() !== "") {
                // Ambil query pencarian
                this.fetchMovies(this.searchQuery);
                this.scrollToResults();
            } else {
                // Jika tidak ada query, ambil semua film
                this.fetchMovies();
            }
        },
        highlightText(text) {
            if (!text || !this.searchQuery.trim()) return text;
            const escapedQuery = this.searchQuery.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"); // Escape special characters
            const regex = new RegExp(`(${escapedQuery})`, "gi"); // Create regex to match query
            return text.replace(regex, '<span class="highlight">$1</span>'); // Replace with highlighted span
        },
        scrollToResults() {
            const resultsSection = document.getElementById("results");
            if (resultsSection) {
                resultsSection.scrollIntoView({ behavior: "smooth" });
            }
        },
    },
};
</script>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

#app {
    display: flex;
    flex-direction: column;
    height: 200vh;
    padding: 0;
    box-sizing: border-box;
}

.marquee {
    white-space: nowrap;
    overflow: hidden;
    position: absolute;
    bottom: 5px;
    width: 100%;
    text-align: center;
    background-color: rgba(243, 132, 6, 0.8);
    padding: 10px 0;
    color: rgb(7, 7, 7);
    font-weight: bold;
    font-size: 18px;
}

.marquee span {
    display: inline-block;
    padding-left: 80%;
    animation: scroll 15s linear infinite;
}

.marquee span:nth-child(2) {
    animation-delay: 7s;
}

@keyframes scroll {
    from {
        transform: translateX(0%);
    }

    to {
        transform: translateX(-100%);
    }
}

.search-container {
    position: absolute;
    top: 65%;
    left: 30%;
    width: 80%;
    text-align: center;
}

.search-form {
    background-color: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    max-width: 600px;
    width: 100%;
}

.search-input {
    width: 100%;
    padding: 15px 20px;
    border-radius: 50px;
    border: 2px solid #ccc;
    margin-right: 10px;
    font-size: 16px;
}

.search-btn {
    border-radius: 50px;
    background-color: #007bff;
    color: white;
    padding: 12px 30px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.search-btn:hover {
    background-color: #0056b3;
}

.results-container {
    margin-top: 100vh;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.movie-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.movie-item {
    background-color: #f9f9f9;
    margin-bottom: 15px;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.movie-item h4 {
    margin: 0 0 10px;
}

.movie-item p {
    margin: 5px 0;
    line-height: 1.5;
}

.highlight {
    background-color: yellow;
    color: black;
    font-weight: bold;
}

.results-container h2 {
    font-size: 2em;
    font-weight: bold;
    color: black;
    margin: 10px 0;
}

.SearchMovies {
    font-size: 1.5em;
    font-weight: bold;
    color: black;
    margin: 10px 0;
}
</style>
