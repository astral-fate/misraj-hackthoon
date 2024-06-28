from flask import Flask, Response, jsonify
from ultralytics import YOLO
import cv2
import math
from flask_cors import CORS
import pyttsx3
import threading
import time

app = Flask(__name__)
CORS(app)

# Initialize pyttsx3 engine
engine = pyttsx3.init()

# Load YOLO model
model = YOLO("../YOLO-Weights/yolov8n.pt")
classNames = ["person", "bicycle", "car", "motorbike", "aeroplane", "bus", "train", "truck", "boat",
              "traffic light", "fire hydrant", "stop sign", "parking meter", "bench", "bird", "cat",
              "dog", "horse", "sheep", "cow", "elephant", "bear", "zebra", "giraffe", "backpack", "umbrella",
              "handbag", "tie", "suitcase", "frisbee", "skis", "snowboard", "sports ball", "kite", "baseball bat",
              "baseball glove", "skateboard", "surfboard", "tennis racket", "bottle", "wine glass", "cup",
              "fork", "knife", "spoon", "bowl", "banana", "apple", "sandwich", "orange", "broccoli",
              "carrot", "hot dog", "pizza", "donut", "cake", "chair", "sofa", "pottedplant", "bed",
              "diningtable", "toilet", "tvmonitor", "laptop", "mouse", "remote", "keyboard", "cell phone",
              "microwave", "oven", "toaster", "sink", "refrigerator", "book", "clock", "vase", "scissors",
              "teddy bear", "hair drier", "toothbrush"
              ]

detected_objects = []
detected_objects_lock = threading.Lock()

def test_camera():
    cap = cv2.VideoCapture(0)
    if not cap.isOpened():
        print("Error: Camera not accessible")
    else:
        print("Camera opened successfully")
    cap.release()

def gen_frames():
    global detected_objects
    cap = cv2.VideoCapture(0)
    cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)
    while True:
        success, img = cap.read()
        if not success:
            break
        else:
            with detected_objects_lock:
                detected_objects = []
                results = model(img, stream=True)
                for r in results:
                    boxes = r.boxes
                    for box in boxes:
                        x1, y1, x2, y2 = box.xyxy[0]
                        x1, y1, x2, y2 = int(x1), int(y1), int(x2), int(y2)
                        cv2.rectangle(img, (x1, y1), (x2, y2), (255, 0, 255), 3)
                        conf = math.ceil((box.conf[0] * 100)) / 100
                        cls = int(box.cls[0])
                        class_name = classNames[cls]
                        detected_objects.append(class_name)
                        label = f'{class_name} {conf}'
                        t_size = cv2.getTextSize(label, 0, fontScale=1, thickness=2)[0]
                        c2 = x1 + t_size[0], y1 - t_size[1] - 3
                        cv2.rectangle(img, (x1, y1), c2, [255, 0, 255], -1, cv2.LINE_AA)
                        cv2.putText(img, label, (x1, y1 - 2), 0, 1, [255, 255, 255], thickness=1, lineType=cv2.LINE_AA)
            ret, buffer = cv2.imencode('.jpg', img)
            frame = buffer.tobytes()
            yield (b'--frame\r\n'
                   b'Content-Type: image/jpeg\r\n\r\n' + frame + b'\r\n')

def speak_detected_objects():
    last_spoken_objects = set()
    while True:
        with detected_objects_lock:
            if detected_objects:
                current_objects = set(detected_objects)
                new_objects = current_objects - last_spoken_objects
                if new_objects:
                    text = ', '.join(new_objects)
                    print(f"Speaking: {text}")  # Add logging
                    engine.say(text)
                    engine.runAndWait()
                    last_spoken_objects = current_objects
                else:
                    print("No new objects to speak.")
        time.sleep(3)  # Add a delay to prevent continuous speech

@app.route('/video_feed')
def video_feed():
    return Response(gen_frames(), mimetype='multipart/x-mixed-replace; boundary=frame')

@app.route('/detected_objects')
def get_detected_objects():
    global detected_objects
    with detected_objects_lock:
        return jsonify(detected_objects)

if __name__ == '__main__':
    test_camera()  # Test camera at the start
    threading.Thread(target=speak_detected_objects, daemon=True).start()
    app.run(debug=True)
