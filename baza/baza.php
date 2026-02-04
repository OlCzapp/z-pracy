<?php
$host = 'localhost';
$db   = 'baza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* DODAJ */
if (isset($_POST['dodaj'])) {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];

    $conn->query("INSERT INTO users (imie, nazwisko, data_dodania)
                  VALUES ('$imie', '$nazwisko', NOW())");
}

/* EDYTUJ PO ID */
if (isset($_POST['zapisz'])) {
    $id = $_POST['id'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];

    $conn->query("UPDATE users 
                  SET imie='$imie', nazwisko='$nazwisko' 
                  WHERE id=$id");
}

/* USUŃ */
if (isset($_GET['usun'])) {
    $id = $_GET['usun'];
    $conn->query("DELETE FROM users WHERE id=$id");
}

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista użytkowników</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center">
        <h1>Lista użytkowników</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Dodaj / Edytuj
        </button>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Dodaj / Edytuj użytkownika</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form method="post">

                        <!-- ID DO EDYCJI -->
                        <div class="mb-3">
                            <label class="form-label">ID (tylko do edycji)</label>
                            <input type="number" name="id" class="form-control" placeholder="Wpisz ID aby edytować">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Imię</label>
                            <input type="text" name="imie" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nazwisko</label>
                            <input type="text" name="nazwisko" class="form-control" required>
                        </div>

                        <button type="submit" name="dodaj" class="btn btn-success">
                            Dodaj
                        </button>

                        <button type="submit" name="zapisz" class="btn btn-warning" id="editBtn">
                            Edytuj
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered mt-4">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Data dodania</th>
            <th>Usuń</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr data-id="<?= $row['id'] ?>"data-imie="<?= htmlspecialchars($row['imie']) ?>"data-nazwisko="<?= htmlspecialchars($row['nazwisko']) ?>"> 
                <td><?= $row['id'] ?></td>
                <td><?= $row['imie'] ?></td>
                <td><?= $row['nazwisko'] ?></td>
                <td><?= $row['data_dodania'] ?></td>
                <td>
                    <a class="btn btn-danger btn-sm"
                       href="?usun=<?= $row['id'] ?>"
                       onclick="return confirm('Usunąć użytkownika?')">
                        Usuń
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
document.getElementById('editBtn').addEventListener('click', function (e) {
    const idInput = document.querySelector('input[name="id"]').value;

    if (!idInput) {
        alert("Podaj ID użytkownika do edycji.");
        e.preventDefault();
        return;
    }

    const row = document.querySelector(
        'tr[data-id="' + idInput + '"]'
    );

    if (!row) {
        alert("Nie znaleziono użytkownika o ID " + idInput);
        e.preventDefault();
        return;
    }

    const imie = row.dataset.imie;
    const nazwisko = row.dataset.nazwisko;

    const ok = confirm(
        imie + " " + nazwisko +
        ", o id " + idInput +
        ", czy na pewno chcesz edytować?"
    );

    if (!ok) {
        e.preventDefault(); // BLOKUJE SUBMIT
    }
});
</script>
</html>
