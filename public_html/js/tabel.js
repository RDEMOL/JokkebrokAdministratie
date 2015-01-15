//TODO: abort previous request when a new one has been started
define(['tabel/kolom', 'tabel/rij'], function (Kolom, Rij) {
    var Tabel = function (url, kolommen) {
        this.url = url;
        this.kolommen = kolommen;
        for (var i = 0; i < this.kolommen.length; ++i) {
            this.kolommen[i].setParent(this);
        }
        this.data = new Array();
        this.rows_loaded = 0;
        this.tabelBody = $('<tbody>');
        this.filter = new Object();
        this.tabelElement = null;
        this.filterRij = null;
        this.sorting_settings = new Array();
        this.rij_styler = null;
    };
    Tabel.default_rows_per_time = 20;
    Tabel.prototype.setUp = function (tabelElement) {
        this.tabelElement = tabelElement;
        this.tabelElement.empty();
        this.tabelElement.append(this.getTHead());
        this.tabelBody = $('<tbody>');
        this.next_rows_element = null;
        this.rows_loaded = 0;
        this.tabelElement.append(this.tabelBody);
        this.updateBody();
    };
    Tabel.prototype.setFilter = function (filter) {
        this.filter = filter;
        this.laadTabel();
    };
    Tabel.prototype.getFilter = function () {
        return this.filter;
    };
    Tabel.prototype.setFilterRij = function (filterRij) {
        this.filterRij = filterRij;
    };
    Tabel.prototype.startWaiting = function () {
        this.tabelBody.empty();
        this.tabelBody.append(
            $('<tr>').append(
                $('<td>').attr('colspan', '100%').css('text-align', 'center').append(
                    $('<i>').addClass('fa fa-cog fa-spin fa-5x')
                )
            )
        );
    };
    Tabel.prototype.laadTabel = function () {
        var self = this;
        if (!this.tabelElement) {
            return;
        }
        this.startWaiting();
        var data = new Object();
        this.rows_loaded = 0;
        data.filter = this.filter;
        data.order = this.getSort();
        var current_request_time = Date.now();
        this.last_request_time = current_request_time;
        $.post(this.url, data, function (res) {
            console.log("res = "+res);
            if(current_request_time == self.last_request_time) {
                self.data = JSON.parse(res).content;
                self.updateBody();
            }
        });
    };
    Tabel.prototype.getTHead = function () {
        var headTR = $('<tr>');
        for (var i = 0; i < this.kolommen.length; ++i) {
            headTR.append(this.kolommen[i].getHeadTH());
        }
        var thead = $('<thead>').append(headTR);
        if (this.filterRij) {
            thead.append(this.filterRij.getElement());
        }
        return thead;
    };
    Tabel.prototype.toonTabel = function () {
        if (!this.tabelElement) {
            return;
        }
        this.tabelElement.empty();
        this.tabelElement.append(this.getTHead());
        this.tabelBody = $('<tbody>');
        this.tabelElement.append(this.tabelBody);
        this.updateBody();
    };
    Tabel.prototype.load_next_row = function (index) {
        var rij = new Rij(this.data[index], this);
        if (this.getRowClickListener()) {
            rij.setRowClickListener(this.getRowClickListener());
        }
        var tr = rij.getElement();
        if (this.getRijStyler()) {
            (this.getRijStyler())(tr, this.data[index]);
        }
        this.tabelBody.append(tr);
        this.rows_loaded++;
    };
    Tabel.prototype.load_next_rows = function (amount) {
        var self = this;
        var start = this.rows_loaded;
        var end = Math.min(this.rows_loaded + amount, this.data.length);
        if (this.next_rows_element != null) {
            this.next_rows_element.remove();
            this.next_rows_element = null;
        }
        for (var i = this.rows_loaded; i < end; ++i) {
            this.load_next_row(i);
        }
        if (this.rows_loaded < this.data.length) {
            this.next_rows_element = $('<tr>').append(
                $('<td>')
                    .text('Klik voor de volgende rijen data...')
                    .css('text-align', 'center')
                    .click(function () {
                        console.log("klik");
                        self.load_next_rows(Tabel.default_rows_per_time);
                    })
                    .attr('colspan', this.kolommen.length)
            );
            this.tabelBody.append(this.next_rows_element);
        }
    };
    Tabel.prototype.updateBody = function () {
        this.tabelBody.empty();
        this.rows_loaded = 0;
        if (!this.data)
            return;
        this.load_next_rows(Tabel.default_rows_per_time);

    };
    Tabel.prototype.getRowClickListener = function () {
        return this.row_click_listener;
    };
    Tabel.prototype.setRowClickListener = function (row_click_listener) {
        this.row_click_listener = row_click_listener;
        this.updateBody();
    };
    Tabel.prototype.getKolommenAmount = function () {
        return this.kolommen.length;
    };
    Tabel.prototype.deleteSortField = function (field) {
        var move_to = 0;
        for (var i = 0; i < this.sorting_settings.length; ++i) {
            this.sorting_settings[move_to] = this.sorting_settings[i];
            if (this.sorting_settings[i].Veld == field) {
                //delete!
            } else {
                ++move_to;
            }
        }
        this.sorting_settings.length = move_to;
    }
    Tabel.prototype.setSort = function (field, ordering) {
        this.deleteSortField(field);
        if (ordering != "") {
            var obj = new Object();
            obj.Veld = field;
            obj.Order = ordering;
            this.sorting_settings.push(obj);
        }
        this.laadTabel();
    };
    Tabel.prototype.getSort = function () {
        return this.sorting_settings;
    };
    Tabel.prototype.setRijStyler = function (rij_styler) {
        this.rij_styler = rij_styler;
    };
    Tabel.prototype.getRijStyler = function () {
        return this.rij_styler;
    };
    return Tabel;
});
