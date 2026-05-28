from flask import Flask, request, jsonify
from langdetect import detect, detect_langs, DetectorFactory

app = Flask(__name__)

# for everything that's sent to /process, this method will be executed
@app.route('/process', methods=['POST'])
def process() :
    data = request.json
    text = data.get('upper_text')
    result_upper = text.upper()
    return jsonify({'result_upper' : result_upper})

# # for everything that's sent to /reverse, this method will be executed. To reverse string
@app.route('/reverse', methods=['POST'])
def reverse() :
    data = request.json
    text = data.get('reverse_text')
    result_reverse = text[::-1]
    return jsonify({'result_reverse' : result_reverse})

# for everything that's sent to /process, this method will be executed. To show what language is the user inputted
@app.route('/lang_detect', methods=['POST'])
def translate():
    data = request.json
    text = data.get('predetect_text')
    result_detection = detect(text)
    return jsonify({'result_detection' : result_detection})

if __name__ == '__main__':
    app.run(port=5000)