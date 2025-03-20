<?php
session_start();
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light'; // Domyślnie jasny tryb
}

// Obsługa przełączania trybu
if (isset($_GET['theme'])) {
    $_SESSION['theme'] = $_GET['theme']; // Zapis wyboru użytkownika
}
$conn = new mysqli("localhost", "root", "", "SVS");

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie loginu użytkownika z parametru URL
$user_login = isset($_GET['user']) ? $_GET['user'] : '';
$sql = "SELECT id, login, created_at, profile_image, bio FROM users WHERE login='$user_login'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("Użytkownik nie znaleziony.");
}

// Sprawdzenie, czy aktualnie zalogowany użytkownik to właściciel profilu
$is_owner = isset($_SESSION['user']) && $_SESSION['user'] === $user_login;

// Pobranie postów użytkownika
$sql_posts = "SELECT title, content, image_url FROM posts WHERE author_id = " . intval($user['id']);
$result_posts = $conn->query($sql_posts);

if (!$result_posts) {
    die("Błąd w zapytaniu SQL dotyczącym postów: " . $conn->error);
}

// Obsługa zmiany bio
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_owner && isset($_POST['bio'])) {
    $new_bio = $conn->real_escape_string($_POST['bio']);
    $update_bio_sql = "UPDATE users SET bio='$new_bio' WHERE id=" . $user['id'];
    if ($conn->query($update_bio_sql) === TRUE) {
        header("Location: profile.php?user=" . $user['login']);
        exit();
    } else {
        echo "Błąd podczas aktualizacji bio.";
    }
}

// Obsługa zmiany zdjęcia profilowego
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_owner && isset($_FILES['profile_image'])) {
    $target_dir = "zdjęcia/profilowe/";
    $image_name = "profil_" . $user['id'] . "_" . basename($_FILES['profile_image']['name']);
    $target_file = $target_dir . $image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES['profile_image']['tmp_name']);

    if ($check !== false) {
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $update_sql = "UPDATE users SET profile_image='$target_file' WHERE id=" . $user['id'];
                if ($conn->query($update_sql) === TRUE) {
                    header("Location: profile.php?user=" . $user['login']);
                    exit();
                } else {
                    echo "Błąd podczas aktualizacji zdjęcia w bazie danych.";
                }
            } else {
                echo "Błąd podczas przesyłania pliku.";
            }
        } else {
            echo "Dozwolone formaty plików to JPG, JPEG, PNG i GIF.";
        }
    } else {
        echo "Przesłany plik nie jest obrazem.";
    }

}
// Pobranie łącznej liczby lajków użytkownika
$sql_likes = "SELECT COUNT(*) as total_likes FROM likes 
              JOIN posts ON likes.post_id = posts.id 
              WHERE posts.author_id = " . intval($user['id']);
$result_likes = $conn->query($sql_likes);

