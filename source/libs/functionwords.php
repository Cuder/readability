<?php
$common = Array (
	// Prepositions
		'about',
		'across',
		'against',
		'along',
		'around',
		'at',
		'behind',
		'beside',
		'besides',
		'by',
		'despite',
		'down',
		'during',
		'for',
		'from',
		'in',
		'inside',
		'into',
		'near',
		'of',
		'off',
		'on',
		'onto',
		'over',
		'out',
		'through',
		'to',
		'toward',
		'with',
		'within',
		'without',
	// Pronouns
		'I',
		'you',
		'he',
		'me',
		'her',
		'him',
		'my',
		'mine',
		'her',
		'hers',
		'his',
		'myself',
		'himself',
		'herself',
		'anything',
		'everything',
		'anyone',
		'everyone',
		'ones',
		'such',
		'it',
		'we',
		'they',
		'us',
		'them',
		'our',
		'ours',
		'their',
		'theirs',
		'itself',
		'ourselves',
		'themselves',
		'something',
		'nothing',
		'someone',
	// Determiners
		'the',
		'some',
		'this',
		'that',
		'every',
		'all',
		'both',
		'one',
		'first',
		'other',
		'next',
		'many',
		'much',
		'more',
		'most',
		'several',
		'no',
		'a',
		'an',
		'any',
		'each',
		'no',
		'half',
		'twice',
		'two',
		'second',
		'another',
		'last',
		'few',
		'little',
		'less',
		'least',
		'own',
	// Conjunctions
		'and',
		'but',
		'after',
		'when',
		'as',
		'because',
		'if',
		'what',
		'where',
		'which',
		'how',
		'than',
		'or',
		'either',
		'nor',
		'neither',
		'so',
		'before',
		'since',
		'while',
		'although',
		'though',
		'who',
		'whose',
	// Modal Verbs
		'can',
		'may',
		'will',
		'shall',
		'could',
		'might',
		'would',
		'should',
		'must',
	// Primary Verbs
		'be',
		'was',
		'were',
		'been',
		'am',
		'is',
		'are',
		'being',
		'do',
		'does',
		'did',
		'doing',
		'have',
		'has',
		'had',
		'having',
	// Adverbs
		'here',
		'there',
		'today',
		'tomorrow',
		'now',
		'then',
		'always',
		'never',
		'sometimes',
		'usually',
		'often',
		'therefore',
		'however',
		'besides',
		'moreover',
		'though',
		'otherwise',
		'else',
		'instead',
		'anyway',
		'incidentally',
		'meanwhile',
	// particles
		'not',
	);

$functionWords = "'". implode("', '", $common) ."'";

$stmt = $db_conn->prepare("SELECT word,count FROM tempw WHERE word NOT IN ($functionWords) AND session='" . $session . "' ORDER BY count DESC LIMIT 10");
$stmt->execute();
$i = 0;
$popularWords = null;
while ($word = $stmt->fetch()) {
	$popularWords .= $word[0] . " (" . $word[1] . ")";
	if ($i == 9) {
		$popularWords .= ".";
	} else {
		$popularWords .= ", ";
	}
	$i++;
}
