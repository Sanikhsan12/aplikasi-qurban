@echo off
cd /d "%~dp0"
echo Installing dependencies...
pip install -r requirements.txt
echo Starting Flask server on port 5000...
python app.py
pause
