define(function () {
    var Control = function (label, className, handler) {
        this.label = label;
        this.className = className;
        this.handler = handler;
    };
    Control.prototype.getElement = function (data) {
        var self = this;
        var el = $('<button>').text(this.label)
            .addClass(this.className)
            .click(function (e) {
                self.handler(data);
                return false;
            });
        return el;
    };
    return Control;
});
