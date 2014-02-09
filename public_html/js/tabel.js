define(['tabel/kolom', 'tabel/rij'], function(Kolom, Rij){
	var Tabel = function(url, kolommen){
		this.url = url;
		this.kolommen = kolommen;
		this.data = new Array();
		this.tabelBody = $('<tbody>');
		this.setFilter(new Object());
		this.filterRij = null;
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
	Tabel.prototype.setFilterRij = function(filterRij){
		this.filterRij = filterRij;
	};
	Tabel.prototype.laadTabel = function(){
		var self = this;
		var data = new Object();
		data.filter = this.filter;
		console.log("data to be sent: "+JSON.stringify(data));
		$.post(this.url, data, function(d){
			console.log("received: "+d);
			self.data = JSON.parse(d).content;
			self.updateBody();
		});
	};
	Tabel.prototype.getTHead = function(){
		var headTR = $('<tr>');
		for(var i = 0; i < this.kolommen.length; ++i){
			headTR.append($('<th>').html(this.kolommen[i].getHeadContent()));
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
		for(var i = 0; i < this.data.length; ++i){
			var rij = new Rij(this.data[i], this);
			this.tabelBody.append(rij.getElement());
		}
		console.log("body updated");
	}
	//TODO: only update tbody
	return Tabel;
});