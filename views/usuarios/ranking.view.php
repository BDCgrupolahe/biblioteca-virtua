<?php require('views/headervertical.view.php'); ?>

<body>
    <div id="leaderboards">
        <ul class="toplist">
        </ul>
    </div>
</body>

<!-- <body>
    <div id="leaderboards">
        <div class="options">
            <input type="text" class="search" placeholder="Buscar por el titulo..." />
            <i></i>
            <div class="sort">
                <h2>Categorias</h2>
                <div class="tabTitles">
                    <span id="bedwars" class="active">Filtros</span>
                    <span id="ffa">Categorias</span>
                </div>
                <form class="tabContents">
                    <li class="tab bedwars active">
                        <input checked name="sort" id="q" type="radio" value="bedwars_wins" />
                        <label for="q">Todas</label>
                        <input name="sort" id="w" type="radio" value="bedwars_games" />
                        <label for="w">Dia</label>
                        <input name="sort" id="e" type="radio" value="bedwars_destroyed" />
                        <label for="e">Semana</label>
                        <input name="sort" id="r" type="radio" value="bedwars_kills" />
                        <label for="r">Mes</label>
                    </li>
                    <li class="tab ffa">
                        <input name="sort" id="y" type="radio" value="ffa_wins" />
                        <label for="y">Wins</label>
                        <input name="sort" id="u" type="radio" value="ffa_kills" />
                        <label for="u">Kills</label>
                        <input name="sort" id="i" type="radio" value="ffa_deaths" />
                        <label for="i">Deaths</label>
                    </li>
                </form>
            </div>  
        </div>
        <ul class="toplist">
            <li data-rank="1">
                <div class="thumb">
                    <span class="img" data-name="BluewaveSwift"></span>
                    <span class="name">BluewaveSwift</span>
                    <span class="stat"><b>4304</b>Wins</span>
                </div>
                <div class="more"> -->
                    <!-- To be designed & implemented -->
                <!-- </div>
            </li>
            <li data-rank="2">
                <div class="thumb">
                    <span class="img" data-name="BluewaveSwift"></span>
                    <span class="name">BluewaveSwift</span>
                    <span class="stat"><b>4304</b>Wins</span>
                </div>
                <div class="more"> -->
                    <!-- To be designed & implemented -->
                <!-- </div>
            </li>
            <li data-rank="3">
                <div class="thumb">
                    <span class="img" data-name="BluewaveSwift"></span>
                    <span class="name">BluewaveSwift</span>
                    <span class="stat"><b>4304</b>Wins</span>
                </div>
                <div class="more"> -->
                    <!-- To be designed & implemented -->
                <!-- </div>
            </li>
            <li data-rank="4">
                <div class="thumb">
                    <span class="img" data-name="BluewaveSwift"></span>
                    <span class="name">BluewaveSwift</span>
                    <span class="stat"><b>4304</b>Wins</span>
                </div>
                <div class="more"> -->
                    <!-- To be designed & implemented -->
                <!-- </div>
            </li>
            <li data-rank="5">
                <div class="thumb">
                    <span class="img" data-name="BluewaveSwift"></span>
                    <span class="name">BluewaveSwift</span>
                    <span class="stat"><b>4304</b>Wins</span>
                </div>
                <div class="more"> -->
                    <!-- To be designed & implemented -->
                <!-- </div>
            </li>
        </ul>
    </div>
</body> --> 


<?php require('views/footer.view.php'); ?>
<?php require('views/estilos3.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin.ranking.js"></script>