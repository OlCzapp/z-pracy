<?php
$host = 'localhost';
$db   = 'baza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['dodaj'])) {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $conn->query("INSERT INTO users (imie, nazwisko, data_dodania) VALUES ('$imie', '$nazwisko', NOW())");
    header("Location: baza.php");
    exit();
}

if (isset($_POST['zapisz'])) {
    $id = $_POST['id'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $conn->query("UPDATE users SET imie='$imie', nazwisko='$nazwisko' WHERE id=$id");
    header("Location: baza.php");
    exit();
}

$edit=false;
if (isset($_GET['edytuj'])) {
    $edit=true;
    $id = $_GET['edytuj'];
    $user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
}

$sql = 'SELECT id, imie, nazwisko, data_dodania FROM users';
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Lista Użytkowników</title>
    </head>
    <body>
        <h1>Lista Użytkowników</h1>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Data Dodania</th>
            </tr>

            <h3><?= $edit ? "edytuj uż." : "dodaj nowego użytkownika" ?></h3>
            <form method="post">
                <input type="hidden" name="id" value="<?= $edit ? $user['id'] : '' ?>">
                <input type="text" name="imie" placeholder="imię" value="<?= $edit ? $user['imie'] : '' ?>">
                <input type="text" name="nazwisko" placeholder="nazwisko" value="<?= $edit ? $user['nazwisko'] : '' ?>">
                <button type="submit" name="<?= $edit ? 'zapisz' : 'dodaj' ?>">
                    <?= $edit ? 'Zapisz' : 'Dodaj' ?>
                </button>
            </form>
            <!--<form method="post" action="baza.php">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <?php endif; ?>
                <label>Imię: <input type="text" name="imie" value="<?= $edit ? $user['imie'] : '' ?>"></label><br>
                <label>Nazwisko: <input type="text" name="nazwisko" value="<?= $edit ? $user['nazwisko'] : '' ?>"></label><br>
                <input type="submit" name="<?= $edit ? 'zapisz' : 'dodaj' ?>" value="<?= $edit ? 'Zapisz' : 'Dodaj' ?>">
            </form>-->

            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["imie"] . "</td>
                            <td>" . $row["nazwisko"] . "</td>
                            <td>" . $row["data_dodania"] . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Brak użytkowników w bazie danych.</td></tr>";
            }
            $conn->close();
            ?>
        </table>

    </body>
</html>