define(function(){
	var Rij = function(data, tabel){
		this.parent_tabel = tabel;
		this.element = $('<tr>');
		this.setData(data);
	};
	Rij.prototype.setData = function(data){
		this.data = data;
		this.update();
	};
	Rij.prototype.update = function(){
		this.element.empty();
		for(var i = 0; i < this.parent_tabel.kolommen.length; ++i){
			this.element.append(this.parent_tabel.kolommen[i].getElement(this.data));
		}
	};
	Rij.prototype.getElement = function(){
		return this.element;
	};
	return Rij;
});