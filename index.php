<?

header("Content-type: text/html; charset=utf-8");

include('simple_html_dom.php');

$city = checkCityNumber($_GET["c"], checkCityNumber($_GET["city"], checkCityNumber($_GET["town"], "4618")));

function checkCityNumber($id, $ifFalse) {
	if(isset($id) && !empty($id) && (int) $id > 0) 
	{
		return $id;
	}
	return $ifFalse;
}

/**
 * https://www.gismeteo.ru/city/gm/4368/ — геомагнитная обстановка
 * https://www.gismeteo.ru/month/4368/ — на месяц
 * https://www.gismeteo.ru/city/hourly/4368/ — почасовая
 */
$html = file_get_html('http://www.gismeteo.ru/city/weekly/'.$city.'/');


$arr=array('section.media_top', 'header.header', 'div.content_overlay', '.right_col_1', '.right_col_2', 'footer.footer', '#printME', 'div.news_frame', 'div.media_with_map', 'div.cities', 'div.breadcrumbs');
$arrParent = array('.media_frame', '.media_middle');

foreach ($arr as $value) {
	foreach($html->find($value) as $e)
		$e->outertext = '';
}

foreach ($arrParent as $value) {
	foreach($html->find($value) as $e)
		$e->parent()->outertext = '';
}

// $html = preg_replace('/<!-- Google Analytics -->(.*)<!-- END Google Analytics -->/', '', $html);
$html = preg_replace("/UA-12105830-1/", "UA-36959087-9", $html);
$html = preg_replace("/39370095/", "41410069", $html);
$html = preg_replace('/<!-- Gismeteo Adfox banner TOP -->(.*?)<\/script>/', '', $html);
$html = preg_replace('/<!-- Gismeteo Adfox banner LEFT -->(.*?)<\/script>/', '', $html);
$html = preg_replace('/<!-- Gismeteo Adfox banner RIGHT_TOP -->(.*?)<\/script>/', '', $html);
$html = preg_replace('/<!--LiveInternet counter-->(.*)<!--\/LiveInternet-->/', '', $html);
$html = preg_replace('/<!-- Gismeteo Adfox banner CatFish -->(.*?)<\/script>/', '', $html);
$html = preg_replace('/name="viewport" content="width=1000"/', 'name="viewport" content="width=720"', $html);
$html = preg_replace('/name="MobileOptimized" content="1000"/', 'name="MobileOptimized" content="720"', $html);
$html = preg_replace('/<meta name="viewport"/', '<base href="http://gismeteo.ru" target="_blank"><meta name="viewport"', $html);
$html = preg_replace('/href="#"/', 'href="javascript:void(0);"', $html);

//echo count($html->find('script'));
/**/
echo $html;


//$html->clear(); 
unset($html);
?>

<script>
	setTimeout(function(){
		/* для действий, которые нужно совершить после загрузки страницы */
	},500);
</script>
<style>
	.content .flexbox.clearfix {
		min-width: 720px;
	}
	.content .wrap {
		min-width: 720px;
		max-width: 720px;
	}
	.content {
		padding-bottom: 0px;
	}
	.twoweeks_desc {
		font-size: 18px;
		line-height: 23px;
	}
	.widget_gm .gm_items .gmib_unit {
		font-size: 18px;
	}
	.widget_gm .gm_items .gmib_legend {
		font-size: 16px;
	}
	.twoweeksline_week {
		font-size: 24px;
	}
	.twoweeksline_month {
		font-size: 20px;
	}
	.pageinfo .pageinfo_title h1 {
		font-size: 32px;
	}
	div.pageinfo_meas > div {
		font-size: 22px;
		width: 190px;
		padding: 10px;
	}
</style>
