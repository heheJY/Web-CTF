<?php
require_once 'init.php';

if (isset($_GET['id'])) {
    $id = (string)$_GET['id'];

    $pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    try {
        $stmt = $pdo->prepare('SELECT id, username, title, content, likes, published FROM post WHERE id=?');
        $stmt->execute([$id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post === false) {
            error_log("Post not found: $id");
            header('HTTP/1.0 404 Not Found');
            die("Post not found.");
        }
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        die("An error occurred while fetching the post.");
    }
} else {
    header('Location: /posts.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($post['title']) ?> - Blog</title>
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Blog</a>
            <div class="collapse navbar-collapse" id="navbarDropdown">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Log in</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main>
    <section>
        <div class="container">
            <h1 class="mt-4">
                <?php if ($post['published'] === '0') { ?><span class="badge badge-secondary">Secret</span><?php } ?>
                <?= htmlspecialchars($post['title']) ?>
            </h1>
            <span class="text-muted">by <?= htmlspecialchars($post['username']) ?> <span class="badge badge-love badge-pill">♥ <?= $post['likes'] ?></span></span>
            <div class="mt-3">
                <?= render_tags($post['content']) ?>
            </div>
            <div class="mt-3">
                <a href="like.php?id=<?= htmlspecialchars($post['id']) ?>" id="like" class="btn btn-love">♥ Like this post</a>
            </div>
        </div>
    </section>
</main>
<footer>
    <div class="container text-center">
        <span class="text-muted">hehe</span>
    </div>
</footer>
<script src="/static/js/jquery-3.4.1.min.js"></script>
<script src="/static/js/bootstrap.bundle.min.js"></script>
</body>
</html>
