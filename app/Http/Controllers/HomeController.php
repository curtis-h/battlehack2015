<?php namespace App\Http\Controllers;

use Request;
use Pusher;
use File;
use App\Facepp;
use App\Advert;
use App\Tracking;
use App\User;

class HomeController extends Controller {
    protected $groupName = 'battlehack';
    
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct() {
        // $this->middleware('auth');
    }
    
    /**
     * Show the application dashboard to the user.
     * @return Response
     */
    public function index() {
        return view ( 'home' );
    }
    
    /**
     * initial testing of facepp class
     */
    public function test() {
        $face = new Facepp();
        $params = [
            'attribute' => 'gender,age,race,smiling,glass,pose',
            //'url' => 'https://pbs.twimg.com/profile_images/482083515128107008/aeaaf-VI.jpeg'
            'img' => public_path("uploads/mike/mike553bb68a592e3.jpg")
        ];
        
        
        $response = $face->execute('/detection/detect', $params);
        $decode   = json_decode($response['body']);
        dd($response, $decode->face[0]->face_id);
    }
    
    
    public function makeGroup() {
        $face = new Facepp();
        
        $dresponse = $face->execute('/group/delete', ['group_name'=>$this->groupName]);
        $cresponse = $face->execute('/group/create', ['group_name'=>$this->groupName]);
        
        dd($dresponse, $cresponse);
    }
    
    
    /**
     * show all images uploaded for a person
     * @param person $who
     */
    public function person($who) {
        $files = File::files(public_path("uploads/{$who}"));
        return view("person")->with('person', $who)->with('images', $files);
    }
    
    
    /**
     * show the upload page
     * @param person $who
     */
    public function viewUpload($who) {
        return view("/upload")->with("who", $who);
    }
    
    /**
     * upload a file for a person
     * @param string $who - the person this relates to
     */
    public function upload($who=false) {
        if($who && Request::hasFile('file')) {
            $name = uniqid($who);
            Request::file('file')->move(public_path("uploads/{$who}"), "{$name}.jpg");
            
            return redirect("/upload/{$who}");
        }
        
        return redirect("/");
    }
    
    /**
     * test uploader for syncing with Mikes stuff
     * @return number
     */
    public function uploadTest() {
        $who = 'curtis';
        if(Request::hasFile('file')) {
            $name = uniqid($who);
            Request::file('file')->move(public_path("uploads/{$who}"), "{$name}.jpg");
        
            return 1;
        }
        
        return 0;
    }
    
    public function makePerson($who) {
        $face = new Facepp();
        // delete the person
        $dresponse = $face->execute('/person/delete', ['person_name' => $who]);
        //* 
        $images = File::files(public_path("uploads/{$who}"));
        if($images) {
            $ids = [];
            
            foreach($images as $image) {
                $filename = pathinfo($image, PATHINFO_FILENAME);
                $params = [
                    'attribute' => 'gender,age,race,smiling,glass,pose',
                    'img'       => public_path("uploads/{$who}/{$filename}.jpg")
                ];
                
                $response = $face->execute('/detection/detect', $params);
                $decode   = json_decode($response['body']);
                $id = @$decode->face[0]->face_id;
                if(!empty($id)) {
                    $ids[] = $id;
                }
            }
            
            $params = [
                'person_name' => $who,
                'group_name'  => $this->groupName,
                'face_id'     => implode(',', $ids)
            ];
            $cresponse = $face->execute('/person/create', $params);
            $person    = json_decode($cresponse['body']);
            
            dd($dresponse, $cresponse, $params, $person);
        }
        //*/
    }
    
    public function train() {
        $face = new Facepp();
        $response = $face->execute('/train/identify', ['group_name'=>$this->groupName]);
        dd($response);
    }
    
    
    public function groupinfo() {
        $face = new Facepp();
        $params = [
            'group_name' => $this->groupName,
        ];
        
        //$response = $face->execute('/group/get_info', $params);
        return Response::json(['curtis'=>'trest'], 400, ['Access-Control-Allow-Origin'=>'http://battle.curtish.me']);
    }
    
    public function personinfo($who) {
        $face = new Facepp();
        $params = [
            'person_name' => $who,
        ];
    
        $response = $face->execute('/person/get_info', $params);
        return Response::json($response, 200, ['Access-Control-Allow-Origin'=>'http://battle.curtish.me']);
    }
    
    
    
