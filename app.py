from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/test-python', methods=['POST'])
def process() :
    data = request.json
    text = data['text']
    result = f"python processed: {text.upper()}"
    return jsonify({'result' : result})

@app.route('/test-python', methods=['POST'])
def reverse() :
    data = request.json
    text = data['text']
    result = text[::-1]
    return jsonify({'result' : result})

if __name__ == '__main__':
    app.run(port=5000)