<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Explorer | Home</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="style.css" rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            Pokémon Explorer
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'home') echo 'active'; ?>" href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'browse') echo 'active'; ?>" href="browse.php">
                        Browse Pokémon
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if($currentPage == 'learn') echo 'active'; ?>" href="learn.php">
                        Learn
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>