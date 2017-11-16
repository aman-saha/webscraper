<?php
	//require_once('test/style.php');
	require_once('SentimentAnalyzer.php');
	/*	
		We instantiate the SentimentAnalyzerTest class below by passing in the SentimentAnalyzer object (class)
		found in the file: 'SentimentAnalyzer.class.php'.

		This class must be injected as a dependency into the constructor as shown below
		
	*/

	$sat = new SentimentAnalyzerTest(new SentimentAnalyzer());

	/*
		Training The Sentiment Analysis Algorithm with words found in the trainingSet directory

		The File 'data.neg' contains a list of sentences that's been marked 'Negative'.
		We use the words in this file to train the algorithm on how a negative sentence/sentiment might
		be structured.

		Likewise, the file 'data.pos' contains a list of 'Positive' sentences and the words are also
		used to train the algorithm on how to score a sentence or document as 'Positive'.

		The trainAnalyzer method below accepts three parameters:
			* param 1: The Location of the file where the training data are located
			* param 2: Used to describe the 'type' of file [param 1] is; used to indicate
					   whether the supplied file contians positive words or not
			* param 3: Enter a less than or equal to 0 here if you want all lines in the
					   file to be used as a training set. Enter any other number if you want to
					   use exactly those number of lines to train the algorithm

	*/

	$sat->trainAnalyzer('../trainingSet/data.neg', 'negative', 5000); //training with negative data
	$sat->trainAnalyzer('../trainingSet/data.pos', 'positive', 5000); //trainign with positive data

	/*
		The AnalyzeDocument method accepts the path to a text file as parameter.
		It analyzes the file and scores it as either a positive or a negative sentiment. It also
		returns an array with the same keys as the analyzeSentence method.

		An example is demonstrated below

	*/

		$documentLocation = '../../Flipkart-Review-Scraper/flireview.txt';
		$sentimentAnalysisOfDocument = $sat->analyzeDocument($documentLocation);
		$resultofAnalyzingDocument = $sentimentAnalysisOfDocument['sentiment'];
		$probabilityofDocumentBeingPositive = $sentimentAnalysisOfDocument['accuracy']['positivity'];
		$probabilityofDocumentBeingNegative = $sentimentAnalysisOfDocument['accuracy']['negativity'];
		require_once('test/presentation.php');
?>
