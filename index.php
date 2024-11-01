<?php 
session_start();

// connexion via base de donnée :
// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     if (isset($_POST["description"]) && !empty($_POST["description"])) {
//         $description = $_POST["description"];

//         $conn = new mysqli("localhost", "root", "root", "task_manager");
//         if ($conn->connect_error) {
//             die("Connection failed: " . $conn->connect_error);
//         } else {
//             echo "Connexion réussie !"; 
//         }

//         $stmt = $conn->prepare("INSERT INTO tasks (description) VALUES (?)");
//         $stmt->bind_param("s", $description);

//         if ($stmt->execute()) {
//             if (isset($_SESSION["tasks"])) {
//                 $_SESSION["tasks"] = [];
//             }
//             $_SESSION['tasks'][] = $description;
//         } else {
//             echo "Erreur lors de l'ajout de la tâche : " . $stmt->error;
//         }
        
//         $stmt->close();
//         $conn->close();
//     }
// }

//connexion via session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["description"])) {
    $description = $_POST["description"];
    if (!isset($_SESSION["tasks"])) {
        $_SESSION['tasks'] = [];
    }
    $_SESSION['tasks'][] = $description;
    header("Location: index.php");
    exit();
}

if (isset($_GET["delete"])) {
    $idToDelete = $_GET['delete'];
    unset($_SESSION['tasks'][$idToDelete]);
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo list</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form">
        <h1>TODO LIST</h1>
        <form method="post" action="index.php">
            <input type="text" name="description" id="description" placeholder="Ajouter une tâche..." required>
            <input type="submit" id="btn-submit" value="Ajouter">
        </form>

        <ul>
            <?php 
                if (isset($_SESSION['tasks'])) {
                    foreach ($_SESSION['tasks'] as $key => $task) {
                        echo "
                        <li>
                            <input type='checkbox' class='task-checkbox'>
                            <span>$task | <a href='?delete=$key'>Supprimer</a><span>
                        </li>";
                    }
                }
            ?>
        </ul>
    </div>
    <script>
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.querySelector('span').style.textDecoration = 'line-through';
                } else {
                    this.parentElement.querySelector('span').style.textDecoration = 'none';
                }
            });
        });
    </script>
</body>
</html>