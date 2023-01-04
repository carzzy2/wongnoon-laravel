<?php

namespace App\Http\Controllers;


use App\Functions\SendRequest;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        if($search == 'null'){
            $search ='';
        }
        $perpage = 20;
        $page = $request->get('page', 1);
        $key = env('GOOGLE_KEY', '');
        $base_url_photo = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&key=$key&photoreference="; // base url สำหรับดูรูป
        $findData = Restaurants::where("search",'like' ,'%' . $search . '%')->paginate($perpage, ['*'], 'page', $page);
        if (count($findData) == 0) {
            if($search ==''){
                $search = 'bang sue'; //ถ้าไม่มีข้อมูลเลยให้ default ดึงค่า
            }
            $location = SendRequest::FindLocation($search);
            $pageToken = '';
            $data = SendRequest::FindRestaurant($location['lat'], $location['lng'], $pageToken);
            $finalArray = [];
            if (count($data['results']) > 0){ //ถ้าเจอร้านค้าให้นำไปเก็บลง array
                foreach ($data['results'] as $key => $value) {
                    $finalArray[] = [
                        'place_id' => $value['place_id'],
                        'search' => $search,
                        'name' => $value['name'],
                        'geometry_lat' => !empty($value['geometry']['location']['lat']) ? $value['geometry']['location']['lat']: null,
                        'geometry_lng' => !empty($value['geometry']['location']['lng']) ? $value['geometry']['location']['lat']: null,
                        'rating' => !empty($value['rating']) ? $value['rating']:  null,
                        'user_ratings_total'=> !empty($value['user_ratings_total']) ? $value['user_ratings_total']: 0,
                        'types'=> json_encode($value['types']),
                        'vicinity' => $value['vicinity'],
                        'icon_url'=> $value['icon'],
                        'full_data'=> json_encode($value),
                        'photo' => !empty($value['photos'][0]['photo_reference']) ? $base_url_photo.$value['photos'][0]['photo_reference']: null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                if ($data['next_page_token']) { //หากมีข้อมูลมากกว่า 20 record จะดึงมาเพิ่มอีก
                    $pageToken = $data['next_page_token'];
                    for ($runApi = 1; $runApi <= 2; $runApi++) {
                        $dataNextPage = null;
                        $dataNextPage = SendRequest::FindRestaurant($location['lat'], $location['lng'], $pageToken);
                        foreach ($dataNextPage['results'] as $key => $value) {
                            $finalArray[] = [
                                'place_id' => $value['place_id'],
                                'search' => $search,
                                'name' => $value['name'],
                                'geometry_lat' => !empty($value['geometry']['location']['lat']) ? $value['geometry']['location']['lat']: null,
                                'geometry_lng' => !empty($value['geometry']['location']['lng']) ? $value['geometry']['location']['lat']: null,
                                'rating' => !empty($value['rating']) ? $value['rating']:  null,
                                'user_ratings_total'=> !empty($value['user_ratings_total']) ? $value['user_ratings_total']: 0,
                                'types'=> json_encode($value['types']),
                                'vicinity' => $value['vicinity'],
                                'icon_url'=> $value['icon'],
                                'full_data'=> json_encode($value),
                                'photo' => !empty($value['photos'][0]['photo_reference']) ? $base_url_photo.$value['photos'][0]['photo_reference']: null,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                        }
                    }
                    Restaurants::upsert($finalArray, 'place_id'); // ทำการบันทึกแบบข้อมูล หาข้อมูลซ้ำจะ update แทน
                }
            }
            $findData = Restaurants::where("search",'like' ,'%' . $search . '%')->paginate($perpage, ['*'], 'page', $page);
        }
        return $findData;
    }

    /**
     * @param $id
     * @return array
     */
    public function getRestaurantById($id): array
    {
        $data = Restaurants::query()->where('place_id', $id)->first();
        if(empty($data)){
            return ['success'=> false, 'data' => [], 'message' => 'Not Found.'];
        }
        return ['success'=> true, 'data' => $data];
    }
}
