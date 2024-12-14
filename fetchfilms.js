const axios = require('axios');
const fs = require('fs');

async function fetchFilmData() {
  try {
    // Mengirim permintaan GET ke DBpedia SPARQL endpoint
    const response = await axios.get('https://dbpedia.org/sparql', {
      params: {
        query: `
          SELECT DISTINCT ?label ?comment ?url WHERE {
            ?subject a dbo:Film;
                      rdfs:label ?label;
                      rdfs:comment ?comment.
            FILTER (lang(?label) = 'en' && lang(?comment) = 'en')
            ?subject <http://www.w3.org/2002/07/owl#sameAs> ?url.
          }
          LIMIT 1000
        `,
        format: 'json',
      },
    });

    // Menangani hasil JSON dari DBpedia
    const results = response.data.results.bindings;

    // Menyimpan hasil data film ke dalam file JSON
    fs.writeFileSync('films.json', JSON.stringify(results, null, 2), 'utf-8');
    console.log('Data saved to films.json');

    return results;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
}

// Contoh penggunaan fungsi
fetchFilmData().then(films => {
  // Proses data film yang ditemukan
  films.forEach(film => {
    console.log(`Title: ${film.label.value}, URL: ${film.url.value}`);
  });
});
