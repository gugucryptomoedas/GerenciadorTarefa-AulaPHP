<?php

require_once 'conn.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $title = isset($_POST['title']) ? $_POST['title'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;

        if($id && $title !== null) {
            $sql = "UPDATE crud_php SET title = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssi", $title, $description, $id);

                if ($stmt->execute()) {
                    header("Location: index.php");
                    exit();
                    } else {
                    throw new Exception("Erro ao executar a consulta: " . $stmt->error);
                }

                $stmt->close();
            } else {
                throw new Exception("Erro ao preparar consulta: " . $conn->error);
            }
        } else {
            throw new Exception("O campo title é obrigatório");
        }
    } else {
        throw new exception("Metódo de requisição invalido");
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
} finally {
    $conn->close();
} 

?>