<!DOCTYPE HTML>

<html>
<?php include 'sql.php';
session_start();
 ?>
	<head>
		<title>The Weird Algorithm</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">

		<!-- Wrapper-->
			<div id="wrapper">

				<!-- Nav -->
					<nav id="nav">
						<a href="#" class="icon solid fa-home"><span>Home</span></a>
						<a href="#search" class="icon solid fa-search"><span>Search</span></a>
                        <a href="#lyrics" class="icon solid fa-edit"><span>Lyrics</span></a>
				    	<a href='#sample' class="icon solid fa-microphone" target="_blank"><span>Sample</span></a>
						<a href="#database" class="icon solid fa-server"><span>Database</span></a>
					</nav>

				<!-- Main -->
					<div id="main">

						<!-- Homepage -->
							<article id="home" class="panel intro">
								<header>
									<h1>The Weird Algorithm</h1>
									<p>The all in one tool for writing parody songs!</p>
								</header>
								<a href="#search" class="jumplink pic">
									<span class="arrow icon solid fa-chevron-right"><span>See my work</span></span>
									<img src="images/weirdal.png" alt="" />
								</a>
							</article>

						<!-- Search Database -->
							<article id="search" class="panel">	
										
<header>
	<h2>Song Search</h2>
</header>
<form method="post" action="http://db8.cse.nd.edu/cse30246/weirdalgorithm/test.php#lyrics";>
Title: <input type="text" name="Song"><br><br>
Artist: <input type="text" name="Artist"><br><br>
<input type="Submit">
</form>

<?php
if ((!empty($_POST["Artist"]) and !empty($_POST["Song"]) ) or isset($lyrarray) ) {
    $lyrarray = array();
    $nrow = 0;
    $ncol = 0;
    //echo $_POST["Artist"] . $_POST["Song"];
    #$comm = "echo '" . $_POST["Artist"] . " " . $_POST["Song"] . "' | ../python/lyricScraper.py ";
    $comm = "./python/lyricScraper.py " . $_POST["Artist"] . "@" . $_POST["Song"];
    //echo $comm;
    $comm = escapeshellcmd($comm);
    $output = shell_exec($comm);
   # echo $output;
    #echo $output;
    $lyricsarr = explode("@", $output);
    #echo $lyricsarr[1];
    $go = 1;
    foreach ($lyricsarr as $row) {
        #echo "$row <br>";
        $row = explode(" ", $row);
        
        foreach ($row as $word) {
            if ($go != 0) {
                #echo '  <button type=\"button\"> ' . $word . '</button>';
                $lyrarray[$nrow][$ncol] = $word;
                $ncol = $ncol + 1;
            }
            $go = 1;
        }
        $go = 0;
        #echo "<br>";
        $ncol = 0;
        $nrow = $nrow +  1;
    }
    if (strcmp($output, "ERROR: INVALID LINK\n") == 0) {
        #echo "Error: Invalid Artist or Song";
        unset($_POST);
        unset($_SESSION["lyrarray"]);
    }
    else {
        $_SESSION["lyrarray"] = $lyrarray;
    }
}

?>


								</p>
							</article>

						<!-- Edit Database -->
							<article id="database" class="panel">
								<header>
									<h2>Update or Add Words</h2>
								</header>

<form method="post" action="http://db8.cse.nd.edu/cse30246/weirdalgorithm/test.php#database">
Word: <input type="text" name="name"><br><br>
Rhymes With: <input type="text" name="rhyme"><br><br>
<input type="submit">
</form>

