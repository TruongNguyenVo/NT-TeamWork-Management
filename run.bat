@echo off
start /B symfony server:start --port=8386

cd api
source venv/bin/activate
python app.py