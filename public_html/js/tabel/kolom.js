define(function(){
	
	var Kolom = function(id, label, content_function){
		this.id = id;
		this.label = label;
		if(content_function == null){
			this.content_function = Kolom.prototype.default_content_function;
		}else{
			this.content_function = content_function;
		}
		
	};
	Kolom.prototype.getElement = function(data){
		return this.content_function(data);
	};
	Kolom.prototype.default_content_function = function(data){
		var text = data[this.id];
		if(!data[this.id])
			text = "";
		return $('<td>').text(text);
	};
	Kolom.prototype.getHeadContent = function(){
		return this.label;
	};
	return Kolom;
});
