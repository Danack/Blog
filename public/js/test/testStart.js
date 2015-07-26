
var testsRun = 0;
var testsPassed = 0;

var testErrors = [];

function assert(var1, var2){

	testsRun++;

	if(var1 != var2){
		//alert("assert failed " + var1 + " != " + var2 );
		testErrors.push("assert failed " + var1 + " != " + var2 );
	}
	testsPassed++;
}



