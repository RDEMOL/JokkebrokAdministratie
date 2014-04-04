define(function(){
	
	var Kolom = function(id, label, content_function, sortable){
		this.id = id;
		this.label = label;
		this.sortable = sortable;
		this.sortable_order = "";
		if(content_function == null){
			this.content_function = Kolom.prototype.default_content_function;
		}else{
			this.content_function = content_function;
		}
		this.parent_table = null;
	};
	Kolom.prototype.getSortable = function(){
		return this.sortable;
	};
	Kolom.prototype.sortChange = function(){
		if(this.getSortable()){
			switch(this.sortable_order){
				case "asc":
					this.sortable_order = "desc";
					break;
				case "desc":
					this.sortable_order = "";
					break;
				default:
					this.sortable_order = "asc";
					break;
			}
			if(this.getParent()){
				this.getParent().setSort(this.id, this.sortable_order);
			}
		}
	};
	Kolom.prototype.setParent = function(parent_table){
		this.parent_table = parent_table;
	};
	Kolom.prototype.getParent = function(){
		return this.parent_table;
	};
	Kolom.prototype.getElement = function(data){
		return this.content_function(data);
	};
	Kolom.prototype.default_content_function = function(data){
		var text = data[this.id];
		if(!data[this.id])
			text = "";
		var td = $('<td>').append($('<span>').text(text));
		return td;
	};
	Kolom.prototype.getHeadTH = function(){
		var self = this;
		var th = $('<th>').append($('<span>').text(this.label));
		if(this.getSortable()){
			th.click(function(){
				console.log("th click");
				self.sortChange();
			});
		}
		return th;
	};
	return Kolom;
});
