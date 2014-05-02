define([],function(){
	var Validator = function(){
		
	};
	Validator.isEmpty = function(data){
		return (data == null) || (data == "");
	};
	Validator.isNonZeroInteger = function(data){
		return !Validator.isEmpty(data) && Validator.isInteger(data) && data != 0;
	};
	Validator.isInteger = function(data){
		return Validator.isNumber(data) && Math.floor(data) == data;
	};
	Validator.isNumber = function(data){
		return $.isNumeric(data);
	};
	Validator.isBirthYear = function(data){
		return Validator.isInteger(data) && data > 1900 && data < 10000;
	}
	return Validator;
});
