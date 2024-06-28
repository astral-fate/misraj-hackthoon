<?php
require 'vendor/autoload.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['image'])) {
    try {
        // Decode the base64 image data
        $imageData = $_POST['image'];
        list($type, $imageData) = explode(';', $imageData);
        list(, $imageData)      = explode(',', $imageData);
        $imageData = base64_decode($imageData);

        // Save the image to a temporary file
        $uploads_dir = 'uploads';
        $images_dir = $uploads_dir . DIRECTORY_SEPARATOR . 'images';
        if (!is_dir($uploads_dir)) {
            if (!mkdir($uploads_dir, 0777, true)) {
                throw new Exception('Failed to create uploads directory');
            }
        }
        if (!is_dir($images_dir)) {
            if (!mkdir($images_dir, 0777, true)) {
                throw new Exception('Failed to create images directory');
            }
        }

        $imagePath = $images_dir . DIRECTORY_SEPARATOR . 'captured_image.png';
        if (!file_put_contents($imagePath, $imageData)) {
            throw new Exception('Failed to save the captured image');
        }

        // Perform OCR on the image
        $tesseractPath = 'C:\Program Files\Tesseract-OCR\tesseract.exe'; // Path to Tesseract executable
        if (!file_exists($tesseractPath)) {
            throw new Exception('Tesseract executable not found at specified path: ' . $tesseractPath);
        }

        $text = (new TesseractOCR($imagePath))
            ->executable($tesseractPath)
            ->lang('eng') // Specify language; add 'ara' if the text is in Arabic
            ->run();

        // Save the extracted text to a file for debugging
        if (!file_put_contents('extracted_text.txt', $text)) {
            throw new Exception('Failed to save extracted text');
        }

        // Display the extracted text
        echo '<h2>Extracted Text:</h2>';
        echo '<pre>' . htmlentities($text) . '</pre>';
        echo '<p>File has been processed successfully.</p>';
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'No image data received.';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بصير</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header Section -->
    <div class="container-fluid text-center py-5" dir="rtl">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <h1>بصير</h1>
                <br>
                <p>من أجل تجربة تعليمية شاملة للمكفوفين</p>
            </div>
        </div>
    </div>

    <!-- Capture Image Section -->
    <div class="container text-center my-5">
        <div class="row">
            <div class="col-md-12">
                <button onclick="startCamera()" class="btn btn-primary mb-3">Start Camera</button>
                <video id="video" width="640" height="480" autoplay></video>
                <button onclick="captureImage()" class="btn btn-success mt-3">Capture Image</button>
                <canvas id="canvas" style="display:none;"></canvas>

                <form id="imageForm" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="image" id="imageData">
                  
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-4">
        <p>2024 All rights reserved</p>
    </footer>

    <script>
        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    const video = document.getElementById('video');
                    video.srcObject = stream;
                })
                .catch(err => {
                    console.error("Error accessing camera: ", err);
                });
        }

        function captureImage() {
            const canvas = document.getElementById('canvas');
            const video = document.getElementById('video');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/png');
            document.getElementById('imageData').value = imageData;
            document.getElementById('imageForm').submit();
        }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
