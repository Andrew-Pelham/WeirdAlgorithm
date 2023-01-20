#!/usr/bin/env python3
import spacy
import sys

def main():

    args = ' '.join(sys.argv[1:])

    prev, rhymes = args.split('9')
    #prev = prev.split()
    rhymes = rhymes.split()

    
    prev = ' '.join(prev)


    nlp = spacy.load("en_core_web_lg")
    doc1 = nlp(prev)

    sims = []
    for arg in rhymes:
        doc2 = nlp(arg)
        sim = (arg, doc1.similarity(doc2))
        sims.append(sim)

    print(sorted(sims, key=lambda x: x[1], reverse=True))
        
   # doc2 = nlp("Chicken")

   # print(doc1, doc2, doc1.similarity(doc2))
   # print("Success")


if __name__ == '__main__':
    main()
