<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Adaugă film sau carte</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>➕ Adaugă film sau carte</h1>
        
        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $type = $_POST['type'];
            
            // Upload imagine
            $imageName = '';
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = time() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
            }
            
            $stmt = $pdo->prepare("INSERT INTO items (title, type, image) VALUES (?, ?, ?)");
            if($stmt->execute([$title, $type, $imageName])) {
                echo "<p class='success'>✅ Adăugat cu succes! <a href='index.php'>Înapoi acasă</a></p>";
            } else {
                echo "<p class='error'>❌ Eroare la adăugare</p>";
            }
        }
        ?>
        
        <form method="POST" enctype="multipart/form-data">
            <label>Titlu:</label>
            <input type="text" name="title" required>
            
            <label>Tip:</label>
            <select name="type">
                <option value="movie">🎬 Film</option>
                <option value="book">📖 Carte</option>
            </select>
            
            <label>Imagine copertă:</label>
            <input type="file" name="image" accept="image/*">
            
            <button type="submit" class="btn">Salvează</button>
            <a href="index.php" class="btn-cancel">Anulează</a>
        </form>
    </div>
</body>
</html>
