<?php
declare(strict_types = 1);                                         // Use strict types
include '../includes/database-connection.php';                     // Database connection
include '../includes/functions.php';                               // Functions
include '../includes/validate.php'; 

// echo "Funcionamdo";
// die();
// Inicializar variáveis para erros
$errors = [];

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Capturar os dados do formulário
    $title = trim($_POST['title']);
    $summary = trim($_POST['summary']);
    $content = trim($_POST['content']);
    $member_id = $_POST['member_id'];
    $category_id = $_POST['category_id'];
    $published = isset($_POST['published']) ? 1 : 0;

    // Validação dos campos obrigatórios
    if (empty($title)) $errors['title'] = "O título é obrigatório.";
    if (empty($summary)) $errors['summary'] = "O resumo é obrigatório.";
    if (empty($content)) $errors['content'] = "O conteúdo é obrigatório.";
    if (empty($member_id)) $errors['author'] = "O autor deve ser selecionado.";
    if (empty($category_id)) $errors['category'] = "A categoria deve ser selecionada.";
    
    // Upload de imagem
    $image_id = null; // Variável para armazenar o ID da imagem caso seja enviada
    if (!empty($_FILES['image']['name'])) {
        $image_file = $_FILES['image']['name'];
        $image_temp = $_FILES['image']['tmp_name'];
        $image_alt = trim($_POST['image_alt']);

        
        // Definir caminho de destino
        $upload_dir = "../uploads/";
        $image_path = $upload_dir . basename($image_file);
        
        // Verificar se o upload foi bem-sucedido
        if (move_uploaded_file($image_temp, $image_path)) {
            // Inserir na tabela `image`
            var_dump($errors);
        die();
            $stmt = $pdo->prepare("INSERT INTO image (image_file, image_alt) VALUES (?, ?)");
            if ($stmt->execute([$image_file, $image_alt])) {
                $image_id = $pdo->lastInsertId(); // Obtém o ID da imagem inserida
            }
            
        } else {
            
            $errors['image_file'] = "Falha ao fazer upload da imagem.";
        }
    }
   
    // Se não houver erros, inserir na tabela `article`
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO article (title, summary, content, member_id, category_id, image_id, published, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

        $success = $stmt->execute([$title, $summary, $content, $member_id, $category_id, $image_id, $published]);

        if ($success) {
            header("Location: articles.php?success=1"); // Redireciona após cadastro bem-sucedido
            exit;
        } else {
            $errors['warning'] = "Erro ao cadastrar artigo.";
        }
    }
}


