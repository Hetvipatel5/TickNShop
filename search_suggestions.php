
<?php
session_start();                    
include_once __DIR__ . '/db.php';
if(isset($_GET['query'])){
    $search = "%" . $_GET['query'] . "%";

    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE name LIKE ?");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<div class='suggestion-item' data-id='" . $row['id'] . "'>" .
                    htmlspecialchars($row['name']) . " - ₹" . $row['price'] .
                 "</div>";
        }
    } else {
        echo "<div class='suggestion-item'>No results found</div>";
    }
}
?>


