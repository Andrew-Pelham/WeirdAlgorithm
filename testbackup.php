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
				    	<a href='#karaoke' class="icon solid fa-microphone" target="_blank"><span>Karaoke</span></a>
						<a href="#database" class="icon solid fa-server"><span>Database</span></a>
					</nav>

				<!-- Main -->
					<div id="main">

						<!-- Homepage -->
							<article id="home" class="panel intro">
								<header>
									<h1>The Weird Algorithm</h1>
									<p>Generate lyrics for any parody!</p>
								</header>
								<a href="#search" class="jumplink pic">
									<span class="arrow icon solid fa-chevron-right"><span>See my work</span></span>
									<img src="images/weirdal.png" alt="" />
								</a>
							</article>

						<!-- Search Database -->
							<article id="search" class="panel">
								<p>
									
										
<form method="post" action="http://db8.cse.nd.edu/cse30246/weirdalgorithm/test.php#lyrics";>
Band: <input type="text" name="Artist">
Song Title: <input type="text" name="Song">
<input type="submit">
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
                        <!--            <form method="post" action="http://db8.cse.nd.edu/cse30246/weirdalgorithm/test.php#search";>
                                    Word To Rhyme: <input type="text" name="addword">
                                    <input type="submit">
                                    </form>  --!>

<?php
/*

if (!empty($_POST["addword"])) {
    $word = $_POST["addword"];
    $word = strtoupper($word);
    $word = "\"" . $word . "\"";


$sql = "SELECT rhyme from rhymeWords WHERE word = " . $word . ";";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $rhyme = $row["rhyme"];
}

$sql = "SELECT word from rhymeWords WHERE rhyme = \"" . $rhyme . "\";";
$result = mysqli_query($conn, $sql);
$nrows = mysqli_num_rows($result);
$therand = rand(0, $nrows);
$counter = 0;
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        if ($counter == $therand) {
            echo $row["word"] . " rhymes with " . $word . "<br>";
            break; 
        }
        $counter++;
    } 
}
} */
?>


								</p>
							</article>

						<!-- Edit Database -->
							<article id="database" class="panel">
								<header>
									<h2>Update or Add Words</h2>
								</header>
<?php /*
								<form action="#" method="post">
									<div>
										<div class="row">
											<div class="col-6 col-12-medium">
												<input type="text" name="name" placeholder="Name" />
											</div>
											<div class="col-6 col-12-medium">
												<input type="text" name="email" placeholder="Email" />
											</div>
											<div class="col-12">
												<input type="text" name="subject" placeholder="Subject" />
											</div>
											<div class="col-12">
												<textarea name="message" placeholder="Message" rows="6"></textarea>
											</div>
											<div class="col-12">
												<input type="submit" value="Send Message" />
											</div>
										</div>
									</div>
								</form> 
*/
?>

<form method="post" action="http://db8.cse.nd.edu/cse30246/weirdalgorithm/test.php#database">
Word: <input type="text" name="name">
Rhymes With: <input type="text" name="rhyme">
<input type="submit">
</form>

<?php if (!empty($_POST["name"]) && !empty($_POST["rhyme"]) ) { 
        $_POST["name"] = strtoupper($_POST["name"]); 
        $_POST["rhyme"] = strtoupper($_POST["rhyme"]);

        //Query DB to see if addition is necessary
        $sql = "SELECT word from rhymeWords where word = \"" . $_POST["name"] . "\";"; 
        $result = mysqli_query($conn, $sql);
        
        //Add value
        if (mysqli_num_rows($result) == 0 ) {
            $sql = "SELECT slant, rhyme, syllables, stress from rhymeWords where word = " . "\"" . $_POST["rhyme"] . "\";";
            //echo $sql;
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            
            //insert new value 
            $sql = "INSERT INTO rhymeWords VALUES (\"" . $_POST["name"] . "\", \"" . $row["slant"] . "\", \"" . $row["rhyme"] . "\", " . $row["syllables"] . ", " . $row["stress"] . ");";
            //INSERTION QUERY
            //Uncomment when ready to roll
            mysqli_query($conn, $sql);
            echo "Record Added";
        }
        else {
            //Update existing value
            $sql = "SELECT slant, rhyme, syllables, stress from rhymeWords where word = " . "\"" . $_POST["rhyme"] . "\";";
            //echo $sql;
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            $sql = "UPDATE rhymeWords SET rhyme = \"" . $row["rhyme"] . "\", slant = \"" . $row["slant"] . "\", syllables = " . $row["syllables"] . ", stress = " . $row["stress"] . " WHERE word = \"" . $_POST["name"] . "\"";
            echo "Record Updated";
            mysqli_query($conn, $sql);
        }
 
    foreach ($_SESSION["lyrarray"] as $row) {
        foreach ($row as $word) {
            echo $word . " ";
        }
        echo "<br>";
    }
    }
    elseif (strcmp($_SERVER['REQUEST_METHOD'], "POST") == 0) {
        echo "Name and rhyme required to update or add<br>";
    }
