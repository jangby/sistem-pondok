from flask import Flask, request, jsonify
import face_recognition
import numpy as np
import json

app = Flask(__name__)

def is_json(myjson):
    try:
        json_object = json.loads(myjson)
    except ValueError as e:
        return False
    return True

@app.route('/get-embedding', methods=['POST'])
def get_embedding():
    # Digunakan saat Pendaftaran Wajah
    if 'image' not in request.files:
        return jsonify({'status': 'error', 'message': 'No image uploaded'}), 400
    
    file = request.files['image']
    img = face_recognition.load_image_file(file)
    encodings = face_recognition.face_encodings(img)
    
    if len(encodings) > 0:
        return jsonify({
            'status': 'success',
            'embedding': json.dumps(encodings[0].tolist())
        })
    else:
        return jsonify({'status': 'failed', 'message': 'Wajah tidak terdeteksi'}), 400

@app.route('/compare', methods=['POST'])
def compare_face():
    # Digunakan saat Absensi
    if 'image' not in request.files or 'known_faces' not in request.form:
        return jsonify({'status': 'error', 'message': 'Data tidak lengkap'}), 400

    # 1. Proses Gambar dari Webcam
    file = request.files['image']
    unknown_image = face_recognition.load_image_file(file)
    unknown_encodings = face_recognition.face_encodings(unknown_image)

    if len(unknown_encodings) == 0:
        return jsonify({'status': 'failed', 'message': 'Wajah tidak ditemukan di kamera'}), 200

    # Ambil encoding wajah yang ada di kamera (anggap cuma ada 1 orang di depan kamera)
    unknown_face_encoding = unknown_encodings[0]

    # 2. Ambil Data Wajah Santri dari Laravel
    # Format JSON dari Laravel: [{"id": 1, "embedding": [0.123, ...]}, {"id": 2, "embedding": [...]}]
    known_faces_data = json.loads(request.form['known_faces'])
    
    known_encodings = []
    known_ids = []

    for item in known_faces_data:
        # Decode string JSON embedding menjadi List Python
        if item['embedding']:
            embedding_list = json.loads(item['embedding'])
            known_encodings.append(np.array(embedding_list))
            known_ids.append(item['id'])

    if not known_encodings:
        return jsonify({'status': 'failed', 'message': 'Database wajah kosong'}), 200

    # 3. Bandingkan Wajah (Face Distance)
    # Kita cari jarak terdekat (semakin kecil nilainya, semakin mirip)
    face_distances = face_recognition.face_distance(known_encodings, unknown_face_encoding)
    
    # Ambil indeks dengan jarak terpendek (paling mirip)
    best_match_index = np.argmin(face_distances)
    
    # Treshold (Batas Toleransi): 0.6 adalah standar, 0.45 sangat ketat, 0.5-0.55 cukup aman
    if face_distances[best_match_index] < 0.50:
        matched_id = known_ids[best_match_index]
        return jsonify({
            'status': 'match',
            'santri_id': matched_id,
            'confidence': float(1 - face_distances[best_match_index]) # Skor kemiripan
        })
    else:
        return jsonify({'status': 'no_match', 'message': 'Wajah tidak dikenali'}), 200

if __name__ == '__main__':
    app.run(port=5000, debug=True)