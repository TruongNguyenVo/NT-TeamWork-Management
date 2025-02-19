import requests

url =  "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key="

headers = {
    "Content-Type": "application/json"
}

# Body của request (dạng JSON)
data = {
    "contents": [{
    "parts":[
        {"text": "List 5 day holidays in VietNam"}
        ]
    }],
    "generationConfig": {
        "response_mime_type": "application/json",
        "response_schema": {
        "type": "ARRAY",
        "items": {
            "type": "OBJECT",
            "properties": {
            "holiday_name": {"type":"STRING"},
            "holiday_date": {"type": "STRING", 'format': "date-time"}
            }
        }
        }
    }
}

# Gửi request POST
response = requests.post(url, json=data, headers=headers)

# In kết quả phản hồi
print("Status Code:", response.status_code)
print("Response:", response.json())  # Nếu response là JSON