from flask import Flask, request, jsonify
import joblib
import connectDatabase
app = Flask(__name__)

# Load model
with open('models\\decision_tree_model.pkl', 'rb') as f:
    model = joblib.load(f)

@app.route('/predict', methods=['GET', 'POST'])
def predict():
    # ket noi database

    requestData = request.get_json()
    print(f"======={requestData}=========")
    names = []
    values = []
    respondData = {}

    for key, val in requestData.items():
        names.append(key) # append the name
        values.extend(val) # append the value (flatten the list)

    values_reshaped = values
    predictions = model.predict(values_reshaped) # predict the values

    for i, key in enumerate(names):
        respondData[key] = predictions[i] # add to dictionary
    
    return jsonify(respondData)

if __name__ == '__main__':
    app.run(port=5000, debug=True)
