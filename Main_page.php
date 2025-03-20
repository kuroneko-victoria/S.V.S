<?php
session_start();

// Jeśli użytkownik nie jest zalogowany, przekieruj do strony logowania
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// Obsługa wylogowania
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: Login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "SVS");


if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Obsługa lajkowania postów
if (isset($_POST['like'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id']; // ID zalogowanego użytkownika

    // Sprawdź, czy użytkownik już polubił post
    $check_like_sql = "SELECT * FROM likes WHERE user_id = $user_id AND post_id = $post_id";
    $check_like_result = $conn->query($check_like_sql);

    if ($check_like_result->num_rows === 0) {
        // Dodaj lajk do bazy danych
        $like_sql = "INSERT INTO likes (user_id, post_id) VALUES ($user_id, $post_id)";
        $conn->query($like_sql);
    }
}

// Pobranie postów, autorów, zdjęć profilowych i liczby lajków
$sql = "SELECT posts.*, users.login AS author, users.profile_image AS author_image, 
        (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count 
        FROM posts 
        JOIN users ON posts.author_id = users.id";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posty</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <div class="post-container">
        <header>
            <h1>Witamy, <?php echo $_SESSION['user']; ?>!</h1>
            <div class="button-group">
                <!-- Przycisk "Dodaj nowy post" -->
                <form action="Create.php" method="get">
                    <button type="submit" class="action-button">Dodaj nowy post</button>
                </form>
                <!-- Przycisk "Wyloguj" -->
                <form action="" method="get">
                    <button type="submit" name="logout" value="true" class="action-button logout">Wyloguj</button>
                </form>
            </div>
            <div class="theme-toggle">
                <button id="theme-toggle-button" class="theme-button">Włącz Night Mode</button>
            </div>
        </header>
        <h2>Posty</h2>
        <div class="posts-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="post-card">';
                    echo '<h3>' . $row['title'] . '</h3>';
                    echo '<p>' . $row['content'] . '</p>';
                    if (!empty($row['image_url'])) {
                        echo '<img src="' . $row['image_url'] . '" alt="Post Image" />';
                    }
                    echo '<p>';
                    if (!empty($row['author_image'])) {
                        echo '<img src="' . $row['author_image'] . '" alt="Zdjęcie autora" class="author-image" />';
                    }
                    echo '<a href="profile.php?user=' . $row['author'] . '">' . $row['author'] . '</a></p>';
                    // Wyświetlanie liczby lajków
                    echo '<div class="post-likes">';
                    echo '<span class="like-count">❤️ ' . $row['like_count'] . ' lajków</span>';
                    echo '</div>';
                    // Formularz do lajkowania postu    
                    echo '<form action="" method="post">';
                    echo '<input type="hidden" name="post_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" name="like" class="like-button">Polub</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "Brak postów do wyświetlenia.";
            }
            ?>
        </div>
    </div>
</body>
</html>

<script>
    // Pobierz element przycisku i body
    const themeToggleButton = document.getElementById('theme-toggle-button');
    const body = document.body;

    // Sprawdź zapisany tryb w localStorage
    const savedTheme = localStorage.getItem('theme');

    // Jeśli zapisano tryb nocny, zastosuj go
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        themeToggleButton.textContent = 'Wyłącz Night Mode';
    }

    // Obsługa kliknięcia przycisku
    themeToggleButton.addEventListener('click', () => {
        if (body.classList.contains('dark-mode')) {
            // Przełącz na tryb jasny
            body.classList.remove('dark-mode');
            themeToggleButton.textContent = 'Włącz Night Mode';
            localStorage.setItem('theme', 'light'); // Zapisz preferencje
        } else {
            // Przełącz na tryb nocny
            body.classList.add('dark-mode');
            themeToggleButton.textContent = 'Wyłącz Night Mode';
            localStorage.setItem('theme', 'dark'); // Zapisz preferencje
        }
    });
</script>
