<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use Validator;
use App\Score;
use App\Product;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    public function show(){
    	return view('login');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email|unique:users,email',
            'password'=>'required'                    
        ]);
        
        if($validator->fails()){
            $messages = $validator->messages();
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        if(Auth::attempt([
                "email" => $request->email,
                'password' => $request->password
            ])){
            $user = User::where('email',$request->email)->pluck('id')->first();
            Session::put('user_id', $user);
            return redirect()->route('home');
        }else{
            return redirect()
                        ->back()
                        ->withInput()
                        ->with('status','false');
        }
    }

    public function success(){
        $auth_user = Auth::user();
        $auth_user_name = $auth_user->name;
        $users = User::whereNotIn('id', [$auth_user->id])->get();
        $javascript = asset('js/javascript.js'); 

        return view('home', compact('javascript','users','auth_user_name'));
    }


    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }


    public function put(Request $request){
        $request = $request->all();
        $user = Auth::user();
        $user_name = $user->name;
        $user = $user->id;
        $person_required = '';
        $select_name = User::where('id','=',$request['select'])->pluck('name');
        $select_name = $select_name[0];
        
        if($request['person']){
            $person_required = 'regex:/^[a-zA-Zა-ჰ,-]*/';
        }


        $validator = Validator::make($request,[
                'select'=>'required',
                'person'=> $person_required,
                'text'=>'required',
                'points'=>'required|regex:/^[-+][0-9]{1,2}$/'
            ]);
        if($validator->fails()){
            $messages = $validator->messages();
            $data = [
                'errors' => $messages,
                'user' => $user
            ];
            $data = json_encode($data);
            echo $data;
        }
        if(!$validator->fails()){
            if(isset($request['person']))
                $request['person'] = ','.$request['person'];
            else
                $request['person'] = null;

            Product::create([
                    'name'=>$select_name,
                    'fined_id'=>$request['select'],
                    'people'=>$user_name.''.$request['person'],
                    'text'=>$request['text'],
                    'points'=>$request['points']
                ]);
            $scores = Score::where('fined_id','=',$request['select'])->first();
            
            if(count($scores) > 0 && isset($scores->score)){
                $new_score = $scores->score + $request['points'];
                $cvladi = Score::where('fined_id',$request['select'])->update(['score'=>$new_score]);
            }else{
                Score::create([
                       'score'=>$request['points'],
                       'fined_name'=>$select_name,
                       'fined_id'=>$request['select'] 
                    ]);
            }

            
            

            
        }
    }

    public function ajax(){
        $last_post = $_GET['last_post'];
        $QTY = $_GET['QTY'];
        if(!isset($_GET['loader']))
            $products = Product::where('id','>',$last_post)->orderBy('id','desc')->take($QTY)->get();
        else
            $products = Product::where('id','<',$last_post)->orderBy('id','desc')->take($QTY)->get();


        if(count($products) != 0){
            echo '<div class="last_post" style="display: none;">'.$products->first()->id.'</div>';
            foreach($products as $product){
                
                echo '<div class="product" product_id='.$product->id.' >';
                ?>
                    <div class="col-lg-2 col-md-2 col-sm-2"><?php echo $product->name ?></div>
                    <div class="col-lg-2 col-md-2 col-sm-2"><?php echo $product->people ?></div>
                    <div class="col-lg-6 col-md-6 col-sm-6"><?php echo $product->text ?></div>
                    <div class="col-lg-1 col-md-1 col-sm-1"><?php echo $product->points ?></div>
                    <div class="col-lg-1 col-md-1 col-sm-1"><?php echo substr($product->created_at, 0,10) ?></div>
                </div>
                <?php
            }
        }
    }
}
