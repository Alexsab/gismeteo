<?php

header("Content-type: text/html; charset=utf-8");

include('simple_html_dom.php');

$city = checkCityNumber('c', checkCityNumber('city', checkCityNumber('town', '4618')));

function checkCityNumber($id, $ifFalse) {
	if(isset($_GET[$id]) && !empty($_GET[$id]) && (int) $_GET[$id] > 0) 
	{
		return $_GET[$id];
	}
	return $ifFalse;
}

/**
 * https://www.gismeteo.ru/city/gm/4368/ — геомагнитная обстановка
 * https://www.gismeteo.ru/month/4368/ — на месяц
 * https://www.gismeteo.ru/city/hourly/4368/ — почасовая
 */
$url = 'http://www.gismeteo.ru/city/weekly/'.$city.'/';
// $html = file_get_contents($url);

function get_web_page( $url ) {
    $res = array();
    $options = array( 
        CURLOPT_RETURNTRANSFER => true,     // return web page 
        CURLOPT_HEADER         => false,    // do not return headers 
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
        CURLOPT_USERAGENT      => "spider", // who am i 
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
        CURLOPT_TIMEOUT        => 120,      // timeout on response 
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
    ); 
    $ch      = curl_init( $url ); 
    curl_setopt_array( $ch, $options ); 
    $content = curl_exec( $ch ); 
    $err     = curl_errno( $ch ); 
    $errmsg  = curl_error( $ch ); 
    $header  = curl_getinfo( $ch ); 
    curl_close( $ch ); 

    // $res['content'] = $content;     
    $res['url'] = $header['url'];
    return $res; 
}  
// $html = get_web_page($url)['url'];

$html = file_get_html($url);


if ($html!='') {
	// $html = file_get_html($url);

	// <meta property="og:url" content="https://www.gismeteo.ru/weather-samara-4618/2-weeks/"  />
	// (/weather-\w+-\d+/)


	$arr=array('section.media_top', 'header.header', 'div.content_overlay', '.right_col_1', '.right_col_2', 'footer.footer', '#printME', 'div.news_frame', 'div.media_with_map', 'div.cities', 'div.breadcrumbs', 'div.wrap_small', 'div.media_with_map', 'div.cities_frame', '.media_top');
	$arrParent = array('.media_frame', '.media_middle', '.readmore_list');


	$iscityurl = preg_match('/(\/weather-\w+-\d+\/)/', $html, $cityurl);
	/**/
	$url = 'http://www.gismeteo.ru'.$cityurl[0].'';
	
	// $today = file_get_contents($url);
	// $today = file_get_html('http://www.gismeteo.ru'.$cityurl[0].'');
	// $tomorrow = file_get_html('http://www.gismeteo.ru'.$cityurl[0].'tomorrow/');
	// $aftertomorrow = file_get_html('http://www.gismeteo.ru'.$cityurl[0].'3-day/');
	// $d3days = file_get_html('http://www.gismeteo.ru'.$cityurl[0].'3-days/');
	// $html = file_get_html('http://www.gismeteo.ru'.$cityurl[0].'3-days/');
	// preg_match('/<div class="forecast_frame hw_wrap one_day"(.*)<\\/div>/s', $today, $todayWeather);
	/**/
	foreach($html->find('div.__frame_sm') as $ht) {
	   // $ht->parent()->innertext = $ht->parent()->innertext; // $todayWeather[0] . 
	}
	// $html->save();
	/**/

	foreach ($arr as $value) {
		foreach($html->find($value) as $e)
			$e->outertext = '';
	}

	foreach ($arrParent as $value) {
		foreach($html->find($value) as $e)
			$e->parent()->outertext = '';
	}

	// $html = preg_replace('/<!-- Google Analytics -->(.*)<!-- END Google Analytics -->/', '', $html);
	$html = preg_replace('/name="MobileOptimized" content="1000"/', 'name="MobileOptimized" content="1000"', $html);
	// $html = preg_replace('/name="viewport" content="width=1000"/', 'name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"', $html);
	$html = preg_replace('/name="viewport" content="width=1000"/', 'name="viewport" content="width=1000"', $html);
	$html = preg_replace('/<meta name="viewport"/', '<base href="http://gismeteo.ru" target="_blank"><meta name="viewport"', $html);
	$html = preg_replace('/href="#"/', 'href="javascript:void(0);"', $html); // empty link
	$html = preg_replace("/UA-\d+-\d+/", "UA-36959087-9", $html); // GA
	
	$html = preg_replace("/54392989/", "00000000", $html); // metrika
	$html = preg_replace("/22740727/", "00000000", $html); // metrika
	$html = preg_replace("/53941180/", "00000000", $html); // metrika
	$html = preg_replace("/39370095/", "41410069", $html); // metrika
	
	$html = preg_replace("/1079660/", "0000000", $html); // adfox_imho-banners
	
	$html = preg_replace("/https\:\/\/yastatic\.net\/pcode\/adfox/", "//alexsab.ru", $html); // adfox_imho-banners
	$html = preg_replace("/https\:\/\/static\.criteo\.net/", "//alexsab.ru", $html); // adfox_imho-banners
	$html = preg_replace("/https\:\/\/bidder\.criteo\.com/", "//alexsab.ru", $html); // adfox_imho-banners
	$html = preg_replace("/counter\.yadro\.ru/", "alexsab.ru", $html); // adfox_imho-banners
	$html = preg_replace("/www\.tns-counter\.ru/", "alexsab.ru", $html); // adfox_imho-banners

	$html = preg_replace("/id\:41410069/", "id:41410069,webvisor:true", $html);

	$html = preg_replace('/<!-- Gismeteo Adfox banner TOP -->(.*?)<\/script>/', '', $html);
	$html = preg_replace('/<!-- Gismeteo Adfox banner LEFT -->(.*?)<\/script>/', '', $html);
	$html = preg_replace('/<!-- Gismeteo Adfox banner RIGHT_TOP -->(.*?)<\/script>/', '', $html);
	$html = preg_replace('/<!--LiveInternet counter-->(.*)<!--\/LiveInternet-->/', '', $html);
	$html = preg_replace('/<!-- Gismeteo Adfox banner CatFish -->(.*?)<\/script>/', '', $html);
	$html = preg_replace('/<!-- Counters -->(.*?)<\/script>/', '', $html);

	//echo count($html->find('script'));
	/**/
	// var_dump($cityurl);

	// echo $todayWeather[0];
	echo $html;


	//$html->clear(); 
	unset($html);
}
?>
<script>
setTimeout(function() {
    /* для действий, которые нужно совершить после загрузки страницы */
}, 500);
</script>
<style>
.content .flexbox.clearfix {
    min-width: 768px;
}

.content .wrap {
    min-width: 768px;
    max-width: 768px;
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

div.pageinfo_meas>div {
    font-size: 22px;
    width: 190px;
    padding: 10px;
}

body>div[style*="z-index:9999999"] {
    visibility: hidden;
}
</style>