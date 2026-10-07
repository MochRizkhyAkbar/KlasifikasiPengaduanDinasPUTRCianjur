from fastapi import FastAPI
from pydantic import BaseModel
import joblib
import os
import re
import numpy as np
from typing import Dict

# ─────────────────────────────────────────────
# Preprocessing (digabung langsung, tidak perlu utils.py eksternal)
# ─────────────────────────────────────────────

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
    """Lowercase → hapus karakter non-alfanumerik → hapus stopword → strip."""
    text = text.lower()
    text = re.sub(r"[^a-z0-9\s]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()
    tokens = [w for w in text.split() if w not in STOPWORDS_ID]
    return " ".join(tokens)


# ─────────────────────────────────────────────
# Load model (semua ada di folder yang sama dengan main.py)
# ─────────────────────────────────────────────

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

print("Memuat model dari:", BASE_DIR)
tfidf     = joblib.load(os.path.join(BASE_DIR, "tfidf_model.joblib"))
le        = joblib.load(os.path.join(BASE_DIR, "label_encoder.joblib"))
svm_model = joblib.load(os.path.join(BASE_DIR, "model_svm.joblib"))
print("OK - Semua model berhasil dimuat!")


# ─────────────────────────────────────────────
# FastAPI App
# ─────────────────────────────────────────────

app = FastAPI(title="API Klasifikasi Pengaduan PUPR")


def softmax(x: np.ndarray) -> np.ndarray:
    """Konversi decision scores ke probabilitas menggunakan softmax."""
    e_x = np.exp(x - np.max(x))
    return e_x / e_x.sum()


class PengaduanRequest(BaseModel):
    teks: str


class PrediksiResponse(BaseModel):
    teks_asli: str
    teks_bersih: str
    kategori_id: int
    kategori_label: str
    confidence_score: float
    semua_skor: Dict[str, float]


@app.get("/")
def root():
    return {"status": "ok", "pesan": "API Klasifikasi Pengaduan PUPR aktif"}


@app.post("/predict", response_model=PrediksiResponse)
def predict_kategori(req: PengaduanRequest):
    # 1. Preprocessing teks
    teks_bersih = preprocess_text(req.teks)

    # 2. Ekstraksi fitur TF-IDF
    fitur = tfidf.transform([teks_bersih])

    # 3. Prediksi kelas
    prediksi_id = svm_model.predict(fitur)[0]

    # 4. Decode label
    prediksi_label = le.inverse_transform([prediksi_id])[0]

    # 5. Confidence score via decision_function + softmax
    decision_scores = svm_model.decision_function(fitur)[0]
    if decision_scores.ndim == 0:
        decision_scores = np.array([decision_scores])

    proba = softmax(decision_scores)

    semua_label = le.inverse_transform(svm_model.classes_)
    semua_skor = {
        str(label): round(float(prob), 4)
        for label, prob in zip(semua_label, proba)
    }

    confidence = float(proba[list(svm_model.classes_).index(prediksi_id)])

    return PrediksiResponse(
        teks_asli=req.teks,
        teks_bersih=teks_bersih,
        kategori_id=int(prediksi_id),
        kategori_label=prediksi_label,
        confidence_score=round(confidence, 4),
        semua_skor=semua_skor,
    )
