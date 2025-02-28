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
                    <ul class="list-group" id="genre_list"></ul>
                </div>
            </div>
        </div>
    <script>
        let isAdult = false;
        
        <?php if( isset($_SESSION['age'])){
            echo "const age = ". $_SESSION['age'] .";";
        }
        ?>
        
        if (typeof age !== "undefined" && age >= 18) {
            isAdult = true;
        }
        
        const filmAnchor = document.getElementById('film-card-anchor');
        const genreList = document.getElementById('genre_list');
        
        const apiKey = '245d54f855207f523fb1b30b39175326';
        const urlFilm = `https://api.themoviedb.org/3/discover/movie?api_key=${apiKey}&language=fr&sort_by=popularity.desc&include_adult=${isAdult}&page=1&with_watch_monetization_types=flatrate`;
        const searchForm = document.getElementById('searchForm'); 
        let genres = [];
        
        getGenres(`https://api.themoviedb.org/3/genre/movie/list?api_key=${apiKey}&language=fr`);
        listFilm(urlFilm);

        function configLink(links) {
            [...links].forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    const id = link.dataset.genre;
                    listFilm(`https://api.themoviedb.org/3/discover/movie?api_key=${apiKey}&language=fr&with_genres=${id}&include_adult=${isAdult}`);
                });
            });
        }

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

        function listFilm(url) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    filmAnchor.innerHTML = data.results.map(result => {
                        const genre_list = result.genre_ids.map(id => {
                            const genre = genres.find(g => g.id === id);
                            return genre ? `<a class="card-link card_genres_link" data-genres="${genre.id}" href="#">${genre.name}</a>` : "";
                        }).join(" ");
                        
                        const synopsis = result.overview.length > 255 ? result.overview.slice(0, 255) + "..." : result.overview;
                        const imageFilm = result.backdrop_path ? `<img src="https://image.tmdb.org/t/p/w500/${result.backdrop_path}" class="card-img-top" alt="affiche de ${result.title}">` : `<img src="img/th.jpg" class="card-img-top" alt="img indisponible">`;

                        return `<div class="col-4 my-2">
                                    <div class="card">
                                        <a href="film.php?id=${result.id}" class="stretched-link">
                                            ${imageFilm}
                                        </a>
                                        <div class="card-body">
                                            <h5 class="card-title"><a href="film.php?id=${result.id}">${result.title}</a></h5>
                                            <p class="card-text h6">${synopsis}</p>
                                            ${genre_list}
                                        </div>
                                    </div>
                                </div>`;
                    }).join('');
                    configLink(document.getElementsByClassName('card_genres_link'));
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
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



$$$$$$\                             $$$$$$\  $$\ $$\                                                              $$\                                                     $$\                           
$$  __$$\                           $$  __$$\ $$ |\__|                                                             $$ |                                                    $$ |                          
$$ /  \__| $$$$$$\   $$$$$$$\       $$ /  \__|$$ |$$\ $$$$$$\$$$$\   $$$$$$$\        $$$$$$$\  $$$$$$\  $$$$$$$\ $$$$$$\          $$$$$$\   $$$$$$\   $$$$$$$\        $$$$$$$ | $$$$$$\   $$$$$$$\       
$$ |      $$  __$$\ $$  _____|      $$$$\     $$ |$$ |$$  _$$  _$$\ $$  _____|      $$  _____|$$  __$$\ $$  __$$\\_$$  _|        $$  __$$\  \____$$\ $$  _____|      $$  __$$ |$$  __$$\ $$  _____|      
$$ |      $$$$$$$$ |\$$$$$$\        $$  _|    $$ |$$ |$$ / $$ / $$ |\$$$$$$\        \$$$$$$\  $$ /  $$ |$$ |  $$ | $$ |          $$ /  $$ | $$$$$$$ |\$$$$$$\        $$ /  $$ |$$$$$$$$ |\$$$$$$\        
$$ |  $$\ $$   ____| \____$$\       $$ |      $$ |$$ |$$ | $$ | $$ | \____$$\        \____$$\ $$ |  $$ |$$ |  $$ | $$ |$$\       $$ |  $$ |$$  __$$ | \____$$\       $$ |  $$ |$$   ____| \____$$\       
\$$$$$$  |\$$$$$$$\ $$$$$$$  |      $$ |      $$ |$$ |$$ | $$ | $$ |$$$$$$$  |      $$$$$$$  |\$$$$$$  |$$ |  $$ | \$$$$  |      $$$$$$$  |\$$$$$$$ |$$$$$$$  |      \$$$$$$$ |\$$$$$$$\ $$$$$$$  |      
 \______/  \_______|\_______/       \__|      \__|\__|\__| \__| \__|\_______/       \_______/  \______/ \__|  \__|  \____/       $$  ____/  \_______|\_______/        \_______| \_______|\_______/       
                                                                                                                                 $$ |                                                                    
                                                                                                                                 $$ |                                                                    
                                                                                                                                 \__|                                                                    
 $$$$$$\  $$\ $$\                                                                   $$\                                               $$\ $$\                                                            
$$  __$$\ $$ |\__|                                                                  $$ |                                              $$ |\__|                                                           
$$ /  \__|$$ |$$\ $$$$$$\$$$$\   $$$$$$$\        $$$$$$$\ $$\   $$\  $$$$$$\        $$ | $$$$$$\         $$$$$$$\ $$\   $$\  $$$$$$$\ $$ |$$\  $$$$$$$\ $$$$$$\$$$$\   $$$$$$\                           
$$$$\     $$ |$$ |$$  _$$  _$$\ $$  _____|      $$  _____|$$ |  $$ |$$  __$$\       $$ |$$  __$$\       $$  _____|$$ |  $$ |$$  _____|$$ |$$ |$$  _____|$$  _$$  _$$\ $$  __$$\                          
$$  _|    $$ |$$ |$$ / $$ / $$ |\$$$$$$\        \$$$$$$\  $$ |  $$ |$$ |  \__|      $$ |$$$$$$$$ |      $$ /      $$ |  $$ |$$ /      $$ |$$ |\$$$$$$\  $$ / $$ / $$ |$$$$$$$$ |                         
$$ |      $$ |$$ |$$ | $$ | $$ | \____$$\        \____$$\ $$ |  $$ |$$ |            $$ |$$   ____|      $$ |      $$ |  $$ |$$ |      $$ |$$ | \____$$\ $$ | $$ | $$ |$$   ____|                         
$$ |      $$ |$$ |$$ | $$ | $$ |$$$$$$$  |      $$$$$$$  |\$$$$$$  |$$ |            $$ |\$$$$$$$\       \$$$$$$$\ \$$$$$$$ |\$$$$$$$\ $$ |$$ |$$$$$$$  |$$ | $$ | $$ |\$$$$$$$\                          
\__|      \__|\__|\__| \__| \__|\_______/       \_______/  \______/ \__|            \__| \_______|       \_______| \____$$ | \_______|\__|\__|\_______/ \__| \__| \__| \_______|                         
                                                                                                                  $$\   $$ |                                                                             
                                                                                                                  \$$$$$$  |                                                                             
                                                                                                                   \______/                                                                              

