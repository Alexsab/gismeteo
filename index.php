<?

include('simple_html_dom.php');

$city = checkCityNumber($_GET["c"], checkCityNumber($_GET["city"], checkCityNumber($_GET["town"], "4618")));

function checkCityNumber($id, $ifFalse) {
	if(isset($id) && !empty($id) && (int) $id > 0) 
	{
		return $id;
	}
	return $ifFalse;
}

$html = file_get_html('http://www.gismeteo.ru/city/weekly/'.$city.'/');

function findAndRemove($value) {
	// Find all <div> with the id attribute
	// Remove a element, set it's outertext as an empty string 
	foreach($html->find('div'.$value) as $e)
		$e->outertext = '';
}


$arr=array('#header', '#cright', '#rbc', '.cright', '#footer', '#link_block', '.hslice', '#ad-lb', '#informer', '#ad-left', '#adfox_catfish', '#intown', '#graph', "#top_wide", ".adv_ico", "#weather-lb", "#weather-left");
$arr2=array('weather', 'weather-weekly', 'graph', 'astronomy');

//, '#iyp__topcontrol_div', '#inpage__browser_size_tooltip', '#iyp__browser_size_viz', '#iyp__topcontrol_spacer'

foreach ($arr as $value) {
	//findAndRemove($value);
	foreach($html->find('div'.$value) as $e)
		$e->outertext = '';
}

foreach($html->find('div#canvas') as $e)
	$e->outertext = '<div id="soft-promo"></div>' . $e->outertext;




// $html = preg_replace('/<!-- Google Analytics -->(.*)<!-- END Google Analytics -->/', '', $html);
$html = preg_replace("/'UA-12105830-1', 'gismeteo.ru'/", "'UA-36959087-9', 'auto'", $html);
$html = preg_replace('/<!-- Gismeteo Adfox banner TOP -->(.*?)<\/script>/', '', $html);
$html = preg_replace('/<!-- Gismeteo Adfox banner RIGHT_TOP -->(.*?)<\/script>/', '', $html);
$html = preg_replace('/<!--LiveInternet counter-->(.*)<!--\/LiveInternet-->/', '', $html);
$html = preg_replace('/<!-- Gismeteo Adfox banner CatFish -->(.*?)<\/script>/', '', $html);
$html = preg_replace('/<meta name="viewport" content="width=1000">/', '<meta name="viewport" content="width=474">', $html); //732
$html = preg_replace('/<meta name="MobileOptimized" content="1000">/', '<meta name="MobileOptimized" content="474"><base href="http://gismeteo.ru" target="_blank">', $html);
$html = preg_replace('/href="#"/', 'href="javascript:void(0);"', $html);

//echo count($html->find('script'));

echo $html;

//$html->clear(); 
unset($html);

		// желтая подложка «Перейти на мобильную версию»
		//document.querySelector('#mobile-version_checkbox').style.height = "50px"; // высота input
		
		//document.querySelector('.mobile-version').style.height = "50px";
		
		/*
		document.querySelector('.mobile-version_btn').style['font-size'] = "23px";
		document.querySelector('.mobile-version_btn').style['line-height'] = "32px";
		document.querySelector('.mobile-version_btn').style.height = "40px";
		document.querySelector('.mobile-version_btn').style.margin = "5px 80px 5px 5px";
		
		document.querySelector('.mobile-version_close').style['line-height'] = "47px";
		document.querySelector('.mobile-version_close').style['font-size'] = "57px";
		document.querySelector('.mobile-version_close').style.width = "80px";
		*/

?>

<script>
	setTimeout(function(){
		document.querySelector('#page.narrow #canvas').style.width = "100%";
		document.querySelector('#canvas').style.width = "100%";
		document.querySelector('#canvas').style['min-width'] = "100%";
		document.querySelector('#canvas').style['max-width'] = "100%";
		document.querySelector('#content').style.width = "100%";
		document.querySelector('#weather').style.float = "none";
		document.querySelector('#weather-weekly').style.float = "none";
		document.querySelector('#astronomy').style.float = "none";
		
		// желтая подложка «Перейти на мобильную версию»
		document.querySelector('.mobile-version').style['min-width'] = "474px";
		document.querySelector('.mobile-version_btn').style['font-size'] = "32px";
		document.querySelector('.mobile-version_btn').style['line-height'] = "47px";

	},500);
</script>
