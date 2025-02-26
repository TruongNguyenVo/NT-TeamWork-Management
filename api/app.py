from flask import Flask, request, jsonify
import joblib
import connectDatabase
app = Flask(__name__)

# Load model
with open('models\\decision_tree_classificant_but_result_is_percent.pkl', 'rb') as f:
    model = joblib.load(f)

@app.route('/predict', methods=['POST'])
def predict():
    # ket noi database

    requestData = request.get_json()
    # print(f"======={requestData}=========")
    names = []
    values = []
    respondData = {}

    for key, val in requestData.items():
        names.append(key) # append the name
        values.extend(val) # append the value (flatten the list)

    values_reshaped = values
    predictions = model.predict_proba(values_reshaped) # predict the values
    predictions = [round(pred[0] * 100, 2) for pred in predictions] # convert to percentage and round to 2 decimal places

    for i, key in enumerate(names):
        respondData[key] = str(predictions[i]) + "%" # add to dictionary
    
    return jsonify(respondData)

if __name__ == '__main__':
    app.run(port=5000, debug=True)
