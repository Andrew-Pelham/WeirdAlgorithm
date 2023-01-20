#!/usr/bin/env python3

import requests
import sys
from html.parser import HTMLParser
import re

# Global Variables

datalist = []

# Classes

class MyHTMLParser(HTMLParser):
    def handle_starttag(self, tag, attrs):
        pass

    def handle_endtag(self, tag):
        pass

    def handle_data(self, data):
        datalist.append(data)

# Main Execution

def main():
    
    line = sys.argv[1:]
    line = ''.join(line)
    line = line.split("@")
    artist = line[0]
    artist = ''.join(filter(str.isalnum, (artist).lower()))
    title = line[1]
    title = ''.join(filter(str.isalnum, (title).lower()))

    words = [] 

    # Generate HTML Request
    site = "https://www.azlyrics.com/lyrics/" + artist + "/" + title + ".html"
    r = requests.get(site)

    # Write Lyrics
    parser = MyHTMLParser()
    parser.feed(r.text)
    for line in datalist:
        if "HOT ALBUMS" in line:
            print("ERROR: INVALID LINK")
            return 1
                
    for index, line in enumerate(datalist):
        if "/Android|webOS|iPhone|iPod|iPad|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)" in line:
            break
        if index > 164:
            if line.rstrip():
                line = line.replace('"', "") 
                words.append(line.rstrip() + "@")

    print(' '.join(words))

if __name__ == '__main__':
    main()
