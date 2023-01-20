#!/usr/bin/env python3

import urllib.request
import re
import sys

# Main Execution

def main():
    #search_keyword = input().rstrip()
    search_keyword = sys.argv[1:]
    #search_keyword = search_keyword.split()
    search_keyword = '+'.join(filter(str.isalpha, search_keyword))
    html = urllib.request.urlopen("https://www.youtube.com/results?search_query=" + search_keyword + "karaoke")
    video_ids = re.findall(r"watch\?v=(\S{11})", html.read().decode())
    print("https://www.youtube.com/watch?v=" + video_ids[0])

if __name__ == '__main__':
    main()
