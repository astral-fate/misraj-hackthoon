<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My University Campus</title>
    <style>
        .container {
            text-align: center;
        }
        img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            width: 80%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Navigate University Campus</h1>
        <img src="http://127.0.0.1:5000/video_feed" />
    </div>

    <script>
        function speak(text) {
            const utterance = new SpeechSynthesisUtterance(text);
            speechSynthesis.speak(utterance);
        }

        async function fetchDetectedObjects() {
            const response = await fetch('http://127.0.0.1:5000/detected_objects');
            const objects = await response.json();
            if (objects.length > 0) {
                speak(objects.join(', '));
            }
        }

        setInterval(fetchDetectedObjects, 3000);
    </script>
</body>
</html>
