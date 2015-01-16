define(function () {
    var RowClickListener = function (handler) {
        this.handler = handler;
    };
    RowClickListener.prototype.rowClicked = function (row) {
        if (this.handler != null) {
            this.handler(row);
        }
    };
    RowClickListener.prototype.setHandler = function (handler) {
        this.handler = handler;
    };
    return RowClickListener;
});
