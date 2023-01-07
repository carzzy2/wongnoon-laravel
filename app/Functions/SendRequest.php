<?php

namespace App\Functions;

class SendRequest
{
    /**
     * @param $search
     * @return mixed|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function FindLocation($search){ //เอาข้อความมาใช้หาสถานที่เพิื่อกำหนด area
        $search = str_replace("%"," ",$search); // แปลงช่องว่างเป็น % เพื่อต่อ url
        $key = env('GOOGLE_KEY', '');
        $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=Restaurants%20in%20$search&inputtype=textquery&fields=name,formatted_address,rating,geometry&locationbias=circle:10000@13.8122999,100.5375006&key=$key";
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $content = $res->getBody()->getContents();
        $responseJson = json_decode($content, true);
        $latlng = [
            'lat' => '',
            'lng' => '',
        ];
        if (count($responseJson['candidates'])  > 0) {
            $data = $responseJson['candidates'];
            $latlng = $data[0]['geometry']['location'];
        }
        return $latlng;
    }

    /**
     * @param $lat
     * @param $lng
     * @param string $pagetoken
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function FindRestaurant($lat, $lng, $pagetoken =''){ // นำ area มาใช้หาร้าน
        $latlng = $lat.",".$lng;
        $key = env('GOOGLE_KEY', '');
        $radius = "10000"; // 10 km
        $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' . $latlng . '&radius=' . $radius . '&type=restaurant&key=' . $key;
        if (!empty($pagetoken)) {
            $url .= '&pagetoken=' . $pagetoken;
        }
//        $responseJson = file_get_contents($url);
//        $responseJson = json_decode($responseJson, true);

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url);
        $content = $res->getBody()->getContents();
        $responseJson = json_decode($content, true);

        return $responseJson;
    }

}
