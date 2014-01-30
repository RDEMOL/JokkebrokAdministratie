define(function(){
	var KindRij = function(data){
		this.data = data;
	};
	KindRij.prototype.getVoornaamTD = function(){
		return $('<td>').text(this.data.voornaam);
	};
	KindRij.prototype.getNaamTD = function(){
		return $('<td>').text(this.data.naam);
	};
	KindRij.prototype.getWerkingTD = function(){
		return $('<td>').text(this.data.werking);
	};
	KindRij.prototype.getMedischeInfoTD = function(){
		return $('<td>').text(this.data.medische_info);
	};
	KindRij.prototype.getControlsTD = function(){
		var buttonWijzigen = $('<button>').addClass('btn btn-sm').text('Wijzigen');
		var buttonVerwijderen = $('<button>').addClass('btn btn-sm').text('Verwijderen');
		return $('<td>').append(buttonWijzigen).append('&nbsp;').append(buttonVerwijderen);
	};
	KindRij.prototype.getElement = function(){
		var rij = $('<tr>');
		var voornaamTD = this.getVoornaamTD();
		var naamTD = this.getNaamTD();
		var werkingTD = this.getWerkingTD();
		var medischeInfoTD = this.getMedischeInfoTD();
		var controlsTD = this.getControlsTD();
		rij.append(voornaamTD);
		rij.append(naamTD);
		rij.append(werkingTD);
		rij.append(medischeInfoTD);
		rij.append(controlsTD);
		return rij;
	};
	var Kinderen = function(){
		
	};
	Kinderen.prototype.setUp = function(tabel){
		this.tabel = tabel;
	};
	Kinderen.prototype.laadTabel = function(filter){
		var self = this;
		var data = new Object();
		data.filter = filter;
		$.post('index.php?action=data&data=kinderenTabel', data, function(data){
			console.log("received: "+data);
			self.data = JSON.parse(data);
			self.toonTabel();
		});
	};
	Kinderen.prototype.getTHead = function(){
		var headTR = $('<tr>');
		headTR.append('<th>Voornaam')
			.append('<th>Naam')
			.append('<th>Werking')
			.append('<th>Medische Info')
			.append('<th>');
		return $('<thead>').append(headTR);
	};
	Kinderen.prototype.toonTabel = function(){
		this.tabel.empty();
		this.tabel.append(this.getTHead());
		for(var i = 0; i < this.data.kinderen.length; ++i){
			console.log("i = "+i);
			var rij = new KindRij(this.data.kinderen[i]);
			this.tabel.append(rij.getElement());
		}
	};
	return Kinderen;
});
