<!DOCTYPE html>

<html>

	<head>
	
		<title> Weird Al Gorithm </title>

	</head>

	<body>

		<h1> Weird Al Gorithm </h1>


		<p>	<b> Description: </b> The application we are planning to make is a song lyric generator. It would be able to make a parody lyric given a previous lyric, with a similar rhyme scheme and syllable structure/rhythm. In addition, it could create a new line of lyrics, given previous line(s). </p>

		<p> <b> Usefulness: </b> This application is prevalent in the art world - poets, writers, and musicians could use the program to generate verses or inspiration for lyrics. In terms of similarity, there are programs to see rhyming words, or to auto generate sentences, but none designed to do both. Moreover, there are no programs which actively change lyrics for use in parody. </p>

		<p> <b> Realness: </b> Our primary dataset tracks the structure of words - syllables, stress, and sounds. With this we can create sentences with a similar structure to previous sentences that would match the rhythm and beat. We have found several strong datasets to do this, such as the CMU pronunciation library, an ARPABET-based system that tracks the pronunciation (including stress and syllables) of over 100,000 words. </p>

		<p> <b> Basic Functions: </b> Because our dataset involves a dictionary of English words, we need to provide access to it due to the constantly-updating lexicon. Insertion of words is necessary, especially for terms like slang that would appear in song lyrics. Updating the words to reflect pronunciation is also important, or adding multiple pronunciations (gif comes to mind). Removal will be present as well, either to remove words that have changed in pronunciation, are unwanted or inappropriate, or mistakes. We also need to search to find matching words for our lyrics, potentially involving making a subset of matching words that the user can choose from, or finding multiple sets of close rhymes. </p>
		
		<p> <b> Advanced Functions: </b> The implementation will be the algorithm that picks replacement words to fit the lyrics. Because we are implementing song lyrics and not simply combinations of words, this will necessitate picking words that match the rhythm of the song, syllable count, and rhyme scheme. This will involve finding patterns in rhyming words using our CMU dictionary, as well as (if necessary) finding words that are similar but not exact in sound for partial rhymes. This particular function is interesting because it can help solve the problem of artist’s block, or expand on the creative ideas a writer would have. </p>

		<p> The second advanced function would be a function to ensure our lyrics make relevant sense. The main idea we would like to implement for this is to have lyrics stick to a theme and not just be random sentences. As an example, if the theme of the song is basketball, we might use words like court or ball to fit this. This involves creating a way to group similar words together and whether a word fits within the group. This could be done in a similar way to social media algorithms who can predict friends due to common links. This part is especially interesting because it extends the function of our program from single lines of lyric to creating much more in depth songs that make sense. </p>

		<p> <b> ER Diagram: </b> </p>


    <p>The ER Diagram describes the relationships present in our project. We have the overall lyrics, including their song name, rhyme scheme, length, and genre. Another relation is the words, including their theme, type, and spelling. The words make up the lyrics. Furthermore, there are syllables, with their stress and pronunication. The syllables compose the words, important in order to create the rhyme scheme and beat we need to match. Replaced words are part of the words relationship, which contain a theme. These will be what is replaced in the provided lyrics.</p>


<?php
   // $comm = "source vrt/bin/activate";
   // shell_exec($comm);
    $comm = "./vrt/testing.py";
    $output = shell_exec($comm);
    echo $output;
?>

</body>
</html>
