@echo off
start /B symfony server:start --port=8386

cd api
source venv/Scripts/activate
python app.py