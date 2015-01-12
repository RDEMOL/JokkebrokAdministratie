<div class="modal fade" id="voogdDetailsModal" tabindex="-1"  aria-labelledby="voogdDetailsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="voogdModalTitle">Voogd overzicht</h4>
            </div>
            <div class="modal-body">
                <h1 id="voogdNaam"></h1>
                <h2>Kinderen</h2>
                <table id="voogd_kinderen_tabel" class="table-bordered table-striped table">

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Sluiten
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    //var laad_voogd = null;
    require(['tabel', 'tabel/kolom', 'tabel/row_click_listener', 'validator'], function (Tabel, Kolom, RowClickListener, Validator, require) {

        var voogd_kinderen_tabel;

        window.laad_voogd_overzicht = function (voogd_id) {
            $('#voogdDetailsModal').modal('show');
            laad_voogd_details(voogd_id);
            laad_voogd_kinderen_tabel(voogd_id);
        };
        function laad_voogd_details(voogd_id){
            $.get("?action=data&data=voogd&VoogdId="+voogd_id, function(res){
                $("h1#voogdNaam").text(res.Voornaam+ " " + res.Naam);
            }, "json");
        }
        function empty_fields(){
            $('#voogd_kinderen_tabel').empty();
            $('h1#voogdNaam').text('');
        }

        function laad_voogd_kinderen_tabel(voogd_id) {
            var k = new Array();
            k.push(new Kolom('Voornaam', 'Voornaam', null, true));
            k.push(new Kolom('Naam', 'Naam', null, true));
            k.push(new Kolom('Geboortejaar', 'Geboortejaar', null, true));
            k.push(new Kolom('Werking', 'Werking'));
            k.push(new Kolom('Info', 'Extra Info', function (data) {
                var td = $('<td>');
                if (data['Belangrijk']) {
                    td.append($('<a>').attr({
                        'data-original-title': data['Belangrijk']
                    }).append($('<span>').addClass('glyphicon glyphicon-info-sign')).tooltip()).append('&nbsp;');
                }
                if (data['VoogdenIds'].length > 1) {
                    var voogden_string = "Meerdere voogden";
                    td.append($('<a>').attr({
                        'data-original-title': voogden_string
                    }).append($('<span>').addClass('glyphicon glyphicon-home')).tooltip()).append('&nbsp;');
                }
                if (data['Schulden']) {
                    td.append($('<a>').attr({
                        'data-original-title': 'Schulden!'
                    }).append($('<span>').addClass('glyphicon glyphicon-euro')).tooltip().click(function () {
                        laad_financien_modal(data);
                    })).append('&nbsp;');
                }

                var aanwezigheden_string = "";
                for (var i = 0; i < data['Aanwezigheden'].length; ++i) {
                    aanwezigheden_string += "Voogd '" + data['Aanwezigheden'][i]['Voogd'] + "': " + data['Aanwezigheden'][i]['Aanwezigheden'] + " dagen aanwezig.\n";
                }
                td.append($('<a>').attr({
                    'data-original-title': aanwezigheden_string
                }).append($('<span>').addClass('glyphicon glyphicon-envelope')).tooltip()).append('&nbsp;');
                return td;
            }));

            voogd_kinderen_tabel = new Tabel('index.php?action=data&data=voogdKinderenTabel&VoogdId=' + voogd_id, k);
            voogd_kinderen_tabel.setRowClickListener(new RowClickListener(function(rij){
                $('#voogdDetailsModal').modal('hide');
                laad_kind_overzicht(rij.getData().Id);
            }));
            voogd_kinderen_tabel.setUp($('#voogd_kinderen_tabel'));
            voogd_kinderen_tabel.laadTabel();
        }
    });
</script>