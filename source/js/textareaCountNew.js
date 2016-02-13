var maxSyllables = 7000;
var minSyllables = 400;
var minWords = 20;

var textArea = document.getElementById('text');
var syllablesNumberElem = document.getElementById('syllablesNumber');
var wordsNumberElem = document.getElementById('wordsNumber');
var submitButton = document.getElementById('submit');
var clearButton = document.getElementById('clear');
var okSpanElem = document.getElementById('okSpan');

var syllablesNumber;
var wordsNumber;
submitButton.style.display = "none";
clearButton.style.display = "none";

textArea.oninput = function() {
  textArea.value = noToSpace(textArea.value);
  if (syllablesNumber > maxSyllables) {textArea.value = textArea.value.substring(0, maxSyllables+1);}
  
  var text = textArea.value;
      
  syllablesNumber = text.length;
  syllablesNumberElem.innerHTML = syllablesNumber + "/" + maxSyllables;
  
  if (syllablesNumber > minSyllables) {
    submitButton.style.display = "inline";
    clearButton.style.display = "inline";
    okSpanElem.innerHTML = "  OK!";
  } else if (syllablesNumber == 0) {
    submitButton.style.display = "none";
    clearButton.style.display = "none";
    okSpanElem.innerHTML = "";
  } else {
    submitButton.style.display = "none";
    clearButton.style.display = "inline";
    okSpanElem.innerHTML = "";
  }
  
  var textToCountWords = noToSpace(noToNumbersCount(deleteBr(text)));
  //noinspection UnnecessaryLocalVariableJS
  var wordsNumber = textToCountWords.split(" ").length;
  wordsNumberElem.innerHTML = wordsNumber;
};

clearButton.onclick = function() {
  var clearAnswer = confirm("Are you sure?");
  if (!clearAnswer) return false;
  submitButton.style.display = "none";
  clearButton.style.display = "none";
  okSpanElem.innerHTML = "";
  syllablesNumberElem.innerHTML = "0/" + maxSyllables; 
  wordsNumberElem.innerHTML = "0";
};

//Вспомогательные функции

function noToSpace(str){return str.replace(/ {1,}/g,' ');}

function deleteBr(str){return str.replace(/[\r\n]/g, ' ');}

function noToNumbersCount(str) {return str.replace(/[0-9]/g, '');}
