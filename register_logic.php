<?php

require_once 'conn.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirmPassword'] ?? null;

        if($email && $password && $confirmPassword) {
            if($password !== $confirmPassword) {
                throw new Exception("As senhas não coincidem!");
                
            }

            if(!$conn || $conn->connect_error) {
                throw new Exception("Conexão com banco de dados não está ativa.");
            }

            $hashedPassword = password_hash($password, PASSWORD_ARGON2I);         

            $sql = "INSERT INTO users (email, password) VALUES(?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ss", $email, $hashedPassword);

                if ($stmt->execute()) {
                    session_start();
                    $_SESSION['message'] = "Cadastro realizado com sucesso!";
                    $_SESSION['message_type'] = "primary";
                    header("Location: login.php");
                    exit();
                } else {
                    throw new Exception("Erro ao executar cadastro:" . $stmt->error);
                }

                $stmt->close();
            } else {
                throw new Exception("Erro ao preparar consulta: " . $conn->error);
            }
        } else {
            throw new Exception("Email, senha e confirmação de senha são obrigatórios!");
        }

        } else {
            throw new Exception("Método de requisição inválido!");
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
 } finally {
    if (isset($conn) && !$conn->connect_error) {
        $conn->close();
    }
 }