?>
							</article>

                <!--Karaoke--!>
                <article id="karaoke" class="panel">
               
    <?php
    if (isset($_POST["Song"])) {
    $ytcomm = "./python/ytSearch.py " . $_POST["Artist"] . " " . $_POST["Song"];
    //echo $comm;
    $ytcomm = escapeshellcmd($ytcomm);
    $ytoutput = shell_exec($ytcomm);
    $ytlink = "https://www.youtube.com/embed/";
    #$ytlink = $ytlink .  str_replace("https://www.youtube.com/watch?v=", "", $ytoutput);
    $_SESSION["yt"] = $ytoutput;
    echo '<div style="text-align:center"> <a href="' . $ytoutput . '" target="_blank" rel="noopener noreferrer"> <button style="height:100px;width:500px">Launch Music Player</button> </a> </div>';
    
       # echo      '  <iframe width="560" height="315" src=$ytlink title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> ';
    }
    elseif (isset($_SESSION["yt"])) {
        echo '<div style="text-align:center"> <a href="' . $_SESSION["yt"] . '" target="_blank" rel="noopener noreferrer"> <button style="height:100px;width:500px">Launch Music Player</button> </a> </div>';
    }

    if (isset($_SESSION["totalLyrics"])) {
        #echo $_SESSION["totalLyrics"];

                echo ' <div id="bigtext" style="text-align:center"> <textarea id="tLyrics" name="tLyrics" rows="10" cols="75" readonly>';
    
                if (isset($_POST["totalLyrics"])) {
                    echo $_POST["totalLyrics"];
                }
                elseif (isset($biglyrics)) {
                    echo $biglyrics;
                }
                

                echo '</textarea> </div>';    
                echo '<br>';             
    }
    ?>
                <br>
                </article> 

                <!--Lyrics--!>
                
				<article id="lyrics" class="panel">
                <header>
                    <h2>Select Lyrics to Change</h2>
                </header>
               

<?php /*
                <form action="http://db8.cse.nd.edu/cse30246/weirdalgorithm/test.php#lyrics" method="post"> 
                    <input type="text" id="toRhyme" name="toRhyme" placeholder="Word To Rhyme">
                    <button type="button">Generate Rhymes</button>
                </form>


<form action="" method="post">
Enter your name: <input type="text" name="sixe" />
Enter your age: <input type="text" name="eighty" />
<input type="submit" />
</form>

                
                echo "<select name = '' id='' style='width: 50px;'> 
                <option value=''>test</option> 
                <option value=''>test2</option>
                </select> <br> <br>"; */

        if (isset($_SESSION{"lyrarray"})) {

                $biglyrics = "";

            
                foreach($_SESSION["lyrarray"] as $row) {
                    foreach ($row as $word) {
                        $biglyrics .= $word;
                        $biglyrics .= ' ';
                    }
                    #$biglyrics .= "&#10;";
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


    
    $word = $_POST["wordToRhyme"];
    $word = strtoupper($word);
    $word = "\"" . $word . "\"";
$word = str_replace(array("#", "'", ";", ")", "(", ",", "?", '.', '!', ' '), '', $word);
$word = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$word);
$word = preg_replace('/\s+/u', '', $word);
$word = ltrim($word);

$sql = "SELECT rhyme from rhymeWords WHERE word = " . $word . ";";
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

    echo "<textarea readonly>";
    while($row = mysqli_fetch_assoc($result)) {
       # if ($counter == $therand) {
            echo $row["word"] . "&#10;" ; # . " rhymes with " . $word . "<br>";
        #    break; 
       # }
       # $counter++;
    } 
    echo "</textarea>";
} 
}
else {
    echo "no rhymes";
}



}


                 

                if (isset($_SESSION["lyrarray"])) {
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
                echo "</form>";
        }
        else {
            echo "Error: Invalid Song or Artist";
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
