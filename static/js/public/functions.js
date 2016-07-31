function isInteger(val) {
	var r = /^(-)?[0-9]*$/;
	return r.test(val);
}
function isNumber(val) { // check whether val is an integer or a decimal
	var r = /^(-)?[0-9]*(\.)?[0-9]*$/;
	return r.test(val);
}
function isAlpha(str) {
	var r = /^[a-zA-Z]*$/;
	return r.test(val);
}
