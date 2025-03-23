<?php
$apiKey = '7bc8178ce7b44f09be37ecdfbb37441b';
$randomUserApi = 'https://randomuser.me/api/';
$newsApiUrl = "https://newsapi.org/v2/top-headlines?country=us&category=general&apiKey=$apiKey";

// Obtener datos de la API
function fetchData($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}


// Obtener noticias
$newsData = fetchData($newsApiUrl);

// Configurar paginación
$articlesPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalArticles = isset($newsData['articles']) ? count($newsData['articles']) : 0;
$totalPages = $totalArticles > 0 ? ceil($totalArticles / $articlesPerPage) : 1;
$startIndex = ($page - 1) * $articlesPerPage;
$articles = $totalArticles > 0 ? array_slice($newsData['articles'], $startIndex, $articlesPerPage) : [];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./src/css/index-styles.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Últimas Noticias</h1>
        <div class="row justify-content-between">

            <?php if ($totalArticles > 0): ?>
                <?php foreach ($articles as $article): ?>
                    <?php $userData = fetchData($randomUserApi); ?>

                    <div class="col-md-4 card mb-4">
                        <div class="card-body">
                            <img src="<?php echo isset($article['urlToImage']) ? htmlspecialchars($article['urlToImage']) : 'Título no disponible'; ?>" class="card-img" alt="">
                            <h5 class="card-title"><?php echo isset($article['title']) ? htmlspecialchars($article['title']) : 'Título no disponible'; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted color-primary">
                                Por <?php echo isset($userData['results'][0]['name']['first']) ? htmlspecialchars($userData['results'][0]['name']['first'] . ' ' . $userData['results'][0]['name']['last']) : 'Autor desconocido'; ?>
                            </h6>
                            <p class="card-text"><?php echo isset($article['description']) ? htmlspecialchars($article['description']) : 'Descripción no disponible'; ?></p>
                            <a href="<?php echo isset($article['url']) ? htmlspecialchars($article['url']) : '#'; ?>" class="btn btn-primary" target="_blank">Leer más</a>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No hay noticias disponibles en este momento.</p>
            <?php endif; ?>
        </div>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"> <?php echo $i; ?> </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <script src="./src/js/index.js"></script>
</body>

</html>