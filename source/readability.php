<?php
if (preg_match("/readability.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");
    /*

        TextStatistics Class
        https://github.com/DaveChild/Text-Statistics

        Released under New BSD license
        http://www.opensource.org/licenses/bsd-license.php

        Calculates following readability scores (formulae can be found in wiki):
          * Flesch Kincaid Reading Ease
          * Flesch Kincaid Grade Level
          * Gunning Fog Score
          * Coleman Liau Index
          * SMOG Index
          * Automated Reability Index

        Will also give:
          * String length
          * Letter count
          * Syllable count
          * Sentence count
          * Average words per sentence
          * Average syllables per word

        Sample Code
        ----------------
        $statistics = new TextStatistics;
        $text = 'The quick brown fox jumped over the lazy dog.';
        echo 'Flesch-Kincaid Reading Ease: ' . $statistics->flesch_kincaid_reading_ease($text);

    */

    class TextStatistics {

        protected $strEncoding = ''; // Used to hold character encoding to be used by object, if set

        /**
         * Constructor.
         *
         * @param string  $strEncoding    Optional character encoding.
         * @return void
         */
        public function __construct($strEncoding = '') {
            if ($strEncoding <> '') {
                // Encoding is given. Use it!
                $this->strEncoding = $strEncoding;
            }
        }

        /**
         * Gives the Flesch-Kincaid Reading Ease of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        public function flesch_kincaid_reading_ease($session) {
            global $db_conn;
            $sth = $db_conn->prepare("SELECT flesch1 FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$flesch1 = $sth->fetchColumn();     
			if ($flesch1 == 0) {
				$flesch1 = round((206.835 - (1.015 * $this->average_words_per_sentence($session)) - (84.6 * $this->average_syllables_per_word($session))), 1);
			}
			return $flesch1;
        }

        /**
         * Gives the Flesch-Kincaid Grade level of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        public function flesch_kincaid_grade_level($session) {
            global $db_conn;
            $sth = $db_conn->prepare("SELECT flesch2 FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$flesch2 = $sth->fetchColumn();     
			if ($flesch2 == 0) {
				$flesch2 = round(((0.39 * $this->average_words_per_sentence($session)) + (11.8 * $this->average_syllables_per_word($session)) - 15.59), 1);	
			}
			return $flesch2;
        }

        /**
         * Gives the Gunning-Fog score of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        public function gunning_fog_score($session,$formalcount) {
			global $db_conn;
            $sth = $db_conn->prepare("SELECT fog FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$fog = $sth->fetchColumn();     
			if ($fog == 0) {
				$fog = round((($this->average_words_per_sentence($session) + $this->percentage_words_with_three_syllables($session, $formalcount)) * 0.4), 1);
			}
			return $fog;
        }

        /**
         * Gives the Coleman-Liau Index of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        public function coleman_liau_index($session) {
			global $db_conn;
            $sth = $db_conn->prepare("SELECT coleman FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$coleman = $sth->fetchColumn();     
			if ($coleman == 0) {
				$wordcount = $this->word_count($session);
				$coleman = round( ( (5.89 * ($this->letter_count($session) / $wordcount)) - (0.3 * ($this->sentence_count($session) / $wordcount)) - 15.8 ), 1);
			}
			return $coleman;
        }

        /**
         * Gives the SMOG Index of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        public function smog_index($session,$formalcount) {
			global $db_conn;
            $sth = $db_conn->prepare("SELECT smog FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$smog = $sth->fetchColumn();     
			if ($smog == 0) {
				$smog = round(1.043 * sqrt(($this->words_with_three_syllables($session,$formalcount) * (30 / $this->sentence_count($session))) + 3.1291), 1);
			}
			return $smog;
        }

        /**
         * Gives the Automated Readability Index of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        public function automated_readability_index($session) {
			global $db_conn;
            $sth = $db_conn->prepare("SELECT automated FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$automated = $sth->fetchColumn();     
			if ($automated == 0) {
				$wordcount = $this->word_count($session);
				$automated = round(((4.71 * ($this->letter_count($session) / $wordcount)) + (0.5 * ($wordcount / $this->sentence_count($session))) - 21.43), 1);
			}
			return $automated;
		}

        /**
         * Gives string length. Tries mb_strlen and if that fails uses regular strlen.
         * @param   strText      Text to be measured
         */  
         public function text_length($strText) {
            $intTextLength = 0;
            try {
                if ($this->strEncoding == '') {
                    $intTextLength = mb_strlen($strText);
                } else {
                    $intTextLength = mb_strlen($strText, $this->strEncoding);
                }
            } catch (Exception $e) {
                $intTextLength = strlen($strText);
            }
            return $intTextLength;
        }

        /**
         * Gives letter count (ignores all non-letters). Tries mb_strlen and if that fails uses regular strlen.
         * @param   strText      Text to be measured
         */
        public function letter_count($session) {
			global $db_conn;
			$sth = $db_conn->prepare("SELECT cleaned FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$strText = $sth->fetchColumn();
            $intTextLength = 0;
            $strText = preg_replace('/[^A-Za-z]+/', '', $strText);
            try {
                if ($this->strEncoding == '') {
                    $intTextLength = mb_strlen($strText);
                } else {
                    $intTextLength = mb_strlen($strText, $this->strEncoding);
                }
            } catch (Exception $e) {
                $intTextLength = strlen($strText);
            }
            return $intTextLength;
        }
        
        /**
         * Converts string to lower case. Tries mb_strtolower and if that fails uses regular strtolower.
         * @param   strText      Text to be transformed
         */
        protected function lower_case($strText) {
            $strLowerCaseText = '';
            try {
                if ($this->strEncoding == '') {
                    $strLowerCaseText = mb_strtolower($strText);
                } else {
                    $strLowerCaseText = mb_strtolower($strText, $this->strEncoding);
                }
            } catch (Exception $e) {
                $strLowerCaseText = strtolower($strText);
            }
            return $strLowerCaseText;
        }

        /**
         * Converts string to upper case. Tries mb_strtoupper and if that fails uses regular strtoupper.
         * @param   strText      Text to be transformed
         */
        protected function upper_case($strText) {
            $strUpperCaseText = '';
            try {
                if ($this->strEncoding == '') {
                    $strUpperCaseText = mb_strtoupper($strText);
                } else {
                    $strUpperCaseText = mb_strtoupper($strText, $this->strEncoding);
                }
            } catch (Exception $e) {
                $strUpperCaseText = strtoupper($strText);
            }
            return $strUpperCaseText;
        }

        /**
         * Gets portion of string. Tries mb_substr and if that fails uses regular substr.
         * @param   strText      Text to be cut up
         * @param   intStart     Start character
         * @param   intLength    Length
         */
        protected function substring($strText, $intStart, $intLength) {
            $strSubstring = '';
            try {
                if ($this->strEncoding == '') {
                    $strSubstring = mb_substr($strText, $intStart, $intLength);
                } else {
                    $strSubstring = mb_substr($strText, $intStart, $intLength, $this->strEncoding);
                }
            } catch (Exception $e) {
                $strSubstring = substr($strText, $intStart, $intLength);
            }
            return $strSubstring;
        }

        /**
         * Returns sentence count for text.
         * @param   strText      Text to be measured
         */
        public function sentence_count($session) {
			global $db_conn;
			$sth = $db_conn->prepare("SELECT cleaned,sentences FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$result = $sth->fetch();
            if ($result[1] == 0) {
				// Will be tripped up by "Mr." or "U.K.". Not a major concern at this point.
				$intSentences = max(1, $this->text_length(preg_replace('/[^\.!?]/', '', $result[0])));
					$stmt = $db_conn->prepare("UPDATE tempt SET sentences=:sentences WHERE session=:session");
					$stmt->bindParam(':sentences', $intSentences);
					$stmt->bindParam(':session', $session);
					$stmt->execute();
			} else {
				$intSentences = $result[1];
			}
            return $intSentences;
        }

        /**
         * Returns word count for text.
         * @param   strText      Text to be measured
         */
        public function word_count($session) {
			global $db_conn;
			$sth = $db_conn->prepare("SELECT wortotal FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$intWords = $sth->fetchColumn();
            if ($intWords == 0) {   
				$sth = $db_conn->prepare("SELECT SUM(count) FROM tempw WHERE session='".$session."'");
				$sth->execute();
				$intWords = $sth->fetchColumn();
					$stmt = $db_conn->prepare("UPDATE tempt SET wortotal=:wnumber WHERE session=:session");
					$stmt->bindParam(':wnumber', $intWords);
					$stmt->bindParam(':session', $session);
					$stmt->execute();
			}
            return $intWords;
        }
        
        public function word_count_raw($strText) {
            // Will be tripped by by em dashes with spaces either side, among other similar characters
            $intWords = 1 + $this->text_length(preg_replace('/[^ ]/', '', $strText)); // Space count + 1 is word count
            return $intWords;
        }

        /**
         * Returns average words per sentence for text.
         * @param   strText      Text to be measured
         */
        public function average_words_per_sentence($session) {
			global $db_conn;
            $sth = $db_conn->prepare("SELECT woraverage FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$average = $sth->fetchColumn();         
            if ($average == 0) { 
				$intSentenceCount = $this->sentence_count($session);
				$intWordCount = $this->word_count($session);
				$average = round(($intWordCount/$intSentenceCount),2);
					$stmt = $db_conn->prepare("UPDATE tempt SET woraverage=:woraverage WHERE session=:session");
					$stmt->bindParam(':woraverage', $average);
					$stmt->bindParam(':session', $session);
					$stmt->execute();
			}
            return $average;
        }

        /**
         * Returns total syllable count for text.
         * @param   strText      Text to be measured
         */
        public function total_syllables($session) {
            global $db_conn;
            $sth = $db_conn->prepare("SELECT syltotal FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$intSyllableCount = $sth->fetchColumn();         
            if ($intSyllableCount == 0) { 
				$stmt = $db_conn->prepare("SELECT word,count FROM tempw WHERE session='".$session."'");
				$stmt -> execute();
				while ($word = $stmt->fetch()) {
					$intSyllableCount += ($this->syllable_count($word[0]))*$word[1];
				}
				$stmt = $db_conn->prepare("UPDATE tempt SET syltotal=:syltotal WHERE session=:session");
				$stmt->bindParam(':syltotal', $intSyllableCount);
				$stmt->bindParam(':session', $session);
				$stmt->execute();
			}
            return $intSyllableCount;
        }

        /**
         * Returns average syllables per word for text.
         * @param   strText      Text to be measured
         */
        public function average_syllables_per_word($session) {
            global $db_conn;	
            $sth = $db_conn->prepare("SELECT sylaverage FROM tempt WHERE session='".$session."'");
			$sth->execute();
			$average = $sth->fetchColumn();         
            if ($average == 0) { 
				$intWordCount = $this->word_count($session);
				$intSyllableCount = $this->total_syllables($session);
				$average = round(($intSyllableCount/$intWordCount),2);
					$stmt = $db_conn->prepare("UPDATE tempt SET sylaverage=:sylaverage WHERE session=:session");
					$stmt->bindParam(':sylaverage', $average);
					$stmt->bindParam(':session', $session);
					$stmt->execute();	
			}
            return $average;
        }

// my function, checks whether the word is long or not
public function word_with_three_syllables($word, $blnCountProperNouns) {
	if ($this->syllable_count($word) > 2) {
                    if ($blnCountProperNouns) {
                        $long = true;             
                    } else {
                        $strFirstLetter = $this->substring($word, 0, 1);
                        if ($strFirstLetter !== $this->upper_case($strFirstLetter)) {
                            // First letter is lower case. Count it.
                            $long = true;
                        } else {
							$long = false;
						} 
                    }                 
	} else {
      $long = false;
	}
    return ($long);
}

        /**
         * Returns the number of words with more than three syllables
         * @param   strText                  Text to be measured
         * @param   blnCountProperNouns      Boolean - should proper nouns be included in words count
         */
        public function words_with_three_syllables($session,$formalcount) {
			global $db_conn;
			if ($formalcount) {
				$where = "(tempw.longw='1' OR tempw.formalw='1')";
			} else {
				$where = "tempw.longw='1'";
			}
			$sth = $db_conn->prepare("SELECT SUM(tempw.count), tempt.longwnumber FROM tempw, tempt WHERE ".$where." AND tempw.session='".$session."' AND tempt.session='".$session."'");
			$sth->execute();
			$longwnumber = $sth->fetch();
				if ($longwnumber[0] != $longwnumber[1] || $longwnumber[1] == 0) {
					$stmt = $db_conn->prepare("UPDATE tempt SET longwnumber=:longnumber WHERE session=:session");
					$stmt->bindParam(':longnumber', $longwnumber[0]);
					$stmt->bindParam(':session', $session);
					$stmt->execute();
				}
            return ($longwnumber[0]);
        }

        /**
         * Returns the percentage of words with more than three syllables
         * @param   strText      Text to be measured
         * @param   blnCountProperNouns      Boolean - should proper nouns be included in words count
         */
        public function percentage_words_with_three_syllables($session,$formalcount) {
			global $db_conn;
			if ($formalcount) {
				$where = "(tempw.longw='1' OR tempw.formalw='1')";
			} else {
				$where = "tempw.longw='1'";
			}
			$sth = $db_conn->prepare("SELECT SUM(tempw.count), tempt.longwpercent FROM tempw, tempt WHERE ".$where." AND tempw.session='".$session."' AND tempt.session='".$session."'");
			$sth->execute();
			$longwpercent = $sth->fetch();
				if ($longwpercent[0] > 0 && $longwpercent[1] == 0) {
					$intWordCount = $this->word_count($session);
					$intLongWordCount = $this->words_with_three_syllables($session,$formalcount);
					$intPercentage = round((($intLongWordCount / $intWordCount) * 100),2);
						$stmt = $db_conn->prepare("UPDATE tempt SET longwpercent=:longwpercent WHERE session=:session");
						$stmt->bindParam(':longwpercent', $intPercentage);
						$stmt->bindParam(':session', $session);
						$stmt->execute();
				} else {
					$intPercentage = $longwpercent[1];
				}
            return ($intPercentage);
        }

        /**
         * Returns the number of syllables in the word.
         * Based in part on Greg Fast's Perl module Lingua::EN::Syllables
         * @param   strWord      Word to be measured
         */
        public function syllable_count($strWord) {

            // Should be no non-alpha characters
            $strWord = preg_replace('/[^A_Za-z]/' , '', $strWord);

            $intSyllableCount = 0;
            $strWord = $this->lower_case($strWord);

            // Specific common exceptions that don't follow the rule set below are handled individually
            // Array of problem words (with word as key, syllable count as value)
            $arrProblemWords = Array(
                 'simile' => 3
                ,'forever' => 3
                ,'shoreline' => 2
                ,'plaque' => 1
            );
            if (isset($arrProblemWords[$strWord])) {
                return $arrProblemWords[$strWord];
            }

            // These syllables would be counted as two but should be one
            $arrSubSyllables = Array(
                 'cial'
                ,'tia'
                ,'cius'
                ,'cious'
                ,'giu'
                ,'ion'
                ,'iou'
                ,'sia$'
                ,'[^aeiuoyt]{2,}ed$'
                ,'.ely$'
                ,'[cg]h?e[rsd]?$'
                ,'que$'
                ,'rved?$'
                ,'[aeiouy][dt]es?$'
                ,'[aeiouy][^aeiouydt]e[rsd]?$'
                //,'^[dr]e[aeiou][^aeiou]+$' // Sorts out deal, deign etc
                ,'[aeiouy]rse$' // Purse, hearse
            );

            // These syllables would be counted as one but should be two
            $arrAddSyllables = Array(
                 'ia'
                ,'riet'
                ,'dien'
                ,'iu'
                ,'io'
                ,'ii'
                ,'[aeiouym]bl$'
                ,'[aeiou]{3}'
                ,'^mc'
                ,'ism$'
                ,'([^aeiouy])\1l$'
                ,'[^l]lien'
                ,'^coa[dglx].'
                ,'[^gq]ua[^auieo]'
                ,'dnt$'
                ,'uity$'
                ,'ie(r|st)$'
            );

            // Single syllable prefixes and suffixes
            $arrPrefixSuffix = Array(
                 '/^un/'
                ,'/^fore/'
                ,'/ly$/'
                ,'/less$/'
                ,'/ful$/'
                ,'/ers?$/'
                ,'/ings?$/'
            );

            // Remove prefixes and suffixes and count how many were taken
            $strWord = preg_replace($arrPrefixSuffix, '', $strWord, -1, $intPrefixSuffixCount);

            // Removed non-word characters from word
            $strWord = preg_replace('/[^a-z]/is', '', $strWord);
            $arrWordParts = preg_split('/[^aeiouy]+/', $strWord);
            $intWordPartCount = 0;
            foreach ($arrWordParts as $strWordPart) {
                if ($strWordPart <> '') {
                    $intWordPartCount++;
                }
            }

            // Some syllables do not follow normal rules - check for them
            // Thanks to Joe Kovar for correcting a bug in the following lines
            $intSyllableCount = $intWordPartCount + $intPrefixSuffixCount;
            foreach ($arrSubSyllables as $strSyllable) {
                $intSyllableCount -= preg_match('/' . $strSyllable . '/', $strWord);
            }
            foreach ($arrAddSyllables as $strSyllable) {
                $intSyllableCount += preg_match('/' . $strSyllable . '/', $strWord);
            }
            $intSyllableCount = ($intSyllableCount == 0) ? 1 : $intSyllableCount;
            return $intSyllableCount;
        }

    }
?>
