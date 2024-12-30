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
                <h3 class="SearchMovies">Search Movies Versi 2</h3>
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

        <!-- Tabel hasil pencarian menggunakan Ag-Grid -->
        <div id="results" class="results-container">
            <h3>Search Results</h3>
            <AgGridVue class="ag-theme-alpine" style="width: 100%; height: 80vh;" :columnDefs="columnDefs"
                :rowData="filteredMovies" :defaultColDef="defaultColDef" :pagination="true" :paginationPageSize="20"
                :rowHeight="500" />
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
            columnDefs: [
                {
                    headerName: "Title",
                    field: "title",
                    minWidth: 200,
                    maxWidth: 250,
                    sortable: true,
                    filter: true,
                    wrapText: true,
                    cellRenderer: (params) => this.highlightText(params.value),
                },
                {
                    headerName: "Overview",
                    field: "overview",
                    minWidth: 200,
                    maxWidth: 250,
                    sortable: true,
                    filter: true,
                    wrapText: true,
                    cellRenderer: (params) => this.highlightText(params.value),
                },
                {
                    headerName: "Genres",
                    field: "genres",
                    minWidth: 150,
                    maxWidth: 200,
                    sortable: true,
                    filter: true,
                    wrapText: true,
                    cellRenderer: (params) => this.highlightText(params.value),
                },
                {
                    headerName: "Producer",
                    field: "producer",
                    minWidth: 150,
                    maxWidth: 200,
                    sortable: true,
                    filter: true,
                    wrapText: true,
                },
                {
                    headerName: "Cast",
                    field: "cast",
                    sortable: true,
                    filter: true,
                    wrapText: true,
                    cellRenderer: (params) => this.highlightText(params.value),
                },
            ],
            defaultColDef: {
                flex: 1,
                minWidth: 100,
                resizable: true,
            },
        };
    },
    created() {
        this.fetchMovies();
    },
    methods: {
        async fetchMovies() {
            try {
                // Kirim query pencarian ke server
                const response = await axios.get(`http://localhost:8000/csv`, {
                    params: {
                        search: this.searchQuery,
                    },
                });
                if (response.data.success) {
                    this.movies = Object.values(response.data.data);
                    this.filteredMovies = this.movies;  // Menampilkan semua data pada awal
                } else {
                    console.error("Error fetching movies:", response.data.message);
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },
        searchMovies() {
            if (this.searchQuery.trim() !== "") {
                // Lakukan pencarian ulang dengan query baru
                this.fetchMovies();
                this.scrollToResults();
            }
        },
        highlightText(text) {
            if (!this.searchQuery) return text;
            const query = this.searchQuery
                .split(" ")
                .filter((word) => word.trim() !== "")
                .join("|");
            const regex = new RegExp(`(${query})`, "gi");
            return text.replace(regex, "<mark>$1</mark>");
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

.results-container .ag-theme-alpine .ag-cell {
    line-height: 0.5;
    padding: 5px;
}

.SearchMovies {
    font-size: 1.5em;
    font-weight: bold;
    color: black;
    margin: 10px 0;
}
</style>
