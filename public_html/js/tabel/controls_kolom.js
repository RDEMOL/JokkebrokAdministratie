define(['./kolom'], function (Kolom) {
    var controls_content_function = function (data) {
        var element = $('<td>');
        for (var i = 0; i < this.controls.length; ++i) {
            element.append(this.controls[i].getElement(data));
            element.append('&nbsp;');
        }
        return element;
    };
    var ControlsKolom = function (controls) {
        this.controls = controls;
    };
    ControlsKolom.prototype = new Kolom('controls', '', controls_content_function);
    ControlsKolom.prototype.getHeadTH = function () {
        var el = $('<span>').css('visibility', 'hidden');
        for (var i = 0; i < this.controls.length; ++i) {
            el.append(this.controls[i].getElement(null));
            el.append('&nbsp;');
        }
        return $('<th>').append(el);
    };

    return ControlsKolom;
});
