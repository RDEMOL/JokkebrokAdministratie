define(['./rij'], function(Kolom){
	var FilterRij = function(filter_velden, tabel){
		this.parent_tabel = tabel;
		this.filter_velden = filter_velden;
		for(var i = 0; i < filter_velden.length; ++i){
			this.filter_velden[i].setParentFilterRij(this);
		}
		this.element = $('<tr>')
		this.update();
		this.notify();
	};
	FilterRij.prototype.notify = function(){
		this.parent_tabel.setFilter(this.getFilter());
	}
	FilterRij.prototype.getFilter = function(){
		var filter = new Object();
		for(var i = 0; i < this.filter_velden.length; ++i){
			this.filter_velden[i].updateFilter(filter);
		}
		return filter;
	};
	FilterRij.prototype.update = function(){
		this.element.empty();
		var aantal_kolommen = 0;
		for(var i = 0; i < this.filter_velden.length; ++i){
			aantal_kolommen += this.filter_velden[i].getSpan();
			this.element.append(this.filter_velden[i].getElement());
		}
		console.log("parent kolommen amount = "+this.parent_tabel.getKolommenAmount());
		for(var i = aantal_kolommen; i < this.parent_tabel.getKolommenAmount(); ++i){
			this.element.append($('<td>'));
		}
	};
	FilterRij.prototype.getElement = function(){
		return this.element;
	};
	return FilterRij;
});
