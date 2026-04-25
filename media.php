<?php
// Read video titles from JSON
$DATA_FILE = "data.json";
$videos = [];
if (file_exists($DATA_FILE)) {
    $data = json_decode(file_get_contents($DATA_FILE), true);
    if(isset($data['videos'])) {
        $videos = $data['videos'];
    }
}

// Read images from uploads directory
$UPLOAD_DIR = "uploads/";
$images = [];
if (is_dir($UPLOAD_DIR)) {
    $files = scandir($UPLOAD_DIR);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $images[] = $file;
        }
    }
}
rsort($images); // Show newest first (since names have timestamp via uniqid rules)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Media | Tejas</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .page-header { padding-top: 8rem; padding-bottom: 2rem; text-align: center; }
        
        .gallery-grid {
            column-count: 3;
            column-gap: 1.5rem;
            margin-bottom: 4rem;
        }

        @media (max-width: 900px) { .gallery-grid { column-count: 2; } }
        @media (max-width: 600px) { .gallery-grid { column-count: 1; } }

        .gallery-item {
            break-inside: avoid;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            transform: translateZ(0);
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        .gallery-item:hover img { transform: scale(1.05); }

        .media-link-card {
            background: var(--color-surface);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--color-accent);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .media-link-card h3 { margin: 0; font-family: var(--font-body); font-size: 1.1rem; }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">TEJAS</div>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="portfolio.html">CV & Media</a></li>
            <li><a href="media.php" class="active">Updates</a></li>
            <li><a href="blog.html">Blog</a></li>
        </ul>
    </nav>

    <header class="page-header container reveal active">
        <h1 class="text-accent">Latest Updates & Media</h1>
        <p>A dynamic collection of my latest photos and video links.</p>
        <a href="admin.php" style="font-size:0.8rem; color:var(--color-text-muted); margin-top:1rem; display:inline-block;">[ Admin Login ]</a>
    </header>

    <!-- Uploaded Videos Section -->
    <section class="container reveal active">
        <h2 style="margin-bottom:2rem;">Newest Videos & Links</h2>
        <?php if(empty($videos)): ?>
            <p class="text-muted">No video links added yet.</p>
        <?php else: ?>
            <div style="max-width:800px; margin:0 auto;">
                <?php foreach($videos as $vid): ?>
                <div class="media-link-card">
                    <h3><?= htmlspecialchars($vid['title']) ?></h3>
                    <a href="<?= htmlspecialchars($vid['link']) ?>" target="_blank" class="btn btn-outline" style="padding:0.5rem 1rem; font-size:0.8rem;">Watch Video</a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Uploaded Photos Section -->
    <section class="container reveal active" style="margin-top:4rem;">
        <h2 style="margin-bottom:2rem;">Latest Photo Uploads</h2>
        <?php if(empty($images)): ?>
            <p class="text-muted text-center" style="margin-bottom:10rem;">No extra photos uploaded yet.</p>
        <?php else: ?>
            <div class="gallery-grid">
                <?php foreach($images as $img): ?>
                <div class="gallery-item">
                    <img src="<?= $UPLOAD_DIR . $img ?>" alt="Uploaded Media">
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <script src="js/main.js"></script>
</body>
</html>
