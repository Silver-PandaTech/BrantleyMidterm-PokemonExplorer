<?php
    $currentPage = "details";
    require "_top.php";

    // Fetch helper
     
    function fetchAPI($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    // Get ID from URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 1;

    // Main Pokémon data
     
    $pokemon = fetchAPI("https://pokeapi.co/api/v2/pokemon/$id");

    // Image fallback logic
     
    $image =
        $pokemon['sprites']['other']['official-artwork']['front_default']
        ?? $pokemon['sprites']['front_default']
        ?? "images/no-image.png";

    // Type colors
     
    function typeColor($type) {
        return match($type) {
            'fire' => 'bg-danger',
            'water' => 'bg-primary',
            'grass' => 'bg-success',
            'electric' => 'bg-warning text-dark',
            'psychic' => 'bg-info text-dark',
            'ice' => 'bg-info',
            'dragon' => 'bg-primary',
            'dark' => 'bg-dark',
            'fairy' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    // Type URL for similar Pokémon
     
    $typeURL = $pokemon['types'][0]['type']['url'];
    $typeData = fetchAPI($typeURL);

    // Build similar Pokémon list
    $similar = [];
    $shown = 0;

    foreach ($typeData['pokemon'] as $p) {

        if ($shown >= 6) break;

        $urlParts = explode('/', rtrim($p['pokemon']['url'], '/'));
        $pokeId = end($urlParts);

        if ($pokeId == $id) continue;

        $pokeData = fetchAPI("https://pokeapi.co/api/v2/pokemon/$pokeId");

        $similar[] = $pokeData;
        $shown++;
    }
?>

<div class="container my-5">
    <div class="row">
        <!-- LEFT SIDE -->
         <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="<?= $image ?>"
                        class="img-fluid"
                        alt="<?= $pokemon['name'] ?>"
                        onerror="this.src='images/no-image.png'">
                    <h2 class="text-capitalize mt-3"><?= $pokemon['name'] ?></h2>
                    <h5>#<?= $pokemon['id'] ?></h5>

                    <!-- TYPES -->
                    <div class="mb-3">
                        <?php foreach ($pokemon['types'] as $type): ?>
                            <span class="badge <?= typeColor($type['type']['name']) ?> text-capitalize"><?= $type['type']['name'] ?></span>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <table class="table">
                        <tr>
                            <th>Height</th>
                                <td><?= $pokemon['height'] ?></td>
                            </tr>
                            <tr>
                                <th>Weight</th>
                                    <td><?= $pokemon['weight'] ?></td>
                                </tr>
                                <tr>
                                    <th>Base XP</th>
                                        <td><?= $pokemon['base_experience'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- RIGHT SIDE -->
                     <div class="col-lg-8">

                        <!-- STATS -->
                         <div class="card mb-4">
                            <div class="card-header">
                                <h4>Base Stats</h4>
                            </div>
                            <div class="card-body">
                                <?php foreach ($pokemon['stats'] as $stat): ?>
                                        <p class="text-capitalize mb-1">
                                                <?= $stat['stat']['name'] ?> (<?= $stat['base_stat'] ?>)
                                        </p>
                                        <div class="progress mb-3">
                                                <div class="progress-bar bg-success"
                                                style="width: <?= min($stat['base_stat'], 100) ?>%">
                                                </div>
                                        </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- ABILITIES -->
                         <div class="card mb-4">
                            <div class="card-header">
                                <h4>Abilities</h4>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <?php foreach ($pokemon['abilities'] as $ability): ?>
                                        <li class="text-capitalize">
                                            <?= $ability['ability']['name'] ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- MOVES -->
                         <div class="card mb-4">
                            <div class="card-header">
                                <h4>Moves</h4>
                            </div>
                            <div class="card-body" style="max-height:350px; overflow:auto;">
                                <ul>
                                    <?php foreach ($pokemon['moves'] as $move): ?>
                                        <li class="text-capitalize">
                                            <?= $move['move']['name'] ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <!-- SPRITES + SIMILAR -->
                 <div class="row mt-5">
                    
                    <!-- SPRITES -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Sprites</h4>
                            </div>
                        <div class="card-body text-center">
                            <?php
                                $sprites = [
                                $pokemon['sprites']['front_default'],
                                $pokemon['sprites']['back_default'],
                                $pokemon['sprites']['front_shiny'],
                                $pokemon['sprites']['back_shiny']
                                ];
                            ?>
                            <?php foreach ($sprites as $sprite): ?>
                                <img class="sprite m-2"
                                    src="<?= $sprite ?? 'images/no-image.png' ?>"
                                    onerror="this.src='images/no-image.png'">
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- SIMILAR -->
                 <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Similar Pokémon</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php 
                                    foreach ($similar as $p): ?>
                                <?php
                                $img = $p['sprites']['front_default'] ?? "images/no-image.png";
                                ?>
                                <div class="col-4 text-center mb-3 similar">
                                    <a href="details.php?id=<?= $p['id'] ?>" class="text-decoration-none">
                                        <img src="<?= $img ?>" style="width:80px;">
                                        <p class="text-capitalize">
                                            <?= $p['name'] ?>
                                        </p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require "_bottom.php"; ?>