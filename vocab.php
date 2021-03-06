<?php

main();

// Represents a vocabulary entry (encapsulating word, part-of-speech, and definition).
class VocabEntry
{
	public function __construct($w, $p, $d)
	{
		$this->word = utf8_encode($w);
		$this->pos = utf8_encode($p);
		$this->definition = utf8_encode($d);
	}

	public function __get($property)
	{
		if (property_exists($this, $property))
		{
			return $this->$property;
		}
	}

	public function __set($property, $value)
	{
		if (property_exists($this, $property))
		{
			$this->$property = utf8_encode($value);
		}
	}

	public function to_array()
	{
		return array('word' => $this->word, 'pos' => $this->pos, 'definition' => $this->definition);
	}

	private $word = "";
	private $pos = "";
	private $definition = "";
}

function main()
{
	$filePath = "../../credentials.txt";
	$credentials = array();
	$table = "Vocab";
	$vocabEntries = array();
	$numWords = 10;

	// If a valid numWords value has been POSTed, use it.
	if (array_key_exists("numWordsBox", $_POST) && $_POST["numWordsBox"] != '')
	{
		$numWords = $_POST["numWordsBox"];
	}

	// Open credentials/information file.
	$infile = fopen($filePath, "r");

	// Read credentials/information from file.
	if ($infile)
	{
		while (!feof($infile))
		{
			$splitLine = explode(":", fgets($infile));
			$credentials[$splitLine[0]] = $splitLine[1];
		}
	
		// Retrieve database credentials/information from credential array.
		$host = trim($credentials['host']);
		$database = trim($credentials['database']);
		$user = trim($credentials['user']);
		$password = trim($credentials['password']);
	}

	// Connect to vocabulary database.
	// $connection = mysqli_connect($host, $user, $password, $database) or die("(BD3VT | ERROR): Unable to select database.");
	$connection = mysqli_connect("localhost", "root", "", "VocabularyTrainer") or die("(BD3VT | ERROR): Unable to select database.");
	$result = mysqli_query($connection, "SELECT * FROM " . $table . " ORDER BY RAND() LIMIT " . $numWords);

	// Fetch and store vocabulary entries.
	for ($i = 0; $row = mysqli_fetch_array($result); ++$i)
	{
		$vocabEntries[$i] = new VocabEntry($row['Word'], $row['PoS'], $row['Definition']);
	}

	// Build page HTML.
	build_html($vocabEntries);

	// Close databse connection and credentials/information file.
	mysqli_close($connection);
	if ($infile)
	{
		fclose($infile);
	}
}

// Builds main application page HTML.
function build_html($vocabEntries)
{
	$numWords = count($vocabEntries);

	// Gather vocabulary entries into a JSON-able format.
	$serializableVocabEntries = array();
	for ($i = 0; $i < $numWords; ++$i)
	{
		$serializableVocabEntries[$i] = $vocabEntries[$i]->to_array();
	}

	echo '
		<!DOCTYPE html>
		<html>
		<head>
			<link rel="stylesheet" href="style/vocab.css" type="text/css" />
			<script src="http://code.jquery.com/jquery-2.1.1.min.js" type="application/javascript"></script>
			<script src="http://ricostacruz.com/jquery.transit/jquery.transit.min.js" type="application/javascript"></script>
			<script src="js/vocab.js" type="application/javascript"></script>

			<title>stndrd.io | Vocabulary Trainer</title>
		</head>

		<body onload="main(', htmlentities(json_encode($serializableVocabEntries)), ')"">

			<div class="center">
				<div class="text0">Vocabulary Trainer</div>

				<p class="text2">
					Click/tap a word card to flip it over and expose the part of speech and definition. Click/tap it again to return it to its original position.
				</p>

				<span id="vocabTrainer">
					<form name="refreshForm" id="refreshForm" action="vocab.php" method="post">
						<input type="submit" name="refreshSubmit" class="button0" value="Refresh" />
						<input type="number" name="numWordsBox" id="numWordsBox" min="1" max="25" value="', $numWords, '" />
					</form>
					<span class="text1">Words</span>

					<div id="wordCardContainer">';

	// Fill vocabulary cards.
	for ($i = 0; $i < $numWords; ++$i)
	{
		echo '
						<button value="', $i, '" class="wordCard">',
							$vocabEntries[$i]->word,
						'</button>';
	}

	echo '	
					</div>
				</span>
			</div>

		</body>
		</html>';
}

?>