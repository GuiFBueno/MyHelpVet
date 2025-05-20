<?php
$conn = new PDO("pgsql:host=localhost;dbname=petshop", "usuario", "senha");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeDono = $_POST["nome_dono"];
    $stmt = $conn->prepare("INSERT INTO Dono (nome) VALUES (?) RETURNING id");
    $stmt->execute([$nomeDono]);
    $idDono = $stmt->fetchColumn();

    $stmt = $conn->prepare("INSERT INTO Animal (nome, peso, idade, sexo, raca, id_dono) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST["nome"],
        $_POST["peso"],
        $_POST["idade"],
        $_POST["sexo"],
        $_POST["raca"],
        $idDono
    ]);
}

$stmt = $conn->query("SELECT Animal.nome, Animal.raca, Dono.nome AS dono FROM Animal JOIN Dono ON Animal.id_dono = Dono.id ORDER BY Animal.id");
$fila = $stmt->fetchAll();
?>

<form method="post">
    Nome do Animal: <input type="text" name="nome"><br>
    Peso: <input type="text" name="peso"><br>
    Idade: <input type="text" name="idade"><br>
    Sexo (M/F): <input type="text" name="sexo"><br>
    Raça: <input type="text" name="raca"><br>
    Nome do Dono: <input type="text" name="nome_dono"><br>
    <button type="submit">Adicionar à Fila</button>
</form>

<h2>Fila de Atendimento</h2>
<ul>
<?php foreach ($fila as $item): ?>
    <li><?php echo $item["nome"] . " (" . $item["raca"] . ") - Dono: " . $item["dono"]; ?></li>
<?php endforeach; ?>
</ul>
