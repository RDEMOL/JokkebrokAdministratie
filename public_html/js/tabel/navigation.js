/**
 * Created by fkint on 16/01/15.
 */

define([], function () {
    var Navigation = function (from, amount, total_amount, tabel) {
        var self = this;
        this.tabel = tabel;
        this.from = from;
        this.amount = amount;
        this.total_amount = total_amount;
        this.btnFirst = $('<button>')
            .addClass('btn btn-default')
            .append($('<span>').addClass('glyphicon glyphicon-fast-backward'))
            .click(function () {
                self.goToFirst();
            });
        this.btnPrevious = $('<button>')
            .addClass('btn btn-default')
            .append($('<span>').addClass('glyphicon glyphicon-step-backward'))
            .click(function () {
                self.goToPrevious();
            });
        this.txtCurrent = $('<span>').text("");
        this.btnNext = $('<button>')
            .addClass('btn btn-default')
            .append($('<span>').addClass('glyphicon glyphicon-step-forward'))
            .click(function () {
                self.goToNext();
            });
        this.btnLast = $('<button>')
            .addClass('btn btn-default')
            .append($('<span>').addClass('glyphicon glyphicon-fast-forward'))
            .click(function () {
                self.goToLast();
            });
        this.element = $('<tr>')
            .append($('<td>')
                .attr('colspan', '100%')
                .append(this.btnFirst)
                .append(this.btnPrevious)
                .append(this.txtCurrent)
                .append(this.btnNext)
                .append(this.btnLast)
        );
    };
    Navigation.prototype.getElement = function () {
        return this.element;
    };
    Navigation.prototype.dataChanged = function (data) {
        this.total_amount = data.length;
        this.navigationUpdated();
    };
    Navigation.prototype.getAmount = function () {
        return this.amount;
    };
    Navigation.prototype.getFrom = function () {
        return this.from;
    };
    Navigation.prototype.navigationUpdated = function () {
        var last = Math.min(this.from + this.amount, this.total_amount);
        this.txtCurrent.text(" " + (this.from + 1) + " tot " + last + " van " + this.total_amount + " ")
        this.btnFirst.removeClass('disabled');
        this.btnPrevious.removeClass('disabled');
        this.btnNext.removeClass('disabled');
        this.btnLast.removeClass('disabled');
        if (this.from == 0) {
            this.btnPrevious.addClass('disabled');
            this.btnFirst.addClass('disabled');
        }
        if (last == this.total_amount) {
            this.btnNext.addClass('disabled');
            this.btnLast.addClass('disabled');
        }
    };
    Navigation.prototype.goToFirst = function () {
        this.from = 0;
        this.navigate();
    };
    Navigation.prototype.goToPrevious = function () {
        this.from = Math.max(0, this.from - this.amount);
        this.navigate();
    };
    Navigation.prototype.goToNext = function () {
        this.from = Math.min(this.total_amount - this.total_amount % this.amount, this.from + this.amount);
        if (this.from == this.total_amount) {
            this.from = Math.max(0, this.from - this.amount);
        }
        this.navigate();
    };
    Navigation.prototype.goToLast = function () {
        this.from = this.total_amount - this.total_amount % this.amount;
        if (this.from == this.total_amount) {
            this.from = Math.max(0, this.from - this.amount);
        }
        this.navigate();
    };
    Navigation.prototype.navigate = function () {
        this.navigationUpdated();
        this.tabel.navigationUpdated();
    };
    return Navigation;
});