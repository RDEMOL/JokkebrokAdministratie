define(['tabel/kolom'], function(Kolom){
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
			if(this.parent_tabel.kolommen[i].id=="controls"){
				this.element.append(this.parent_tabel.getControlsTD(this.data));
			}else{
				this.element.append(this.parent_tabel.kolommen[i].getElement(this.data));
			}
		}
	};
	var Tabel = function(url, kolommen){
		this.url = url;
		this.kolommen = kolommen;
		this.data = new Array();
		this.setFilter(new Object());
		this.controls = new Array();
	};
	Tabel.prototype.setUp = function(tabelElement){
		this.tabelElement = tabelElement;
	};
	Tabel.prototype.setFilter = function(filter){
		this.filter = filter;
	}
	Tabel.prototype.laadTabel = function(){
		var self = this;
		var data = new Object();
		data.filter = this.filter;
		$.post(this.url, data, function(data){
			console.log("received: "+data);
			self.data = JSON.parse(data).content;
			self.toonTabel();
		});
	};
	Tabel.prototype.getTHead = function(){
		var headTR = $('<tr>');
		for(var i = 0; i < this.kolommen.length; ++i){
			headTR.append($('<th>').text(this.kolommen[i].label));
		}
		return $('<thead>').append(headTR);
	};
	Tabel.prototype.toonTabel = function(){
		this.tabelElement.empty();
		this.tabelElement.append(this.getTHead());
		for(var i = 0; i < this.data.length; ++i){
			var rij = new Rij(this.data[i], this);
			this.tabelElement.append(rij.element);
		}
	};
	Tabel.prototype.getControlsTD = function(data){
		var controls = $('<td>');
		for(var i = 0; i < this.controls.length; ++i){
			controls.append(this.controls[i].getElement(data));
			controls.append('&nbsp;');
		}
		return controls;
	};
	Tabel.prototype.setControls = function(controls){
		this.controls = controls;
	}
	return Tabel;
});
