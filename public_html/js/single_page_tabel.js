/**
 * Created by fkint on 16/01/15.
 */
define(["tabel", "tabel/rij"], function (Tabel, Rij) {
    var SinglePageTabel = function (url, kolommen) {
        Tabel.call(this, url, kolommen);
        this.rows_loaded = 0;
    };
    SinglePageTabel.prototype = Object.create(Tabel.prototype);
    SinglePageTabel.default_rows_per_time = 20;

    SinglePageTabel.prototype.load_rows = function () {
        this.load_next_rows();
    };
    SinglePageTabel.prototype.load_next_rows = function () {
        var self = this;
        var start = this.rows_loaded;
        var amount = SinglePageTabel.default_rows_per_time;
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

    return SinglePageTabel;
})