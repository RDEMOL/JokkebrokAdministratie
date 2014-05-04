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
	Validator.isPositiveInteger = function(data){
		return Validator.isInteger(data) && data > 0;
	};
	Validator.isNumber = function(data){
		return $.isNumeric(data);
	};
	Validator.isGoodYear = function(data){
		return Validator.isInteger(data) && data > 1900 && data < 10000;
	};
	Validator.isPositivePayment = function(data){
		return Validator.isNumber(data) && data > 0 && (Math.floor(data*100) == data*100);
	};
	Validator.isGoodMonth = function(data){
		return Validator.isInteger(data) && parseInt(data,10) > 0 && parseInt(data, 10) <= 12;
	};
	Validator.isGoodDayOfMonth = function(data){
		return Validator.isInteger(data) && parseInt(data, 10) > 0 && parseInt(data, 10) <= 31;
	};
	Validator.isGoodDate = function(data){
		var parts = data.split("-");
		if(parts.length != 3)
			return false;
		if(!Validator.isGoodYear(parts[0])){
			return false;
		}
		if(!Validator.isGoodMonth(parts[1]) || !Validator.isGoodDayOfMonth(parts[2])){
			return false;
		}
		return true;
	};
	return Validator;
});
