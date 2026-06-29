<?php
    $currentPage = "browse";
    require "_top.php";

    // Fetch helper
    
    function fetchAPI($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    // GET filters from URL
    $search   = $_GET['search'] ?? '';
    $sort     = $_GET['sort'] ?? 'numberAsc';
    $page     = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 20;

    // Load all Pokémon
    
    $data = fetchAPI("https://pokeapi.co/api/v2/pokemon?limit=2000");

    $allPokemon = [];

    foreach ($data['results'] as $index => $p) {

        $id = $index + 1;

        $allPokemon[] = [
            "id" => $id,
            "name" => $p['name'],
            "image" => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/$id.png"
        ];
    }

    // SEARCH
    if ($search !== '') {
        $allPokemon = array_filter($allPokemon, function($p) use ($search) {
            return str_contains($p['name'], strtolower($search));
        });
    }

    // SORT
    usort($allPokemon, function($a, $b) use ($sort) {

        return match($sort) {

            "nameAsc" => strcmp($a['name'], $b['name']),
            "nameDesc" => strcmp($b['name'], $a['name']),
            "numberDesc" => $b['id'] <=> $a['id'],
            default => $a['id'] <=> $b['id'],
        };
    });

    // PAGINATION
    $total = count($allPokemon);
    $totalPages = max(1, ceil($total / $pageSize));

    $page = min($page, $totalPages);

    $offset = ($page - 1) * $pageSize;

    $display = array_slice($allPokemon, $offset, $pageSize);
?>

<div class="container mt-4">

<h1 class="mb-4">Browse Pokémon</h1>

<!-- FILTER FORM -->
<form method="GET" class="row mb-4">

    <div class="col-md-6">
        <input type="text"
               name="search"
               value="<?= htmlspecialchars($search) ?>"
               class="form-control"
               placeholder="Search Pokémon...">
    </div>

    <div class="col-md-3">
        <select name="sort" class="form-select">
            <option value="numberAsc" <?= $sort=='numberAsc'?'selected':'' ?>>
                Number (Ascending)
            </option>
            <option value="numberDesc" <?= $sort=='numberDesc'?'selected':'' ?>>
                Number (Descending)
            </option>
            <option value="nameAsc" <?= $sort=='nameAsc'?'selected':'' ?>>
                Name (A-Z)
            </option>
            <option value="nameDesc" <?= $sort=='nameDesc'?'selected':'' ?>>
                Name (Z-A)
            </option>
        </select>
    </div>

    <div class="col-md-3">
        <select name="pageSize" class="form-select">
            <option value="20" <?= $pageSize==20?'selected':'' ?>>20 per page</option>
            <option value="40" <?= $pageSize==40?'selected':'' ?>>40 per page</option>
            <option value="60" <?= $pageSize==60?'selected':'' ?>>60 per page</option>
            <option value="100" <?= $pageSize==100?'selected':'' ?>>100 per page</option>
        </select>
    </div>

    <div class="col-12 mt-3">
        <button class="btn btn-danger">Apply Filters</button>
    </div>

</form>

<!-- POKEMON GRID -->
<div class="row">

<?php foreach ($display as $pokemon): ?>

<div class="col-lg-3 col-md-4 col-sm-6 mb-4">

    <div class="card h-100">

        <div class="card-body text-center">

            <img src="<?= $pokemon['image'] ?>"
                 onerror="this.src='images/no-image.png'"
                 class="img-fluid">

            <h5 class="text-capitalize mt-3">
                <?= $pokemon['name'] ?>
            </h5>

            <p>#<?= $pokemon['id'] ?></p>

            <a href="details.php?id=<?= $pokemon['id'] ?>"
               class="btn btn-danger">
                View Details
            </a>

        </div>

    </div>

</div>

<?php endforeach; ?>

</div>

<!-- PAGINATION -->
<div class="d-flex justify-content-between mt-4 mb-5">

<?php
$prevPage = max(1, $page - 1);
$nextPage = min($totalPages, $page + 1);

$queryBase = http_build_query([
    'search' => $search,
    'sort' => $sort,
    'pageSize' => $pageSize
]);
?>

<a class="btn btn-secondary"
   href="?<?= $queryBase ?>&page=<?= $prevPage ?>">
    Previous
</a>

<span class="align-self-center fw-bold">
    Page <?= $page ?> of <?= $totalPages ?>
</span>

<a class="btn btn-secondary"
   href="?<?= $queryBase ?>&page=<?= $nextPage ?>">
    Next
</a>

</div>

</div>

<?php require "_bottom.php"; ?>