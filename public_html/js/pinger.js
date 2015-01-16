/**
 * Created by fkint on 16/01/15.
 */

define([], function () {
    var Pinger = function (url, action) {
        this.url = url;
        this.action = action;
    };
    Pinger.prototype.start = function () {
        var self = this;
        this.interval_id = window.setInterval(function () {
            $.get(self.url, function (data) {
                if (!data.session) {
                    self.action();
                }
            }, "json");
        }, 1000);
    };
    Pinger.prototype.stop = function () {
        window.clearInterval(this.interval_id);
    };
    return Pinger;
});