<?php

require_once '../includes/db.php';



// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO newsletters (title, content) VALUES (:title, :content)");
        $stmt->execute(['title' => $title, 'content' => $content]);
        $message = "Newsletter posted successfully!";
    } else {
        $message = "Please fill in both title and content.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Post Newsletter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: #e0ffe0;
            color: #006600;
        }
    </style>
</head>

<body >
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <img src="/templates/pwk.png" alt="Logo" style="max-width:150px;" /> 
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="super_admin_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
     
     
    <div class="container">
        <h2>Post a Newsletter</h2>
        <?php if (!empty($message))
            echo "<div class='message'>$message</div>"; ?>
        <form method="POST" action="">
            <label for="title">Newsletter Title</label>
            <input type="text" id="title" name="title" placeholder="Enter newsletter title" required>

            <label for="content">Content</label>
            <textarea id="content" name="content" rows="10" placeholder="Write the content here..." required></textarea>

            <button type="submit">Post Newsletter</button>
        </form>
    </div>
</body>

</html>