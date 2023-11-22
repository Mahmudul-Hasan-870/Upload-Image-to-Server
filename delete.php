<?php
// Include the configuration file
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $imageId = $data['imageId'];

    // Get the image URL from the database
    $sql = "SELECT url FROM images WHERE id = $imageId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageUrl = $row['url'];
        $fullImagePath = 'uploads/' . basename($imageUrl);

        if (file_exists($fullImagePath)) {
            // Delete the image file
            if (unlink($fullImagePath)) {
                // Delete the corresponding database record
                $sqlDelete = "DELETE FROM images WHERE id = $imageId";

                if ($conn->query($sqlDelete) === TRUE) {
                    $response = ['success' => true];
                } else {
                    $response = ['success' => false, 'message' => 'Error deleting image details from the database: ' . $conn->error];
                }
            } else {
                $response = ['success' => false, 'message' => 'Error deleting image file.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Image file not found.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Image not found in the database.'];
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle invalid request method
    $response = ['success' => false, 'message' => 'Invalid request method'];

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

$conn->close();
?>
