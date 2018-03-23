<?php 
error_reporting(0);
$ch=curl_init("https://api2.bitcoin.co.id/api/webdata/".( isset($_GET["id"]) && !empty($_GET["id"]) ? $_GET["id"] : "btcidr"));
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
if($response){
    $result = getDataList(json_decode($response,true));
    $data["frame_buy_orders"] = $result["buy_orders"];
    $data["frame_sell_orders"] = $result["sell_orders"];
    $data["frame_last_trades"] = $result["last_trades"];

    $result2 = getDataPrice(json_decode($response,true), ( isset($_GET["id"]) && !empty($_GET["id"]) ? $_GET["id"] : "btcidr"));
    $table="";
    foreach ($result2["prices"] as $market => $value) {
        if(substr($market, -3) == "btc"){
            $change_price = number_format(abs(($value["price"]-$result2["prices_24h"][$market]["price"])/100000000), 8);
        }else{
            $change_price = number_format(abs(($value["price"]-$result2["prices_24h"][$market]["price"])),0,',','.');
        }
        $change_persen = number_format(((($value["price"]-$result2["prices_24h"][$market]["price"])/$result2["prices_24h"][$market]["price"]) * 100), 2  );
        $table.="<tr class=".($change_persen < 0 ? 'danger' : 'success')." title='Market Detail : ".$value["label"]."' style='cursor:pointer;' onclick='getData(\"".$value["value"]."\")'>";
        $table.="<td>".$value["label"]."</td>";
        $table.="<td align='right'>".$value["price_format"]."</td>";
        $table.="<td align='right'>".$change_price."</td>";
        $table.="<td>".abs($change_persen)."%</td>";
        $table.="</tr>";
    }
    $data["market"] = $table;
    $data["_24h"] = $result2["_24h"];
}
curl_close($ch);
echo json_encode($data);
        
function getDataList($data){
    $result["buy_orders"] = $data["frame_buy_orders"];
    $result["sell_orders"] = $data["frame_sell_orders"];
    $result["last_trades"] = $data["frame_last_trades"];
    return $result;
}
        
function getDataPrice($data, $type=null){
    $result["_24h"]["last_price"] = marketPrices($data["_24h"]["last_price"], substr($type, -3));
    $result["_24h"]["low"] = marketPrices($data["_24h"]["low"], substr($type, -3));
    $result["_24h"]["high"] = marketPrices($data["_24h"]["high"], substr($type, -3));
    foreach ($data["prices_24h"] as $market => $price) {
        $result["prices_24h"][$market]["price"] = $price;
        $result["prices_24h"][$market]["price_format"] = marketPrices($price, substr($market, -3));
        $result["prices_24h"][$market]["label"] = strtoupper(substr_replace($market, "/", -3, 0));
        $result["prices_24h"][$market]["value"] = $market;
    }

    $i = 0;
    foreach ($data["prices"] as $market => $price) {
        $result["prices"][$market]["price"] = $price;
        $result["prices"][$market]["price_format"] = marketPrices($price, substr($market, -3));
        $result["prices"][$market]["label"] = strtoupper(substr_replace($market, "/", -3, 0));
        $result["prices"][$market]["value"] = $market;
        $i++;
    }
    return $result;
}
        
function marketPrices($data, $type){
    switch ($type) {
        case 'btc':
            $result=number_format(($data/100000000),8);
            break;
        
        default:
            $result=number_format($data,0,',','.');
            break;
    }
    return $result;
}