$total_likes = 0; // Domyślnie 0, jeśli brak lajków
if ($result_likes && $result_likes->num_rows > 0) {
    $likes_data = $result_likes->fetch_assoc();
    $total_likes = $likes_data['total_likes'];

// Ustalanie odpowiedniej aury
if ($total_likes < 10) {
    $auraImage = 'Zdjecia/aura1.png';
    $borderColor = 'red';
    $auraText = 'Low Aura';
} elseif ($total_likes >= 10 && $total_likes < 30) {
    $auraImage = 'Zdjecia/aura2.png';
    $borderColor = 'orange';
    $auraText = 'Mid Aura';
} elseif ($total_likes >= 30 && $total_likes < 70) {
    $auraImage = 'Zdjecia/aura3.png';
    $borderColor = 'yellow';
    $auraText = 'Premium Mid Aura';
} elseif ($total_likes >= 70 && $total_likes < 100) {
    $auraImage = 'Zdjecia/aura5.png';
    $borderColor = 'green';
    $auraText = 'Aura 100';
} elseif ($total_likes >= 100 && $total_likes < 300) {
    $auraImage = 'Zdjecia/aura6.png';
    $borderColor = 'blue';
    $auraText = 'Sigmaaaa';
} elseif ($total_likes >= 300 && $total_likes < 1000) {
    $auraImage = 'Zdjecia/aura7.png';
    $borderColor = 'pink';
    $auraText = 'Auraaaa 10000 Slay';
} else {
    $auraImage = 'Zdjecia/default.png';
    $borderColor = 'gray';
    $auraText = 'Legendary Aura';
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil użytkownika</title>
    <link rel="stylesheet" href="stylp.css">
</head>
<body class="<?php echo $_SESSION['theme'] === 'dark' ? 'dark-mode' : ''; ?>">
    
    <div class="profile-container">
        <div class="profile-info">
            <h1>Profil użytkownika</h1>
            <p>Login: <?php echo $user['login']; ?></p>
            <p>Data rejestracji: <?php echo $user['created_at']; ?></p>
        </div>
        <img src="<?php echo !empty($user['profile_image']) ? $user['profile_image'] : 'zdjęcia/default.jpg'; ?>" alt="Zdjęcie profilowe" class="profile-picture">
        <?php if ($is_owner): ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="profile_image">Zmień zdjęcie profilowe:</label>
                <input type="file" name="profile_image" id="profile_image" required>
                <button type="submit" class="upload-button">Zmień zdjęcie</button>
            </form>
        <?php else: ?>
            <p>Zmiana zdjęcia możliwa tylko dla właściciela profilu.</p>
        <?php endif; ?>

        <!-- Sekcja Bio -->
        <div class="bio-section">
            <h2>Bio:</h2>
            <?php if ($is_owner): ?>
                <form action="" method="POST">
                    <textarea name="bio" rows="4" placeholder="Dodaj opis..."><?php echo htmlspecialchars($user['bio']); ?></textarea>
                    <button type="submit" class="save-bio-button">Zapisz Bio</button>
                </form>
            <?php else: ?>
                <p><?php echo !empty($user['bio']) ? htmlspecialchars($user['bio']) : "Brak opisu."; ?></p>
            <?php endif; ?>
        </div>
        <div class="user-likes">
            <h2>Łączna liczba lajków:</h2>
            <p><?php echo $total_likes; ?> lajków</p>
        </div>
        <!-- Przycisk powrotu do Main Page -->
        <div class="return-button">
            <form action="Main_page.php" method="get">
                <button type="submit" class="btn">Powrót do Main Page</button>
            </form>
        </div>
    </div>

    <div class="user-posts">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Posty użytkownika</h2>
        <div class="theme-toggle">
            <?php if ($_SESSION['theme'] === 'light'): ?>
                <a href="?theme=dark" class="theme-button">Włącz Night Mode</a>
            <?php else: ?>
                <a href="?theme=light" class="theme-button">Wyłącz Night Mode</a>
            <?php endif; ?>
        </div>
    </div>

    <?php
    if ($result_posts->num_rows > 0) {
        while ($post = $result_posts->fetch_assoc()) {
            echo '<div class="post-card">';
            echo '<h3>' . $post['title'] . '</h3>';
            echo '<p>' . $post['content'] . '</p>';
            if (!empty($post['image_url'])) {
                echo '<img src="' . $post['image_url'] . '" alt="Obrazek posta">';
            }
            echo '</div>';
        }
    } else {
        echo "<p>Ten użytkownik jeszcze nic nie opublikował.</p>";
    }
    ?>
</div>
</body>
</html>
<script>
    // Pobierz przełącznik i element body
    const themeToggle = document.querySelector('.theme-button');
    const body = document.body;

    // Sprawdź lokalne ustawienie trybu (localStorage)
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode'); // Ustaw tryb nocny
    }

    // Obsługa kliknięcia przełącznika
    themeToggle.addEventListener('click', (e) => {
        e.preventDefault(); // Zablokuj przeładowanie strony
        if (body.classList.contains('dark-mode')) {
            body.classList.remove('dark-mode'); // Usuń tryb nocny
            localStorage.setItem('theme', 'light'); // Zapisz jasny tryb
        } else {
            body.classList.add('dark-mode'); // Włącz tryb nocny
            localStorage.setItem('theme', 'dark'); // Zapisz tryb nocny
        }
    });
</script><?php
