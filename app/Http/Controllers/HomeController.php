<?php namespace App\Http\Controllers;

use Request;
use File;
use App\Facepp;


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
        $name = "battlehack";
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
    
    public function detect() {
        if(Request::hasFile('file')) {
            $name = uniqid("detect");
            Request::file('file')->move(public_path("uploads/detect"), "{$name}.jpg");
            
            $face = new Facepp();
            $params = [
                'group_name' => $this->groupName,
                'img'        => public_path("uploads/detect/{$name}.jpg")
            ];
            
            $response = $face->execute('/recognition/identify', $params);
            if(!empty($response) && !empty($response['body'])) {
                $decode   = json_decode($response['body']);
                if(!empty($decode) && !empty($decode->face)) {
                    $str  = json_encode($decode->face[0]->candidate);
                    return $str;
                }
            }
        }
        
        return 0;
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
                $str  = json_encode($decode->face[0]->candidate);
                return $str;
            }
        }
        
        return 0;
    }
    
    public function groupinfo() {
        $face = new Facepp();
        $params = [
        'group_name' => $this->groupName,
        ];
        
        $response = $face->execute('/group/get_info', $params);
        dd($response);
    }
    
    public function personinfo($who) {
        $face = new Facepp();
        $params = [
            'person_name' => $who,
        ];
    
        $response = $face->execute('/person/get_info', $params);
        dd($response);
    }
}
