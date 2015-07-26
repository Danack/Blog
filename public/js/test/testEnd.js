
var testsSummaryString = "Test summary";

if(testsRun == testsPassed){
	testsSummaryString = "All tests PASSED.";
}

else if (testsRun > testsPassed){
	testsSummaryString = "Some tests FAILED.";
}

alert(testsSummaryString + " Tests run = " + testsRun + ", tests pased " + testsPassed);
