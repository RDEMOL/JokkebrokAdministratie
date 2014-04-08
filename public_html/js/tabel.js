//TODO: abort previous request when a new one has been started
define(['tabel/kolom', 'tabel/rij'], function(Kolom, Rij){
	var Tabel = function(url, kolommen){
		this.url = url;
		this.kolommen = kolommen;
		for(var i = 0; i < this.kolommen.length; ++i){
			this.kolommen[i].setParent(this);
		}
		this.data = new Array();
		this.tabelBody = $('<tbody>');
		this.filter = new Object();
		this.tabelElement = null;
		this.filterRij = null;
		this.sorting_settings = new Array();
	};
	Tabel.prototype.setUp = function(tabelElement){
		this.tabelElement = tabelElement;
		this.tabelElement.empty();
		this.tabelElement.append(this.getTHead());
		this.tabelBody = $('<tbody>');
		this.tabelElement.append(this.tabelBody);
		this.updateBody();
	};
	Tabel.prototype.setFilter = function(filter){
		this.filter = filter;
		this.laadTabel();
	};
	Tabel.prototype.getFilter = function(){
		return this.filter;
	};
	Tabel.prototype.setFilterRij = function(filterRij){
		this.filterRij = filterRij;
	};
	Tabel.prototype.laadTabel = function(){
		var self = this;
		if(!this.tabelElement){
			return;
		}
		var data = new Object();
		data.filter = this.filter;
		data.order = this.getSort();
		$.post(this.url, data, function(res){
			self.data = JSON.parse(res).content;
			self.updateBody();
		});
	};
	Tabel.prototype.getTHead = function(){
		var headTR = $('<tr>');
		for(var i = 0; i < this.kolommen.length; ++i){
			headTR.append(this.kolommen[i].getHeadTH());
		}
		var thead = $('<thead>').append(headTR);
		if(this.filterRij){
			thead.append(this.filterRij.getElement());
		}
		return thead;
	};
	Tabel.prototype.toonTabel = function(){
		if(!this.tabelElement){
			return;
		}
		this.tabelElement.empty();
		this.tabelElement.append(this.getTHead());
		this.tabelBody = $('<tbody>');
		this.tabelElement.append(this.tabelBody);
		this.updateBody();
	};
	Tabel.prototype.updateBody = function(){
		this.tabelBody.empty();
		if(!this.data)
			return;
		for(var i = 0; i < this.data.length; ++i){
			var rij = new Rij(this.data[i], this);
			if(this.getRowClickListener()){
				rij.setRowClickListener(this.getRowClickListener());
			}
			this.tabelBody.append(rij.getElement());
		}
	};
	Tabel.prototype.getRowClickListener = function(){
		return this.row_click_listener;
	};
	Tabel.prototype.setRowClickListener = function(row_click_listener){
		this.row_click_listener = row_click_listener;
		this.updateBody();
	};
	Tabel.prototype.getKolommenAmount = function(){
		return this.kolommen.length;
	};
	Tabel.prototype.deleteSortField = function(field){
		var move_to = 0;
		for(var i = 0; i < this.sorting_settings.length; ++i){
			this.sorting_settings[move_to]=this.sorting_settings[i];
			if(this.sorting_settings[i].Veld == field){
				//delete!
			}else{
				++move_to;
			}
		}
		this.sorting_settings.length = move_to;
	}
	Tabel.prototype.setSort = function(field, ordering){
		this.deleteSortField(field);
		if(ordering != ""){
			var obj = new Object();
			obj.Veld = field;
			obj.Order = ordering;
			this.sorting_settings.push(obj);
		}
		this.laadTabel();
	};
	Tabel.prototype.getSort = function(){
		return this.sorting_settings;
	};
	return Tabel;
});
