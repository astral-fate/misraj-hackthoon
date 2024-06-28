<?php
require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

$pdfText = '';
$statusMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    try {
        $file_path = $_FILES['file']['tmp_name'];
        $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        // Allow only PDF files
        $allowTypes = array('pdf');
        if (in_array($fileType, $allowTypes)) {
            // Initialize and load PDF Parser library
            $parser = new Parser();

            // Parse pdf file using Parser library
            $pdf = $parser->parseFile($file_path);

            // Extract text from PDF
            $text = $pdf->getText();

            // Save extracted text to a file for debugging
            file_put_contents('extracted_text.txt', $text);

            // Determine the output format
            $output_format = $_POST['output_format'];
            $output_file = $output_format === 'audiobook' ? 'output.mp3' : 'output.brf';

            if ($output_format === 'braille') {
                // Define the Braille map
                $braille_map = [
                    'a' => '⠁', 'b' => '⠃', 'c' => '⠉', 'd' => '⠙', 'e' => '⠑',
                    'f' => '⠋', 'g' => '⠛', 'h' => '⠓', 'i' => '⠊', 'j' => '⠚',
                    'k' => '⠅', 'l' => '⠇', 'm' => '⠍', 'n' => '⠝', 'o' => '⠕',
                    'p' => '⠏', 'q' => '⠟', 'r' => '⠗', 's' => '⠎', 't' => '⠞',
                    'u' => '⠥', 'v' => '⠧', 'w' => '⠺', 'x' => '⠭', 'y' => '⠽', 'z' => '⠵',
                    '1' => '⠼⠁', '2' => '⠼⠃', '3' => '⠼⠉', '4' => '⠼⠙', '5' => '⠼⠑',
                    '6' => '⠼⠋', '7' => '⠼⠛', '8' => '⠼⠓', '9' => '⠼⠊', '0' => '⠼⠚',
                    ' ' => ' ',
                ];

                // Translate text to Braille
                function translate_to_braille($text, $braille_map) {
                    $translated_text = '';
                    foreach (str_split(strtolower($text)) as $char) {
                        $translated_text .= $braille_map[$char] ?? $char;
                    }
                    return $translated_text;
                }

                // Translate to Braille
                $braille_text = translate_to_braille($text, $braille_map);

                // Save translated Braille text to a file
                file_put_contents($output_file, $braille_text, LOCK_EX);

            } elseif ($output_format === 'audiobook') {
                // Path to the Python script
                $pythonScript = 'pdf_to_mp3.py';

                // Save the extracted text to a temporary file
                $temp_txt_file = 'temp_text.txt';
                file_put_contents($temp_txt_file, $text);

                // Command to execute the Python script
                $command = escapeshellcmd("python $pythonScript $temp_txt_file $output_file");
                exec($command, $output, $returnVar);

                // Remove the temporary text file
                unlink($temp_txt_file);

                if ($returnVar !== 0) {
                    throw new Exception("Failed to convert text to MP3.");
                }
            }

            // Provide the output file for download
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . basename($output_file));
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . filesize($output_file));
            readfile($output_file);
            unlink($output_file);
            exit;
        } else {
            $statusMsg = '<p>Sorry, only PDF file is allowed to upload.</p>';
        }
    } catch (Exception $e) {
        // Output the error message for debugging
        file_put_contents('error_log.txt', $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بصير</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card.selected {
            border: 2px solid blue;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <body>
    <!-- Header Section -->
    <div class="container-fluid text-center py-5" dir="rtl">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <h1>Books Converter</h1>
                <br>
                <p> You can convert your uploaded file as Braille or Audiobook</p>
            </div>
        </div>
    </div>

    <!-- File Upload Section -->
    <div class="container text-center my-5">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="file">اختر ملف</label>
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card h-100 format-card" data-format="braille" onclick="selectFormat('braille')">
                                        <div class="card-body">
                                            <div class="icon-wrapper">
                                                <div class="icon-bg">
                                                <img src="images/braile.png" class="easy-icon">
                                                </div>
                                            </div>
                                            <h4 class="card-title">Braille</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 format-card" data-format="audiobook" onclick="selectFormat('audiobook')">
                                        <div class="card-body">
                                            <div class="icon-wrapper">
                                                <div class="icon-bg">
                                                    <img src="images/audio.png" alt="Audiobook Icon" class="easy-icon">
                                                    
                                                </div>
                                            </div>
                                            <h4 class="card-title">Audiobook</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="output_format" id="output_format">
                            <br>
                            <button type="submit" class="btn btn-primary mt-3">رفع وتحويل</button>
                        </div>
                    </div>
                </form>
                <?php if (!empty($statusMsg)) { ?>
                    <div class="alert alert-info mt-3"><?php echo $statusMsg; ?></div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-4">
        <p>2024 All rights reserved</p>
    </footer>

    <script>
        function selectFormat(format) {
            document.getElementById('output_format').value = format;
            document.querySelectorAll('.format-card').forEach(function(card) {
                card.classList.remove('selected');
            });
            document.querySelector('.format-card[data-format="' + format + '"]').classList.add('selected');
        }

        document.querySelector('form').addEventListener('submit', function() {
            setTimeout(function() {
                alert('File has been downloaded successfully.');
            }, 1000);
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
