define(function () {
    var Rij = function (data, tabel) {
        this.parent_tabel = tabel;
        this.element = $('<tr>');
        this.row_click_listener = null;
        this.setData(data);
    };
    Rij.prototype.setData = function (data) {
        this.data = data;
        this.update();
    };
    Rij.prototype.getData = function () {
        return this.data;
    };
    Rij.prototype.update = function () {
        var self = this;
        this.element = $('<tr>');
        if (this.getRowClickListener() != null) {
            this.element.click(function () {
                self.getRowClickListener().rowClicked(self);
            });
        }
        for (var i = 0; i < this.parent_tabel.kolommen.length; ++i) {
            this.element.append(this.parent_tabel.kolommen[i].getElement(this.data));
        }
    };
    Rij.prototype.getElement = function () {
        return this.element;
    };
    Rij.prototype.getRowClickListener = function () {
        return this.row_click_listener;
    };
    Rij.prototype.setRowClickListener = function (row_click_listener) {
        this.row_click_listener = row_click_listener;
        this.update();
    };
    return Rij;
});