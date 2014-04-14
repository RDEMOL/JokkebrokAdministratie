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
		var self = this;
		this.element.empty();
		var aantal_kolommen = 0;
		for(var i = 0; i < this.filter_velden.length; ++i){
			aantal_kolommen += this.filter_velden[i].getSpan();
			this.element.append(this.filter_velden[i].getElement());
		}
		/*for(var i = aantal_kolommen; i < this.parent_tabel.getKolommenAmount(); ++i){
			this.element.append($('<td>'));
		}*/
		this.element.append($('<td>').attr('colspan', this.parent_tabel.getKolommenAmount()-aantal_kolommen).append($('<button>').text('Reset filter').click(function(){
			for(var i = 0; i < self.filter_velden.length; ++i){
				self.filter_velden[i].setValue(null);
			}
			self.notify();
			return false;
		})));
	};
	FilterRij.prototype.getElement = function(){
		return this.element;
	};
	return FilterRij;
});
