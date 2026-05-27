from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/process', methods=['POST'])
def process() :
    data = request.json
    text = data['upper_text']
    result_upper = f"python processed: {text.upper()}"
    return jsonify({'result_upper' : result_upper})

@app.route('/reverse', methods=['POST'])
def reverse() :
    data = request.json
    text = data['reverse_text']
    result_reverse = text[::-1]
    return jsonify({'result_reverse' : result_reverse})

if __name__ == '__main__':
    app.run(port=5000)