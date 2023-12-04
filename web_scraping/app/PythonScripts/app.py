
from flask import Flask, request, Response
import subprocess

app = Flask(__name__)

@app.route('/run-scraper', methods=['GET'])
def run_scraper():
    gender = request.args.get('gender', default='heren')
    category = request.args.get('category', default='schoenen')
    sort = request.args.get('sort', default='populairste')

    command = [

        'python',
        '/app/scraper.py',
        '--gender',
        gender,
        '--category',
        category,
        '--sort',
        sort,
    ];

    process = subprocess.Popen(command, stdout=subprocess.PIPE, stderr=subprocess.STDOUT, text=True)

    output, _ = process.communicate()

    # Return the output as the API response
    return Response(output, content_type='text/plain', status=200)

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5000)
