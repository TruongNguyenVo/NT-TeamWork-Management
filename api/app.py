from flask import Flask, request, jsonify
import joblib
from connectDatabase import query_predict_view
app = Flask(__name__)

# Load model
with open('models\\decision_tree_model.pkl', 'rb') as f:
    model = joblib.load(f)

@app.route('/predict', methods=['GET', 'POST'])
def predict():
    # ket noi database

    dataset = query_predict_view()
    # lay cac record cua member (lay id cua member di cho nhanh)
    # de vao model (da luong cho nhanh)
    # hien thi du lieu ve symfony
    # Dữ liệu test đầu vào
 
    data = {}
    # # Dự đoán kết quả
    for key,value in dataset.items():
        prediction = model.predict(value)
        data[key] = prediction[0]

    data = {
        "status": 200,
        "data": data,
    }
    
    return jsonify(data)

if __name__ == '__main__':
    app.run(port=5000, debug=True)
