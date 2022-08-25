from flask import Flask, request, jsonify
from converter import Converter
import threading
import requests

class RezzuminAPI(Flask):
    def __init__(self):
        super(RezzuminAPI, self).__init__(__name__)

    def start(self):
        self.init_endpoints()
        return self

    def init_endpoints(self):
        @self.route('/hello', methods=['GET'])
        def get_hello():
            return f"<h1>Test running at {Converter.debug()}</h1>\n"

        @self.route('/send_text', methods=['POST'])
        def send_text():
            data = request.get_json()
            threading.Thread(target=process_text, args=(data, )).start()
            return jsonify({"id": request.form.get("id"), "text": data.get("text")})

        def process_text(data):
            print("Processing text...")
            text = data.get("text")
            _id = data.get("id")
            percent = data.get("percent")
            ref = data.get("ref")
            print(f"Processing: {_id}, with {percent}% on:\n {text}")
            rez_converter = Converter(text, _id, percent).process()
            if not rez_converter.fail:
                answer = {'id': _id, 'body': rez_converter.result}
                x = requests.post(ref, data=answer)
                print(f"Request answer -> {x.content}")

    def run(self):
        try:
            super(RezzuminAPI, self).run(host="0.0.0.0", port=8181, debug=False)
        except RuntimeError as msg:
            if str(msg) == "Server going down":
                Mlog.INFO("Server going down")

    def shutdown_server(self):
        raise RuntimeError('Server going down')

    def stop(self):
        self.shutdown_server()

app = RezzuminAPI().start()

if __name__ == "__main__":
    api = RezzuminAPI()
    api.start()
    api.run()
    print("Done!")