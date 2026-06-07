from flask import Flask, request, jsonify
from ultralytics import YOLO
import cv2
import numpy as np
import os

app = Flask(__name__)

model_path = os.path.join(os.path.dirname(__file__), 'best.pt')
model = None

def load_model():
    global model
    if os.path.exists(model_path):
        model = YOLO(model_path)
        return True
    return False

model_loaded = load_model()
if not model_loaded:
    print("WARNING: best.pt tidak ditemukan. Letakkan file model di ml-server/best.pt")

@app.route('/detect', methods=['POST'])
def detect():
    if not model_loaded or model is None:
        return jsonify({
            'status': 'error',
            'message': 'Model YOLO (best.pt) belum tersedia. Letakkan file di ml-server/best.pt'
        }), 503

    if 'image' not in request.files:
        return jsonify({'error': 'No image provided'}), 400

    file = request.files['image']
    img_bytes = file.read()
    img = cv2.imdecode(np.frombuffer(img_bytes, np.uint8), cv2.IMREAD_COLOR)

    if img is None:
        return jsonify({'error': 'Invalid image'}), 400

    results = model(img)[0]

    detections = []
    for box in results.boxes:
        cls_id = int(box.cls[0])
        label = 'asli' if cls_id == 0 else 'palsu'
        detections.append({
            'label': label,
            'confidence': round(float(box.conf[0]), 4),
            'bbox': [int(x) for x in box.xyxy[0].tolist()]
        })

    kesimpulan = max(detections, key=lambda x: x['confidence'])['label'] if detections else 'tidak terdeteksi'

    return jsonify({
        'status': 'success',
        'detections': detections,
        'kesimpulan': kesimpulan
    })

@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        'status': 'ok',
        'model_loaded': model_loaded,
        'model_path': model_path
    })

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=False)
