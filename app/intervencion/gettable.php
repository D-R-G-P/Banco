// Obtener el valor del parámetro 'banco'
if (isset($_GET['banco'])) {
    $banco = $_GET['banco'];

    try {
        // Preparar y ejecutar la consulta para obtener los datos correspondientes al banco seleccionado
        $query = "SELECT item, nombre, d_corta FROM items WHERE banco = :banco AND estado = 'act' AND stock >= 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':banco', $banco);
        $stmt->execute();

        // Obtener los datos como un array asociativo
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver los datos como JSON
        header('Content-Type: application/json');
        echo json_encode($results);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}