    public function detect() {
        // beaconId
        if(Request::hasFile('file')) {
            $name = uniqid("detect");
            Request::file('file')->move(public_path("uploads/detect"), "{$name}.jpg");
            $this->runFaceDetection($name);
        }
    
        return 0;
    }
    
    /**
     * handle base 64 encoded image post
     * cross origin policy as posting with ajax from browser
     */
    public function detect64() {
        $user = 'detect';
        $name              = uniqid($user);
        $file              = public_path("uploads/{$user}/{$name}.jpg");
        $data              = Request::input('imgBase64');
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data              = base64_decode($data);
        file_put_contents($file, $data);
        
        
        $result = $this->runFaceDetection($name);
        
        return response()
            ->view('detection', ['result'=>$result])
            ->header('Access-Control-Allow-Origin', '*');
    }
    
    
    
    public function runFaceDetection($name) {
        $device = Request::input('beaconId', false);
        $face   = new Facepp();
        $params = [
            'group_name' => $this->groupName,
            'img'        => public_path("uploads/detect/{$name}.jpg")
        ];
        
        $response = $face->execute('/recognition/identify', $params);
        
        if(!empty($response) && !empty($response['body'])) {
            $decode   = json_decode($response['body']);
            if(!empty($decode) && !empty($decode->face)) {
                $top = $decode->face[0]->candidate[0];
                $str = json_encode($top);
                error_log($str);
                
                if($top->confidence > 65) {
                    $name   = $top->person_name;
                    $advert = Advert::send($name, $device);
                    // save person detected at this location
                    Tracking::create([
                        'user_id'   => User::convert($name),
                        'device_id' => $device,
                        'advert_id' => $advert
                    ]);
        
                    return $str;
                }
                else {
                    return "Confidence: {$top->confidence}";
                }
            }
        }
        
        return 'nothing';
    }
    
    
    
    public function detectTest() {
        $face = new Facepp();
        $params = [
            'group_name' => $this->groupName,
            'img'        => public_path("uploads/detect/detect553bd9bd4bf67.jpg")
        ];
    
        $response = $face->execute('/recognition/identify', $params);
        dd($response);
        if(!empty($response) && !empty($response['body'])) {
            $decode   = json_decode($response['body']);
            if(!empty($decode) && !empty($decode->face)) {
                dd($decode);
                $name = $decode->face[0]->candidate[0]->person_name;
                $str  = json_encode($decode->face[0]->candidate);
                return $str;
            }
        }
    
        return 0;
    }
    
    
    public function redirectnfc() {
        //http://bh2015.hazan.me/?purchase=1&customer_id=123456&value=20
        $track = Tracking::where('device_id', Request::input('device'))
            ->orderBy('id', true)
            ->first();
        
        if(!empty($track)) {
            $this->advertSuggestion($track->advert);
            
            $params = [
                'purchase'    => $track->advert_id,
                //'customer_id' => $track->user_id,
                'customer_id' => '123456',
                'value'       => $track->advert->amount
            ];
            
            $url = "http://bh2015.hazan.me/?".http_build_query($params);
            return redirect($url);
        }
    }
    
    
    public function advertSuggestion($advert) {
        // http://battlehackamazon.eu-gb.mybluemix.net/:id
        $url  = "http://battlehackamazon.eu-gb.mybluemix.net/{$advert->amazon_id}";
        $data = file_get_contents($url);
        $data = json_decode($data);
        
        if(!empty($data->id)) {
            $a = Advert::create([
                'product' => 3,
                'type'    => 'image',
                'data'    => $data->image,
                'amount'  => $data->price,
                'amazon_id' => $data->id
            ]);
            dd($a);
        }
        
    }
    
    
    
    public function getAdvert() {
        $device = Request::input('beaconId', false);
        $advert = Advert::random($device);
        
        Tracking::create([
            'user_id'   => 0,
            'device_id' => $device,
            'advert_id' => $advert
        ]);
        
        return response()
            ->view('detection', ['result'=>'success'])
            ->header('Access-Control-Allow-Origin', '*');
    }
    
    
    public function tester() {
        $pusher = new Pusher($_ENV['PUSHER_KEY'], $_ENV['PUSHER_SECRET'], $_ENV['PUSHER_ID']);
        dd($pusher);
    }
}
