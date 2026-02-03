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
    // header("Location: baza.php");
    // exit();
}

if (isset($_POST['zapisz'])) {
    $id = $_POST['id'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $conn->query("UPDATE users SET imie='$imie', nazwisko='$nazwisko' WHERE id=$id");
    // header("Location: baza.php");
    // exit();
}

$edit = false;
if (isset($_GET['edytuj'])) {
    $edit = true;
    $id = $_GET['edytuj'];
    $user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
}

$del = false;
if (isset($_GET['usun'])) {
    $del = true;
    $id = $_GET['usun'];
    $user = $conn->query("DELETE FROM users WHERE id=$id");
}

$sql = 'SELECT id, imie, nazwisko, data_dodania FROM users';
$result = $conn->query($sql);
// print_r($_POST);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Lista Użytkowników</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">


                <!-- Button trigger modal -->


                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel"><?= $edit ? "edytuj uż." : "dodaj nowego użytkownika" ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="imie" class="form-label">Imię</label>
                                        <input type="text" id="imie" class="form-control" name="imie" placeholder="imię" value="<?= $edit ? $user['imie'] : '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nazwisko" class="form-label">Nazwisko</label>
                                        <input type="text" id="nazwisko" class="form-control" name="nazwisko" placeholder="nazwisko" value="<?= $edit ? $user['nazwisko'] : '' ?>">
                                    </div>
                                    <input type="hidden" name="id" value="<?= $edit ? $user['id'] : '' ?>">
                                    <button class="btn btn-success" type="submit" name="<?= $edit ? 'zapisz' : 'dodaj' ?>">
                                        <?= $edit ? 'Zapisz' : 'Dodaj' ?>
                                    </button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <h1>Lista Użytkowników</h1>
                    <div>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Dodaj
                        </button>
                    </div>
                </div>

                <table class="table table-striped table-bordered">
                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>Data Dodania</th>
                            <th>Akcje</th>
                            <th>USUN</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row["id"] ?></td>
                                    <td><?= $row["imie"] ?></td>
                                    <td><?= $row["nazwisko"] ?></td>
                                    <td><?= $row["data_dodania"] ?></td>
                                    <td><a class="btn btn-sm btn-primary" href='?edytuj=<?= $row["id"] ?>'>Edytuj</a></td>
                                    <td><a class="btn btn-sm btn-danger" href='?usun=<?= $row["id"] ?>' onclick='return confirm(\"Czy na pewno chcesz usunąć tego użytkownika?\")'>Usuń</a></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>Brak użytkowników w bazie danych.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
<!--dodać usuń potwiedzany konfirmem-->
