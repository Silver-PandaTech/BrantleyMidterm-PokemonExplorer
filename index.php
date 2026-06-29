<?php
    $currentPage = "home";
    require "_top.php";

    // Fetch data from PokéAPI
    function fetchAPI($url) {

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }

    // Load API data
    $pokemonList   = fetchAPI("https://pokeapi.co/api/v2/pokemon?limit=1");
    $typeList      = fetchAPI("https://pokeapi.co/api/v2/type");
    $moveList      = fetchAPI("https://pokeapi.co/api/v2/move?limit=1");
    $abilityList   = fetchAPI("https://pokeapi.co/api/v2/ability?limit=1");
    $genList       = fetchAPI("https://pokeapi.co/api/v2/generation");
    $regionList    = fetchAPI("https://pokeapi.co/api/v2/region");


    // Generate featured Pokémon

    $featured = [];

    for ($i = 0; $i < 6; $i++) {

        $id = rand(1, 1025);

        $pokemon = fetchAPI("https://pokeapi.co/api/v2/pokemon/$id");

        $image =
            $pokemon['sprites']['other']['official-artwork']['front_default']
            ?? $pokemon['sprites']['front_default']
            ?? "images/no-image.png";

        $featured[] = $pokemon;
    }
?>

<!-- HERO -->
<section class="hero">
    <div class="container text-center">
        <h1 class="display-3 fw-bold">Pokémon Explorer</h1>
        <p class="lead">
            A PokéAPI powered website to browse any Pokémon you would like!
        </p>
    </div>
</section>

<div class="container mt-5">

    <!-- STATS -->
    <div class="row text-center">

        <div class="col-md-2 mb-4">
            <div class="card stat-card p-3">
                <h2><?= $pokemonList['count'] ?></h2>
                <small>Pokémon</small>
            </div>
        </div>

        <div class="col-md-2 mb-4">
            <div class="card stat-card p-3">
                <h2><?= $typeList['count'] ?></h2>
                <small>Types</small>
            </div>
        </div>

        <div class="col-md-2 mb-4">
            <div class="card stat-card p-3">
                <h2><?= $moveList['count'] ?></h2>
                <small>Moves</small>
            </div>
        </div>

        <div class="col-md-2 mb-4">
            <div class="card stat-card p-3">
                <h2><?= $abilityList['count'] ?></h2>
                <small>Abilities</small>
            </div>
        </div>

        <div class="col-md-2 mb-4">
            <div class="card stat-card p-3">
                <h2><?= $genList['count'] ?></h2>
                <small>Generations</small>
            </div>
        </div>

        <div class="col-md-2 mb-4">
            <div class="card stat-card p-3">
                <h2><?= $regionList['count'] ?></h2>
                <small>Regions</small>
            </div>
        </div>

    </div>

    <hr class="my-5">

    <!-- INFO -->
    <div class="row">

        <div class="col-lg-6">

            <h2>Welcome!</h2>

            <p>
                Pokémon Explorer is an interactive website built using the free <strong>PokéAPI</strong>.
                Every Pokémon displayed throughout the site is retrieved live from the API, 
                ensuring the information is always up-to-date.
            </p>

            <p>
                Use this website to browse every Pokémon, 
                explore detailed statistics, 
                discover evolution chains, 
                and learn more about the Pokémon universe. 
                <strong>However</strong>, not all Pokémon have images available!
            </p>

        </div>

        <div class="col-lg-6">

            <h2>API Facts</h2>

            <ul>
                <li>Over 1,000 Pokémon available</li>
                <li>Includes stats, moves, abilities</li>
                <li>Free REST API</li>
                <li>Used globally by developers</li>
            </ul>

        </div>

    </div>

    <hr class="my-5">

    <!-- TYPES -->
    <h2>Pokémon Types</h2>

    <div class="mt-3">

        <?php foreach ($typeList['results'] as $type): ?>
            <span class="badge bg-primary m-1 text-capitalize">
                <?= $type['name'] ?>
            </span>
        <?php endforeach; ?>

    </div>

    <hr class="my-5">

    <!-- FEATURED -->
    <h2>Featured Pokémon</h2>

    <div class="row mt-3">

        <?php foreach ($featured as $pokemon): ?>

            <?php
                $img =
                    $pokemon['sprites']['other']['official-artwork']['front_default']
                    ?? $pokemon['sprites']['front_default']
                    ?? "images/no-image.png";
            ?>

            <div class="col-md-4 col-lg-2 mb-4">

                <div class="card text-center h-100">

                    <div class="card-body">

                        <img
                            src="<?= $img ?>"
                            alt="<?= $pokemon['name'] ?>"
                            onerror="this.src='images/no-image.png'"
                            style="width:100px;height:100px;"
                        >

                        <h5 class="text-capitalize mt-2">
                            <?= $pokemon['name'] ?>
                        </h5>

                        <a href="details.php?id=<?= $pokemon['id'] ?>"
                           class="btn btn-danger btn-sm mt-2">
                            View Details
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<?php require "_bottom.php"; ?>