define(function () {
    var FilterVeld = function (id, span, type, data, custom_filter, start_value) {
        this.id = id;
        this.span = span;
        this.type = type;
        this.element = $('<td>');
        this.input_element = null;
        this.default_value = "";
        if (custom_filter) {
            this.getFilter = custom_filter;
        }
        this.start_value = start_value;
        this.setData(data);
    };
    FilterVeld.prototype.getSpan = function () {
        return this.span;
    };
    FilterVeld.prototype.setData = function (data) {
        this.data = data;
        this.update();
    };
    FilterVeld.prototype.setParentFilterRij = function (parent_filter_rij) {
        this.parent_filter_rij = parent_filter_rij;
    };
    FilterVeld.prototype.notify = function () {
        if (this.parent_filter_rij) {
            this.parent_filter_rij.notify();
        }
    };
    FilterVeld.prototype.update = function () {
        this.element.empty();
        this.element.attr('colspan', this.span);
        var el = null;
        var self = this;
        switch (this.type) {
            case 'text':
                el = $('<input>').attr('type', 'text').attr('name', this.id).addClass('form-control');
                el.keyup(function () {
                    self.notify();
                });
                break;
            case 'select':
                el = $('<select>').attr('name', this.id).addClass('form-control');
                this.default_value = this.data.options[0].value;
                for (var i = 0; i < this.data.options.length; ++i) {
                    el.append($('<option>').val(this.data.options[i].value).text(this.data.options[i].label));
                }
                el.change(function () {
                    self.notify();
                });
                break;
            case 'datepicker':
                var d = new Date();
                var formatter = function (num) {
                    if (num < 10) {
                        num = "0" + num;
                    }
                    return num;
                };
                this.default_value = d.getFullYear() + "-" + formatter(d.getMonth() + 1) + "-" + formatter(d.getDate());
                this.element.append($('<button>').addClass('btn btn-sm').append($('<span>').addClass('glyphicon glyphicon-remove')).click(function () {
                    var d = new Date();
                    self.input_element.val(this.default_value);
                    self.input_element.datepicker('update');
                    self.input_element.val('');
                    self.notify();
                }));
                this.element.append('&nbsp;');
                this.element.append($('<button>').addClass('btn btn-sm').append($('<span>').addClass('glyphicon glyphicon-backward')).click(function () {
                    var d = new Date($.trim(self.input_element.val()));
                    d.setDate(d.getDate() - 1);
                    self.input_element.val(d.getFullYear() + "-" + formatter(d.getMonth() + 1) + "-" + formatter(d.getDate()));
                    self.input_element.datepicker('update');
                    self.notify();
                }));
                this.element.append('&nbsp;');
                this.element.append($('<button>').addClass('btn btn-sm').append($('<span>').addClass('glyphicon glyphicon-calendar')).click(function () {
                    self.input_element.val(self.default_value);
                    self.input_element.datepicker('update');
                    self.notify();
                }));
                this.element.append('&nbsp;');
                this.element.append($('<button>').addClass('btn btn-sm').append($('<span>').addClass('glyphicon glyphicon-forward')).click(function () {
                    var d = new Date($.trim(self.input_element.val()));
                    d.setDate(d.getDate() + 1);
                    self.input_element.val(d.getFullYear() + "-" + formatter(d.getMonth() + 1) + "-" + formatter(d.getDate()));
                    self.input_element.datepicker('update');
                    self.notify();
                }));
                this.element.append('&nbsp;');
                el = $('<input>').attr({
                    'name': this.id,
                    'type': 'text'
                }).addClass('form-control').css('width', '100px').css('display', 'inline');
                $(document).ready(function () {
                    el.datepicker({format: 'yyyy-mm-dd'}).on('changeDate', function () {
                        el.datepicker('hide');
                        self.notify();
                    });
                    el.val(self.default_value);
                    el.datepicker('update');
                });

                el.change(function () {
                    self.notify();
                });
                break;
            default:
                break;
        }
        if (this.start_value) {
            el.val(this.start_value);
        }
        this.input_element = el;
        this.element.append(el);
    };
    FilterVeld.prototype.updateFilter = function (filter) {
        if (this.input_element.val() != "") {
            filter[this.id] = this.input_element.val();
        }
    };
    FilterVeld.prototype.getElement = function () {
        return this.element;
    };
    FilterVeld.prototype.setValue = function (v) {
        if (this.input_element) {
            if (v == null) {
                this.input_element.val(this.default_value);
            } else {
                this.input_element.val(v);
            }
        }
    };
    return FilterVeld;
});