@echo off
start /B symfony server:start --port=8386

cd api
source penv/Scripts/activate
python app.py