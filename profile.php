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

// Pobranie liczby lajków użytkownika
$sql_likes = "SELECT COUNT(*) as total_likes FROM likes 
              JOIN posts ON likes.post_id = posts.id 
              WHERE posts.author_id = " . intval($user['id']);
$result_likes = $conn->query($sql_likes);
$total_likes = 0;
if ($result_likes && $result_likes->num_rows > 0) {
    $likes_data = $result_likes->fetch_assoc();
    $total_likes = $likes_data['total_likes'];
}

// Ustalanie odpowiedniej aury
if ($total_likes < 10) {
    $auraImage = 'Zdjecia/aura 0.jpg';
    $borderColor = 'red';
    $auraText = 'Low Aura';
} elseif ($total_likes >= 10 && $total_likes < 30) {
    $auraImage = 'Zdjecia/aura 1.jpg';
    $borderColor = 'orange';
    $auraText = 'Mid Aura';
} elseif ($total_likes >= 30 && $total_likes < 70) {
    $auraImage = 'Zdjecia/aura 3.jpg';
    $borderColor = 'yellow';
    $auraText = 'Premium Mid Aura';
} elseif ($total_likes >= 70 && $total_likes < 100) {
    $auraImage = 'Zdjecia/aura 4.jpg';
    $borderColor = 'green';
    $auraText = 'Aura 100';
} elseif ($total_likes >= 100 && $total_likes < 300) {
    $auraImage = 'Zdjecia/aura 5.jpg';
    $borderColor = 'blue';
    $auraText = 'Sigmaaaa';
} elseif ($total_likes >= 300 && $total_likes < 1000) {
    $auraImage = 'Zdjecia/aura 6.jpg';
    $borderColor = 'pink';
    $auraText = 'Legendary Auuraaaa 10000 Slay';
} else {
    $auraImage = 'Zdjecia/defaultaura.jpg';
    $borderColor = 'gray';
    $auraText = 'default';
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
    <style>
        .aura-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid <?php echo $borderColor; ?>;
            display: block;
            margin: 0 auto;
        }
        .aura-text {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: <?php echo $borderColor; ?>;
        }
    </style>
</head>
<body class="<?php echo $_SESSION['theme'] === 'dark' ? 'dark-mode' : ''; ?>">
    <div class="profile-container">
        <div class="user-likes">
            <img src="<?php echo $auraImage; ?>" alt="Aura" class="aura-image">
            <p class="aura-text"><?php echo $auraText; ?></p>
            <h2>Łączna liczba lajków: <?php echo $total_likes; ?></h2>
        </div>
    </div>
</body>
</html>
