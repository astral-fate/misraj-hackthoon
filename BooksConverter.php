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
    <div class="container-fluid text-center py-5" dir="rtl">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <h1>Import Form</h1>
                <br>
                <p> You can choose the type of the imported file; pdf or from camera</p>
            </div>
        </div>
    </div>


    <!-- Features Section -->
    <div class="container text-center my-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <div class="icon-bg">
                                <img src="images/camera.png" alt="Icon" class="easy-icon" onclick="openCamera()">
                            </div>
                        </div>
                        <a href="process_image.php"><h4 class="card-title">From Camera</h4></a>
                        <p class="card-text">Use the camera to capture images for conversion.</p>
                        <input type="file" accept="image/*" capture="camera" id="cameraInput" style="display:none;">
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <div class="icon-bg">
                                <img src="images/file.png" alt="Icon" class="easy-icon" onclick="openFile()">
                            </div>
                        </div>
                        <a href="con.php"><h4 class="card-title">From File</h4></a>
                       

                     
                      
                        <p class="card-text">Upload files (PDF, Word) for conversion.</p>
                        <input type="file" accept=".pdf,.doc,.docx" id="fileInput" style="display:none;" onchange="showFormatPopup()">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Format Selection Modal -->
    <div class="modal" id="formatModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose Format</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
          
            </div>
        </div>
    </div>


    <script>
        function openCamera() {
            document.getElementById('cameraInput').click();
        }

        function openFile() {
            document.getElementById('fileInput').click();
        }

        function showFormatPopup() {
            $('#formatModal').modal('show');
        }

        function downloadFile(format) {
            $('#formatModal').modal('hide');
            let link = document.createElement('a');
            if (format === 'audiobook') {
                link.href = 'path/to/audiobook.mp3'; // Replace with actual URL to the generated MP3 file
                link.download = 'converted-file.mp3';
            } else if (format === 'braille') {
                link.href = 'path/to/braille.berf'; // Replace with actual URL to the generated .berf file
                link.download = 'converted-file.berf';
            }
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
