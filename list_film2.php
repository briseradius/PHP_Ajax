

$$$$$$$$\                                             $$\                       $$\                                                              
$$  _____|                                            $$ |                      $$ |                                                             
$$ |      $$\   $$\  $$$$$$\  $$$$$$\$$$$\   $$$$$$\  $$ | $$$$$$\         $$$$$$$ |$$\   $$\                                                    
$$$$$\    \$$\ $$  |$$  __$$\ $$  _$$  _$$\ $$  __$$\ $$ |$$  __$$\       $$  __$$ |$$ |  $$ |                                                   
$$  __|    \$$$$  / $$$$$$$$ |$$ / $$ / $$ |$$ /  $$ |$$ |$$$$$$$$ |      $$ /  $$ |$$ |  $$ |                                                   
$$ |       $$  $$<  $$   ____|$$ | $$ | $$ |$$ |  $$ |$$ |$$   ____|      $$ |  $$ |$$ |  $$ |                                                   
$$$$$$$$\ $$  /\$$\ \$$$$$$$\ $$ | $$ | $$ |$$$$$$$  |$$ |\$$$$$$$\       \$$$$$$$ |\$$$$$$  |                                                   
\________|\__/  \__| \_______|\__| \__| \__|$$  ____/ \__| \_______|       \_______| \______/                                                    
                                            $$ |                                                                                                 
                                            $$ |                                                                                                 
                                            \__|                                                                                                 
                          $$\                                                          $$\                            $$$$$$\        $$\         
                          $$ |                                                         \__|                          $$$ __$$\     $$$$ |        
 $$$$$$$\  $$$$$$\   $$$$$$$ | $$$$$$\        $$\    $$\  $$$$$$\   $$$$$$\   $$$$$$$\ $$\  $$$$$$\  $$$$$$$\        $$$$\ $$ |    \_$$ |        
$$  _____|$$  __$$\ $$  __$$ |$$  __$$\       \$$\  $$  |$$  __$$\ $$  __$$\ $$  _____|$$ |$$  __$$\ $$  __$$\       $$\$$\$$ |      $$ |        
$$ /      $$ /  $$ |$$ /  $$ |$$$$$$$$ |       \$$\$$  / $$$$$$$$ |$$ |  \__|\$$$$$$\  $$ |$$ /  $$ |$$ |  $$ |      $$ \$$$$ |      $$ |        
$$ |      $$ |  $$ |$$ |  $$ |$$   ____|        \$$$  /  $$   ____|$$ |       \____$$\ $$ |$$ |  $$ |$$ |  $$ |      $$ |\$$$ |      $$ |        
\$$$$$$$\ \$$$$$$  |\$$$$$$$ |\$$$$$$$\          \$  /   \$$$$$$$\ $$ |      $$$$$$$  |$$ |\$$$$$$  |$$ |  $$ |      \$$$$$$  /$$\ $$$$$$\       
 \_______| \______/  \_______| \_______|          \_/     \_______|\__|      \_______/ \__| \______/ \__|  \__|       \______/ \__|\______|      
                                                                                                                                                 
                                                                                                                                                 
                                                                                                                                                 



<?php   include "header.php";
        include 'session.php';
        include "navbar.php";
?>
<body>
        <div class="container">
            <div class="row" >
            <div class="col-10">
                <div class="row" id="film-card-anchor">
                </div>
            </div>
                <div class="col-2 overflow-auto">
                    <h6 class="text-secondary">Recherche par genre</h6>
                    <ul class="list-group" id="genre_list">
                    </ul>
                </div>
            </div>
        </div>
    <script>
        // Déclaration de la variable pour vérifier si l'utilisateur est adulte
        let isAdult = false;
        
        // Récupération de l'âge de la session PHP si disponible
        <?php if( isset($_SESSION['age'])){
            echo "const age = ". $_SESSION['age'] .";";
        }
        ?>
        
        // Vérification de l'âge pour autoriser les films adultes
        if (typeof age !== "undefined" && age >= 18) {
            isAdult = true;
        }

        // Sélection des éléments du DOM
        const searchForm = document.getElementById('searchForm');
        const search = document.getElementById('search');
        const filmAnchor = document.getElementById('film-card-anchor');
        const genreList = document.getElementById('genre_list');
        
        // Clé API et URL de base pour les films
        const apiKey = '245d54f855207f523fb1b30b39175326';
        const urlFilm = `https://api.themoviedb.org/3/discover/movie?api_key=${apiKey}&language=fr&sort_by=popularity.desc&include_adult=${isAdult}&page=1&with_watch_monetization_types=flatrate`;
        
        let genres = [];
        
        // Récupérer la liste des genres de films
        getGenres(`https://api.themoviedb.org/3/genre/movie/list?api_key=${apiKey}&language=fr`);
        listFilm(urlFilm);

        // Fonction pour configurer les liens des genres
        function configLink(links) {
            [...links].forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    const id = link.dataset.genre;
                    // Charger les films par genre sélectionné
                    listFilm(`https://api.themoviedb.org/3/discover/movie?api_key=${apiKey}&language=fr&with_genres=${id}&include_adult=${isAdult}`);
                });
            });
        }

        // Fonction pour récupérer la liste des genres
        function getGenres(url) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    genres = data.genres;
                    genreList.innerHTML = genres.map(genre => 
                        `<li class="list-group-item"><a class="genres" href="#" data-genre="${genre.id}">${genre.name}</a></li>`
                    ).join('');
                    configLink(document.getElementsByClassName("genres"));
                });
        }

        // Gérer la recherche de films par titre
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = search.value.trim();
            if (query.length > 0) {
                const searchUrl = `https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&language=fr&query=${encodeURIComponent(query)}&include_adult=${isAdult}`;
                listFilm(searchUrl);
            }
        });

        // Fonction pour récupérer et afficher la liste des films
        function listFilm(url) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    filmAnchor.innerHTML = data.results.map(result => {
                        // Récupérer la liste des genres pour chaque film
                        const genre_list = result.genre_ids.map(id => {
                            const genre = genres.find(g => g.id === id);
                            return genre ? `<a class="card-link card_genres_link" data-genres="${genre.id}" href="#">${genre.name}</a>` : "";
                        }).join(" ");

                        // Tronquer le synopsis si trop long
                        const synopsis = result.overview.length > 255 ? result.overview.slice(0, 255) + "... <a class='card-link' href='#'>Lire la suite</a>" : result.overview;
                        
                        // Vérifier si une image est disponible
                        const imageFilm = result.backdrop_path ? `<img src="https://image.tmdb.org/t/p/w500/${result.backdrop_path}" class="card-img-top" alt="affiche de ${result.title}">` : "";

                        // Générer la carte du film
                        return `<div class="col-4 my-2">
                                    <div class="card">
                                        ${imageFilm}
                                        <div class="card-body">
                                            <h5 class="card-title">${result.title}</h5>
                                            <p class="card-text h6">${synopsis}</p>
                                            ${genre_list}
                                        </div>
                                    </div>
                                </div>`;
                    }).join('');
                    configLink(document.getElementsByClassName('card_genres_link'));
                });
        }
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Vérification du chargement de Bootstrap
    if (typeof bootstrap === 'undefined') {
        console.log("Bootstrap ne s'est pas chargé !");
    } else {
        console.log("Bootstrap est bien chargé !");
    }
</script>
</body>
</html>
