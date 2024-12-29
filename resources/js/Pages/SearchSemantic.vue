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
                <h3>Search Movies</h3>
                <div class="input-group">
                    <!-- Ikon pada input field -->
                    <span class="input-group-text">
                        <i class="fas fa-search search-icon"></i>
                    </span>
                    <input type="text" class="form-control search-input" placeholder="Search your movies here..."
                        v-model="searchQuery" @keyup.enter="searchMovies" />
                </div>
                <!-- Tombol pencarian -->
                <button class="btn search-btn mt-3" @click="searchMovies">
                    Search <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Tabel hasil pencarian -->
        <div id="results" class="results-container">
            <h3>Search Results</h3>
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
            <p v-else v-if="searchQuery !== ''">No movies found</p>
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
        async fetchMovies() {
            try {
                const response = await axios.get("http://localhost:8000/csv");
                if (response.data.success) {
                    this.movies = Object.values(response.data.data);
                } else {
                    console.error("Error fetching movies:", response.data.message);
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },
        searchMovies() {
            if (this.searchQuery.trim() !== "") {
                this.filterMovies();
                this.scrollToResults();
            }
        },
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
        scrollToResults() {
            const resultsSection = document.getElementById("results");
            if (resultsSection) {
                resultsSection.scrollIntoView({ behavior: "smooth" });
            }
        },
    },
};
</script>

<style scoped>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

#app {
    display: flex;
    flex-direction: column;
    height: 200vh;
    /* Agar halaman dapat di-scroll */
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
    /* Agar hasil ada di bagian bawah */
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.results-container table {
    width: 100%;
    border-collapse: collapse;
}

.results-container th,
.results-container td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}
</style>