<?php if (!empty($_POST["name"]) && !empty($_POST["rhyme"]) ) { 
        $_POST["name"] = strtoupper($_POST["name"]); 
        $_POST["name"] = mysqli_real_escape_string($conn, $_POST["name"]);
        $_POST["rhyme"] = strtoupper($_POST["rhyme"]);
        $_POST["rhyme"] = mysqli_real_escape_string($conn, $_POST["rhyme"]);

        //Query DB to see if addition is necessary
        $sql = 'SELECT word from rhymeWords where word = "' . $_POST["name"] . '";'; 
        $result = mysqli_query($conn, $sql);
        
        //Add value
        if (mysqli_num_rows($result) == 0 ) {
            $sql = 'SELECT slant, rhyme, syllables, stress from rhymeWords where word = "'  . $_POST["rhyme"] . '"';
            //echo $sql;
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            
            //insert new value 
            $sql = 'INSERT INTO rhymeWords VALUES ("' . $_POST["name"] . '", "' . $row["slant"] . '", "' . $row["rhyme"] . '", ' . $row["syllables"] . ', ' . $row["stress"] . ');';
            //INSERTION QUERY
            mysqli_query($conn, $sql);
            echo "Record Added";
        }
        else {
            //Update existing value
            $sql = 'SELECT slant, rhyme, syllables, stress from rhymeWords where word = "' . $_POST["rhyme"] . '"';
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            $sql = 'UPDATE rhymeWords SET rhyme = "' . $row["rhyme"] . '", slant = "' . $row["slant"] . '", syllables = ' . $row["syllables"] . ', stress = ' . $row["stress"] . ' WHERE word = "' . $_POST["name"] . '"';
            echo "Record Updated";
            mysqli_query($conn, $sql);
        }
 
   } 
    elseif (strcmp($_SERVER['REQUEST_METHOD'], "POST") == 0) {
        echo "Valid word and rhyme required to update or add<br>";
    }
?>
							</article>

                <!--Sample--!>
                <article id="sample" class="panel">
               
								<header>
									
								</header>
    <?php
    if (isset($_POST["Song"])) {
    $ytcomm = "./python/ytSearch.py " . $_POST["Artist"] . " " . $_POST["Song"];
    //echo $comm;
    $ytcomm = escapeshellcmd($ytcomm);
    $ytoutput = shell_exec($ytcomm);
    $ytlink = "https://www.youtube.com/embed/";
    $_SESSION["yt"] = $ytoutput;
    echo '<div style="text-align:center"> <a href="' . $ytoutput . '" target="_blank" rel="noopener noreferrer"> <button style="height:100px;width:500px">Launch Music Player</button> </a> </div>';
    
    }
    elseif (isset($_SESSION["yt"])) {
        echo '<div style="text-align:center"> <a href="' . $_SESSION["yt"] . '" target="_blank" rel="noopener noreferrer"> <button style="height:100px;width:500px">Launch Music Player</button> </a> </div><br>';
    }

    if (isset($_POST["totalLyrics"])) {
        #echo $_SESSION["totalLyrics"];
				echo '<style>
.scroll-up {
 height: 2000px;	
 overflow: hidden;
 position: relative;
 background: white;
 color: black;
 border: 1px black;
}
.scroll-up p {
 position: absolute;
 width: 100%;
 height: 100%;
 margin: 0;
 line-height: 50px;
 text-align: center;
 /* Starting position */
 -moz-transform:translateY(100%);
 -webkit-transform:translateY(100%);	
 transform:translateY(100%);
 /* Apply animation to this element */	
 -moz-animation: scroll-up 200s linear infinite;
 -webkit-animation: scroll-up 200s linear infinite;
 animation: scroll-up 200s linear infinite;
}
/* Move it (define the animation) */
@-moz-keyframes scroll-up {
 0%   { -moz-transform: translateY(100%); }
 100% { -moz-transform: translateY(-100%); }
}
@-webkit-keyframes scroll-up {
 0%   { -webkit-transform: translateY(100%); }
 100% { -webkit-transform: translateY(-100%); }
}
@keyframes scroll-up {
 0%   { 
 -moz-transform: translateY(100%); /* Browser bug fix */
 -webkit-transform: translateY(100%); /* Browser bug fix */
 transform: translateY(10%); 		
 }
 100% { 
 -moz-transform: translateY(-100%); /* Browser bug fix */
 -webkit-transform: translateY(-100%); /* Browser bug fix */
 transform: translateY(-100%); 
 }
}
</style>

<div class="scroll-up">

<div class="scroll-left"><p>
';
				//echo ' <div id="bigtext" style="text-align:center"> <textarea id="tLyrics" name="tLyrics" rows="10" cols="75" readonly>';

                if (isset($_POST["totalLyrics"])) {
                    #echo $_POST["totalLyrics"];
                    $theLyrics = str_replace("\n", "<br>", $_POST["totalLyrics"]);
                    echo $theLyrics;
                }
                elseif (isset($_POST["biglyrics"])) {
                    #echo $biglyrics;
                    $theLyrics = str_replace("\n", "<br>", $_POST['biglyrics']);
                    echo $theLyrics;
                }
                

                echo '</p></div>'; 
                echo '<br>';          
    }


?>
                <br>
                </article> 

                <!--Lyrics--!>
                
				<article id="lyrics" class="panel">
                <header>
                    <h2>Edit Lyrics</h2>
                </header>
               

