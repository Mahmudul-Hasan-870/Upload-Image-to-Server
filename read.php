<?php
// Include the configuration file
require_once 'config.php';

// Define your secret API key
$apiKey = 'yuFX9gaiQohRAfr9n1BrRZJo7f9nXV';

// Check if the provided API key matches the expected key
if (!isset($_GET['api_key']) || $_GET['api_key'] !== $apiKey) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(array('error' => 'Unauthorized access'));
    exit();
}

// Initialize the response array
$response = array();

// Fetch all images from the database
$sql = "SELECT id, url FROM images";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imageId = $row['id'];
        $imageUrl = $row['url'];
        $imagePath = str_replace(BASE_URL . '/', '', $imageUrl);

        // Add each image to the response array
        $response[] = array(
            'id' => $imageId,
            'url' => $imageUrl,
            'path' => $imagePath
        );
    }
}

// Close the database connection
$conn->close();

// Encode the response array as JSON and output it
header('Content-Type: application/json');
echo json_encode($response);
?>
