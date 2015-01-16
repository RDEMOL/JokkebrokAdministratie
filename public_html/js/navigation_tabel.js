/**
 * Created by fkint on 16/01/15.
 */
define(["tabel", "tabel/navigation"], function (Tabel, Navigation) {
    var NavigationTabel = function (url, kolommen, amount) {
        Tabel.call(this, url, kolommen);
        this.navigation = new Navigation(0, 2, 0, this);
        this.data_change_listeners.push(this.navigation);
        this.pre_title_headers.push(this.navigation);
    };
    NavigationTabel.prototype = Object.create(Tabel.prototype);
    NavigationTabel.prototype.load_rows = function () {
        var self = this;
        var start = this.navigation.getFrom();
        var amount = this.navigation.getAmount();
        var end = Math.min(start + amount, this.data.length);
        for (var i = 0; i + start < end; ++i) {
            this.load_next_row(start + i);
        }
    };
    NavigationTabel.prototype.navigationUpdated = function () {
        this.updateBody();
    };
    return NavigationTabel;
});