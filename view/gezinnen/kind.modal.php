<div class="modal fade" id="kindDetailsModal" tabindex="-1"  aria-labelledby="kindDetailsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="voogdModalTitle">Kind overzicht</h4>
            </div>
            <div class="modal-body">
                <h1 id="kindNaam"></h1>
                <h2>Voogden</h2>
                <table id="kind_voogden_tabel" class="table-bordered table-striped table">

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
    require(['tabel', 'tabel/kolom', 'tabel/row_click_listener', 'validator'], function (Tabel, Kolom, RowClickListener, Validator, require) {

        var kind_voogden_tabel;

        window.laad_kind_overzicht = function (kind_id) {
            $('#kindDetailsModal').modal('show');
            empty_fields();
            laad_kind_details(kind_id);
            laad_voogden_tabel(kind_id);
        };

        function empty_fields(){
            $("#kind_voogden_tabel").empty();
            $('h1#kindNaam').text('');
        }

        function laad_kind_details(kind_id){
            $.get("?action=data&data=kind&KindId="+kind_id, function(res){
                $('#kindNaam').text(res.Voornaam + " " +res.Naam);
            }, "json");
        }

        function laad_voogden_tabel(kind_id) {
            var k = new Array();
            k.push(new Kolom('Voornaam', 'Voornaam', null, false));
            k.push(new Kolom('Naam', 'Naam', null, false));
            k.push(new Kolom('Opmerkingen', 'Opmerkingen', null, false));

            kind_voogden_tabel = new Tabel('index.php?action=data&data=kindVoogdenTabel&KindId=' + kind_id, k);
            kind_voogden_tabel.setRowClickListener(new RowClickListener(function(rij){
                $('#kindDetailsModal').modal('hide');
                laad_voogd_overzicht(rij.getData().Id);
            }));
            kind_voogden_tabel.setUp($('#kind_voogden_tabel'));
            kind_voogden_tabel.laadTabel();
        }
    });
</script>