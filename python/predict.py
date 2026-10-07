"""
predict.py — Dipanggil langsung oleh Laravel via exec/shell_exec.
Cara pakai: python predict.py "teks pengaduan di sini"
Output    : JSON ke stdout
"""

import sys
import os
import re
import json
import joblib
import numpy as np

# ─── Stopword sederhana Bahasa Indonesia ───
STOPWORDS_ID = {
    "yang", "dan", "di", "ke", "dari", "ini", "itu", "dengan", "untuk",
    "adalah", "pada", "dalam", "tidak", "ada", "juga", "sudah", "saya",
    "kami", "kita", "mereka", "dia", "anda", "bisa", "akan", "karena",
    "jika", "maka", "tapi", "atau", "oleh", "dapat", "seperti", "lebih",
    "agar", "atas", "bawah", "lain", "sama", "setelah", "sebelum",
    "telah", "sedang", "pun", "pula", "hal", "cara", "saat", "bila",
    "bahwa", "antara", "hingga", "terhadap", "sehingga", "tersebut",
    "tentang", "namun", "namanya", "ya", "nya", "lah", "kah",
}

def preprocess_text(text: str) -> str:
    text = text.lower()
    text = re.sub(r"[^a-z0-9\s]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()
    tokens = [w for w in text.split() if w not in STOPWORDS_ID]
    return " ".join(tokens)

def softmax(x: np.ndarray) -> np.ndarray:
    e_x = np.exp(x - np.max(x))
    return e_x / e_x.sum()

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Tidak ada teks yang diberikan"}))
        sys.exit(1)

    teks = sys.argv[1]

    # Load model dari folder yang sama dengan script ini
    BASE_DIR = os.path.dirname(os.path.abspath(__file__))
    tfidf     = joblib.load(os.path.join(BASE_DIR, "tfidf_model.joblib"))
    le        = joblib.load(os.path.join(BASE_DIR, "label_encoder.joblib"))
    svm_model = joblib.load(os.path.join(BASE_DIR, "model_svm.joblib"))

    # Prediksi
    teks_bersih  = preprocess_text(teks)
    fitur        = tfidf.transform([teks_bersih])
    prediksi_id  = svm_model.predict(fitur)[0]
    prediksi_label = le.inverse_transform([prediksi_id])[0]

    # Confidence score
    decision_scores = svm_model.decision_function(fitur)[0]
    if decision_scores.ndim == 0:
        decision_scores = np.array([decision_scores])
    proba = softmax(decision_scores)

    semua_label = le.inverse_transform(svm_model.classes_)
    semua_skor  = {
        str(label): round(float(prob), 4)
        for label, prob in zip(semua_label, proba)
    }
    confidence = float(proba[list(svm_model.classes_).index(prediksi_id)])

    hasil = {
        "teks_asli"       : teks,
        "teks_bersih"     : teks_bersih,
        "kategori_id"     : int(prediksi_id),
        "kategori_label"  : prediksi_label,
        "confidence_score": round(confidence, 4),
        "semua_skor"      : semua_skor,
    }

    # Output JSON ke stdout (dibaca oleh Laravel)
    print(json.dumps(hasil, ensure_ascii=False))

if __name__ == "__main__":
    main()
