# import requests

# url =  "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key="

# headers = {
#     "Content-Type": "application/json"
# }

# # Body của request (dạng JSON)
# data = {
#     "contents": [{
#     "parts":[
#         {"text": "List 5 day holidays in VietNam"}
#         ]
#     }],
#     "generationConfig": {
#         "response_mime_type": "application/json",
#         "response_schema": {
#         "type": "ARRAY",
#         "items": {
#             "type": "OBJECT",
#             "properties": {
#             "holiday_name": {"type":"STRING"},
#             "holiday_date": {"type": "STRING", 'format': "date-time"}
#             }
#         }
#         }
#     }
# }

# # Gửi request POST
# response = requests.post(url, json=data, headers=headers)

# # In kết quả phản hồi
# print("Status Code:", response.status_code)
# print("Response:", response.json())  # Nếu response là JSON

# ung dung model
import joblib

# Tải model từ file
model = joblib.load("models\\decision_tree_model.pkl")
# Giả sử dữ liệu đầu vào mới cho mô hình
new_data = [[10, 5, 3, 0.8, 0.9]]
# Thay thế bằng dữ liệu thực tế của bạn
# Đảm bảo dữ liệu có cùng số features và thứ tự với dữ liệu training ban đầu
# Dự đoán kết quả
predictions = model.predict(new_data)

# # In kết quả
# print(predictions)