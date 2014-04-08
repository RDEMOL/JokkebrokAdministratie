<?php
require_once(dirname(__FILE__)."/../page.php");
class UitstappenPage extends Page{
    public function __construct(){
        parent::__construct("Uitstappen", "", "uitstappen");
    }
    public function printContent(){
    	include (dirname(__FILE__)."/../../public_html/pages/uitstappen/content.html");
    	$content = "";
    	if(isset($_REQUEST['UitstapId'])){
			$id = $_REQUEST['UitstapId'];
$content .= <<<HERE
<script>
init_function = function(){
	console.log("in init!");
	var data = new Object();
	data.Id = $id;
	$.post('?action=data&data=uitstapDetails', data, function(resp){
		console.log("got data: "+JSON.stringify(resp));
		laad_uitstap(JSON.parse(resp).content);
	});
};
</script>
HERE;
		}else{
			$content .= <<<HERE
<script>
init_function = function(){
			console.log("calling M. placeholder!");
		laad_uitstap_details_placeholder();};
</script>
HERE;
		}
		echo $content;
    }
}
?>
