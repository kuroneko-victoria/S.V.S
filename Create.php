<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj nowy post</title>
    <link rel="stylesheet" href="styl2.css">
</head>
<body>
    <?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: Login.php");
        exit();
    }
    ?>

    <form action="Create.php" method="POST" enctype="multipart/form-data">
        <h2>Dodaj nowy post</h2>
        <label for="title">Tytuł:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">Treść:</label><br>
        <textarea id="content" name="content" rows="4" required></textarea><br><br>

        <label for="image">Dodaj obrazek (opcjonalne):</label><br>
        <input type="file" id="image" name="image"><br><br>

        <input type="submit" value="Dodaj post">
    </form>

    <?php
    $conn = new mysqli("localhost", "root", "", "SVS");

    if (!$conn) {
        die("Połączenie nieudane: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $image_url = '';
        $author_id = $_SESSION['user_id'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "zdjęcia/";
            $target_file = $target_dir . basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES['image']['tmp_name']);

            if ($check !== false) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_url = $target_file;
                } else {
                    echo "<p>Błąd podczas przesyłania pliku.</p>";
                }
            } else {
                echo "<p>Plik nie jest obrazem.</p>";
            }
        }

        $sql = "INSERT INTO posts (title, content, image_url, author_id) VALUES ('$title', '$content', '$image_url', '$author_id')";

        if (mysqli_query($conn, $sql)) {
            echo "<p>Post dodany pomyślnie!</p>";
            header("Location: Main_page.php");
            exit();
        } else {
            echo "<p>Błąd: " . mysqli_error($conn) . "</p>";
        }
    }

    mysqli_close($conn);
    ?>
</body>
</html>
