<?php
session_start();

// ====== CONFIGURATION ======
// Change this password to whatever you want
$ADMIN_PASS = "TejasMedia123";
$UPLOAD_DIR = "uploads/";
$DATA_FILE = "data.json";

// Ensure directories and files exist
if (!is_dir($UPLOAD_DIR)) {
    mkdir($UPLOAD_DIR, 0777, true);
}
if (!file_exists($DATA_FILE)) {
    file_put_contents($DATA_FILE, json_encode(["videos" => []]));
}

// ====== LOGIN LOGIC ======
if (isset($_POST['login'])) {
    if ($_POST['password'] === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = "Incorrect Password!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Check auth
$loggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// ====== UPLOAD LOGIC (IMAGE) ======
$message = "";
if ($loggedIn && isset($_POST['upload_image'])) {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $tmpNm = $_FILES['photo']['tmp_name'];
        $name = basename($_FILES['photo']['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed)) {
            $newName = uniqid("UP_") . "_" . time() . "." . $ext;
            if (move_uploaded_file($tmpNm, $UPLOAD_DIR . $newName)) {
                $message = "<div style='color:green;'>Image uploaded successfully!</div>";
            } else {
                $message = "<div style='color:red;'>Failed to move file to uploads directory.</div>";
            }
        } else {
            $message = "<div style='color:red;'>Invalid file type! Only JPG, PNG, WEBP allowed.</div>";
        }
    } else {
        $message = "<div style='color:red;'>Please select a valid image.</div>";
    }
}

// ====== ADD VIDEO LINK LOGIC ======
if ($loggedIn && isset($_POST['add_video'])) {
    $videoLink = trim($_POST['video_link']);
    $videoTitle = trim($_POST['video_title']);
    if (!empty($videoLink) && !empty($videoTitle)) {
        $data = json_decode(file_get_contents($DATA_FILE), true);
        array_unshift($data['videos'], [
            "title" => htmlspecialchars($videoTitle),
            "link" => htmlspecialchars($videoLink),
            "added_at" => time()
        ]);
        file_put_contents($DATA_FILE, json_encode($data));
        $message = "<div style='color:green;'>Video link added successfully!</div>";
    } else {
        $message = "<div style='color:red;'>Please fill both title and link!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Tejas</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { padding-top: 6rem; text-align:center; }
        .admin-panel { max-width: 500px; margin: 0 auto; background: var(--color-surface); padding: 2rem; border-radius: 8px; border: 1px solid var(--color-accent); }
        .input-group { margin-bottom: 1.5rem; text-align: left; }
        .input-group label { display: block; margin-bottom: 0.5rem; color: var(--color-accent); }
        .input-group input { width: 100%; padding: 0.8rem; border-radius: 4px; border: 1px solid #333; background: #111; color: #fff; }
        hr { border: 0; border-top: 1px solid #333; margin: 2rem 0; }
    </style>
</head>
<body>

    <nav class="navbar scrolled">
        <div class="logo">TEJAS admin</div>
        <ul class="nav-links">
            <li><a href="index.html">Back to Site</a></li>
            <?php if($loggedIn): ?>
            <li><a href="media.php">View Media Page</a></li>
            <li><a href="?logout=true" style="color:red;">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container reveal active">
        <?php if (!$loggedIn): ?>
            <!-- LOGIN FORM -->
            <div class="admin-panel">
                <h2 style="margin-bottom:1rem;">Admin Login</h2>
                <?php if(isset($login_error)) echo "<p style='color:red;'>$login_error</p>"; ?>
                <form method="POST">
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </form>
            </div>
        <?php else: ?>
            <!-- DASHBOARD -->
            <div class="admin-panel">
                <h2>Media Uploader Panel</h2>
                <?php echo $message; ?>
                
                <hr>
                
                <h3>Upload New Photo</h3>
                <form method="POST" enctype="multipart/form-data" style="margin-top:1rem;">
                    <div class="input-group">
                        <input type="file" name="photo" accept="image/*" required>
                    </div>
                    <button type="submit" name="upload_image" class="btn btn-outline" style="width:100%;">Upload Photo</button>
                </form>

                <hr>

                <h3>Add Video Link</h3>
                <form method="POST" style="margin-top:1rem;">
                    <div class="input-group">
                        <label>Video Title</label>
                        <input type="text" name="video_title" placeholder="e.g. My new short film" required>
                    </div>
                    <div class="input-group">
                        <label>Video URL (YouTube/Vimeo)</label>
                        <input type="url" name="video_link" placeholder="https://youtube.com/watch?v=..." required>
                    </div>
                    <button type="submit" name="add_video" class="btn btn-outline" style="width:100%;">Add Video</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
