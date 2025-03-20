<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login i Rejestracja</title>
    <link rel="stylesheet" href="styllog.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Logowanie</h2>
            <form action="" method="POST">
                <label for="login">Login:</label>
                <input type="text" name="login" required>
                <label for="password">Hasło:</label>
                <input type="password" name="password" required>
                <button type="submit" name="login_user">Zaloguj</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Rejestracja</h2>
            <form action="" method="POST">
                <label for="login">Login:</label>
                <input type="text" name="login" required>
                <label for="password">Hasło:</label>
                <input type="password" name="password" required>
                <button type="submit" name="register">Zarejestruj</button>
            </form>
        </div>
    </div>
    <?php
        session_start();
        
        $conn = new mysqli("localhost", "root", "", "SVS");


        if ($conn->connect_error) {
            die("Błąd połączenia: " . $conn->connect_error);
        }

        // Rejestracja użytkownika
        if (isset($_POST['register'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "SELECT * FROM users WHERE login='$login'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "Użytkownik o tym loginie już istnieje!";
            } else {
                $sql = "INSERT INTO users (login, password) VALUES ('$login', '$hashed_password')";
                if ($conn->query($sql) === TRUE) {
                    echo "Rejestracja udana!";
                } else {
                    echo "Błąd podczas rejestracji: " . $conn->error;
                }
            }
        }

        // Logowanie użytkownika
        if (isset($_POST['login_user'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM users WHERE login='$login'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user'] = $row['login'];
                    $_SESSION['user_id'] = $row['id'];
                    header("Location: Main_page.php");
                    exit();
                } else {
                    echo "Nieprawidłowe hasło!";
                }
            } else {
                echo "Nie znaleziono użytkownika o podanym loginie!";
            }
        }

        $conn->close();
    ?>
</body>
</html>
