<?php
// Include the configuration file
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = "uploads/";

    // Create the uploads folder if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $fileName = date("YmdHis") . '_' . generateRandomString(5) . '.' . $extension;
    $targetFile = $uploadDir . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the image file is a real image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $response = ['success' => false, 'message' => 'File is not a valid image.'];
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 5000000) {
        $response = ['success' => false, 'message' => 'Sorry, your file is too large.'];
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        $response = ['success' => false, 'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'];
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $response = ['success' => false, 'message' => 'Error uploading image.'];
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Save image details to the database
            $imageUrl = BASE_URL . '/' . $targetFile;
            $sql = "INSERT INTO images (url) VALUES ('$imageUrl')";
            if ($conn->query($sql) === TRUE) {
                $response = ['success' => true, 'imageUrl' => $imageUrl];
            } else {
                $response = ['success' => false, 'message' => 'Error saving image details to the database: ' . $conn->error];
            }
        } else {
            $response = ['success' => false, 'message' => 'Error uploading image.'];
        }
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

// Function to generate a random string
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>
