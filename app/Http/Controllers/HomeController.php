<?php namespace App\Http\Controllers;

use Request;
use File;
use App\Facepp;


class HomeController extends Controller {
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
        $face = new Facepp ();
        $params = [
            'attribute' => 'gender,age,race,smiling,glass,pose',
            //'url' => 'https://pbs.twimg.com/profile_images/482083515128107008/aeaaf-VI.jpeg'
            'img' => public_path("uploads/mike/mike553baf26da818.jpg")
        ];
        
        $response = $face->execute('/detection/detect', $params);
        dd($response);
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
        $who = 'mike';
        if(Request::hasFile('file')) {
            $name = uniqid($who);
            Request::file('file')->move(public_path("uploads/{$who}"), "{$name}.jpg");
        
            return 1;
        }
        
        return 0;
    }
}
