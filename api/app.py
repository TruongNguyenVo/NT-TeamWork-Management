from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/', methods=['GET', 'POST'])
def predict():
    # ket noi database
    # lay cac record cua member (lay id cua member di cho nhanh)
    # de vao model (da luong cho nhanh)
    # hien thi du lieu ve symfony
    data = {
        "status": 404,
        "message": "not found"
    }
    
    return jsonify(data)

if __name__ == '__main__':
    app.run(port=5000, debug=True)
