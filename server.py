from http.server import HTTPServer
from http.server import SimpleHTTPRequestHandler
import json

class myHTTPRequestHandler(SimpleHTTPRequestHandler):
    def do_GET(self):
        return SimpleHTTPRequestHandler.do_GET(self)

    def do_POST(self):
        content_length = int(self.headers['Content-Length']) # <--- Gets the size of data
        post_data = self.rfile.read(content_length).decode('utf-8') # <--- Gets the data itself
        data = json.loads(post_data)
        print("coucou", data["noise"])

        


httpd = HTTPServer(('', 8080), myHTTPRequestHandler)

httpd.serve_forever()