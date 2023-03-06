<?php


set_time_limit(0);
$url = "http://sitename.com/feed";
$site = "Sitename";
$pingliste = "ping.txt";
if (!function_exists('xmlrpc_encode_request'))
{
    function xmlrpc_encode_request($yontem, $iki)
    {
        $cikti .= '<?xml version="1.0"?>';
        $cikti .= '<methodCall>';
        $cikti .= '<methodName>'.$yontem.'</methodName>';
        $cikti .= '<params>';
        $cikti .= '<param><value><string>'.$iki[0].'</string></value></param>';
        $cikti .= '<param><value><string>'.$iki[1].'</string></value></param>';
        $cikti .= '</params></methodCall>';
        return $cikti;
    }
}
function pingle($pingurl,$site,$url,$yontem) {
    $xmlrpc = xmlrpc_encode_request($yontem,array($site, $url));
    preg_match('@^(?:http://)?([^/]+)@i', $pingurl, $cikti); 
    $pinghost = $cikti[1];
    $headers[] = "Host: ".$pinghost;
    $headers[] = "Content-type: text/xml";
    $headers[] = "User-Agent: r10.ping";
    $headers[] = "Content-length: ".strlen($xmlrpc) . "\r\n";
    $headers[] = $xmlrpc;
    $chi = curl_init();
    curl_setopt($chi,CURLOPT_URL,$pingurl); 
    curl_setopt($chi,CURLOPT_RETURNTRANSFER,1); 
    curl_setopt($chi, CURLOPT_CONNECTTIMEOUT, 4);
    curl_setopt($chi,CURLOPT_HTTPHEADER,$headers); 
    curl_setopt($chi,CURLOPT_CUSTOMREQUEST,'POST');
    $html = curl_exec( $chi );
    $sonuc = curl_getinfo($chi);
    echo " ".$pinghost.", ".$sonuc["http_code"]."<br />";
    curl_close($chi);
    unset($headers);
}
$oku = file($pingliste);
$len = count($oku);
for ($i = 0; $i < $len; $i++) {
    $pingurl = trim($oku[$i]);    
    pingle($pingurl,$site,$url,"weblogUpdates.ping");
    
}
?>