<?php 

        if (isset($_SESSION{"lyrarray"})) {
                $biglyrics = "";
 
                foreach($_SESSION["lyrarray"] as $row) {
                    foreach ($row as $word) {
                        $biglyrics .= $word;
                        $biglyrics .= ' ';
                    }
                }   
            
                if (isset($_POST["totalLyrics"])) {
                    $bigLyrics = $_POST["totalLyrics"];
                }
               
                echo "<form action='' method='post'>";
                echo ' <div id="bigtext" style="text-align:center"> <textarea id="totalLyrics" name="totalLyrics" rows="10" cols="75">';
    
                if (isset($_POST["totalLyrics"])) {
                    echo $_POST["totalLyrics"];
                }
                else {
                    echo $biglyrics;
                }

                echo '</textarea> </div>';    
                echo '<br>';             

                if (isset($_POST["totalLyrics"])) {
                    $_SESSION["totalLyrics"] = $_POST["totalLyrics"]; 
                }

                if (isset($_POST['wordToRhyme'])) {
                    #echo $_POST['wordToRhyme'];


    if (strcmp($_POST["wordToRhyme"], "Generate Rhymes") == 0) {
        $word = $_POST["manText"];
    }
    else {
    $word = $_POST["wordToRhyme"]; 
    }

    $word = strtoupper($word);
    #$word = '"' . $word . '"';
$word = str_replace(array("#", "'", ";", ")", "(", ",", "?", '.', '!', ' '), '', $word);
$word = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$word);
$word = preg_replace('/\s+/u', '', $word);
$word = ltrim($word);
$word = mysqli_real_escape_string($conn, $word);

$sql = 'SELECT rhyme from rhymeWords WHERE word = "' . $word . '";';
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $rhyme = $row["rhyme"];
$sql = "SELECT word from rhymeWords WHERE rhyme = \"" . $rhyme . "\";";
$result = mysqli_query($conn, $sql);
$nrows = mysqli_num_rows($result);
$therand = rand(0, $nrows);
$counter = 0;
if (mysqli_num_rows($result) > 0) {

    echo '<div>Rhymes';
    echo '<textarea readonly rows=4 cols=70 >';
    while($row = mysqli_fetch_assoc($result)) {
            echo $row["word"] . "&#10;"; 
    } 
    echo "</textarea>";
}

 
$sql = 'SELECT slant from rhymeWords WHERE word = "' . $word . '";';
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $slant = $row["slant"];
$sql = "SELECT word from rhymeWords WHERE slant = \"" . $slant . "\";";
$result = mysqli_query($conn, $sql);
$nrows = mysqli_num_rows($result);
$therand = rand(0, $nrows);
$counter = 0;
if (mysqli_num_rows($result) > 0) {

    echo 'Slant Rhymes<textarea readonly rows=4 cols=70>';
    while($row = mysqli_fetch_assoc($result)) {
            echo $row["word"] . "&#10;"; 
    } 
    echo "</textarea> </div> <br>";
} 
}
}
else {
    echo "No Rhymes Found";
}



}


                 

                if (isset($_SESSION["lyrarray"])) {
                #echo '<div id="ha"> <button type=button name="SaveLyrics" value=Save Lyrics> </button> </div><br> <br>';
                       echo '  <button>  <br> Save Lyrics <br> <br> </button> <br> <br>';
                foreach ($_SESSION["lyrarray"] as $row) {
                    echo '<div id="lol">';
                    foreach ($row as $word) {
                        echo '  <button type=\"button\" name="wordToRhyme" value="' . $word . '"> ' . $word . '</button>';
                        #echo $word . " ";
                    }


                    echo "</div>";
                    #echo '<input type="text" id="Name" size=75 name="Name" value=' . '"' . join(' ', $row) . '">';
                    }
                }
                    echo '<br> <br> Need to Rhyme a Different Word? Enter here: <input type="text" name="manText"> <br> <br> <input type="submit" name="wordToRhyme" value="Generate Rhymes" >'; 
                echo "</form>";

           //echo "<form method='post'> <input type='text' name='manText' id='manText'> <input type='submit' name='manButton' id='manButton' value='Generate Rhymes'> </form>"; 
        }
        else {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            echo "Error: Invalid Song or Artist";
            }
        else {
            echo "Please enter a song";
        }
            }
                ?> 
            
                </article>


					</div>


				<!-- Footer -->
					<div id="footer">
					
					</div>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
