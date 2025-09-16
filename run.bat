@echo off
start cmd /k "symfony server:start"
start cmd /k "cd api && python app.py"
exit


