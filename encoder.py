#!/usr/bin/env python3

import csv

# Functions

def main():
    words = [line.rstrip().split() for line in open("cmudict-0.7b", "r", encoding="ISO-8859-1")]
    f = open('words.csv', 'w', newline='')
    writer = csv.writer(f)
    for entry in words:
        word = entry.pop(0)
        rhyme = ''
        endRhyme = ''
        endIndex = 0
        stress = ''
        syllCount = 0
        for index, syl in enumerate(entry, 0):
            if len(syl) == 3:
                endIndex = index
                rhyme += syl[0:2]
                endRhyme += syl[0:2]
                stress += syl[2]
                syllCount += 1
        for index, syl in enumerate(entry, 0):
            if index > endIndex:
                endRhyme += syl
        writer.writerow([word, rhyme, endRhyme, syllCount, stress])
        print(word)
        print(rhyme)
        print(endRhyme)
        print(syllCount)
        print(stress)

# Main execution

if __name__ == '__main__':
    main()
