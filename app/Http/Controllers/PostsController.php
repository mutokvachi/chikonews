<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Post;
use Validator;
use Debugbar;
use App\Comment;
use Illuminate\Http\Request;


class PostsController extends Controller
{
   	public function show(){
   		$javascript = asset('js/scoreboard.js');
   		return view('posts', compact('javascript'));
   	}

   	public function ajax(){
   		$counter = $_GET['postsCounter'];
		$posts = Post::where('id','>', $counter)->orderBy('id','desc')->get();
		$user = Auth::user();

   		foreach($posts as $post){
   		?>
   		<div class="post" last_post="<?= $post->id ?>">
	   		<div class="post_head">
				<img src="<?php echo asset('img/unknown.jpg') ?>">
				<?php
				if($post->user_id == $user->id){
				?>
				<a href="posts/delete/<?= $post->id ?>"><i class="fa fa-close"></i></a>
				<a href=""><i class="fa fa-edit"></i></a>
				<?php } ?>
				<p><?= $post->text ?></p>
				<form method="post" id='postMessages' action="<?= route('postMessages') ?>">
					<?= csrf_field() ?>
					<textarea name="text" class="form-control" placeholder="დაამატეთ თქვენი კომენტარი"></textarea>
					<input type="button" name="send" value="გაგზავნა" class="btn btn-primary">
				</form>
			</div>

			<div class="load_more">სხვა კომენატრების ნახვა</div>
		</div>
   		<?php
   		}
   	}

   	public function messages(Request $request){
   		$request = $request->all();
   		$user = Auth::user();

   		$validator = Validator::make($request,[
   				'text'=>'required'
   			]);
   		if(!$validator->fails()){
   			Comment::create([
   					'text'=>$request['text'],
   					'user_id'=>$user->id,
   					'post_id'=>$request['last_id']
   				]);
   		}
   		if($validator->fails()){
   			echo 'fail';
   		}
   	}

   	public function showMessages(){
   		$id = $_GET['last_id'];
   		$user = Session::get('user_id');
   		$comments = Comment::where('id','>', $id)->get();
   		$response = [];
		foreach($comments as $comment){
			if($user == $comment->user_id){
	   			array_push($response, ['post_id'=>$comment->post_id],['id'=>$comment->id],
				'<div class="comments">
					<div class="comment" id='.$comment->id.'>
						<img src='.asset("img/unknown.jpg").'>
						<a href='."posts/delete/message/".$comment->id.' <i class="fa fa-close"></i></a>
						<a href="#"><i class="fa fa-edit"></i></a>
						<p>'.$comment->text.'</p>
					</div>
				</div>');
   			}else{
   				array_push($response, ['post_id'=>$comment->post_id],['id'=>$comment->id],
				'<div class="comments">
					<div class="comment" id='.$comment->id.'>
						<img src='.asset("img/unknown.jpg").'>
						<p>'.$comment->text.'</p>
					</div>
				</div>');
   			}
		} 
		if(!empty($response))
			echo json_encode($response);
		
   	}

   	public function store(Request $request){
   		$user = Auth::user();
   		$request = $request->all();
		
		$validator = Validator::make($request,[
				'text'=>'required'
			]);

		if(!$validator->fails()){
			Post::create([
					'user_id'=>$user->id,
					'text'=>$request['text']
			]);
		}
		if($validator->fails()){
			$messages = $validator->messages();
			echo $messages;
		}

   	}

   	public function destroy($id){
   		$user = Session::get('user_id');

   		$comments = Comment::where('post_id',$id)->where('user_id', $user)->delete();
   		$post = Post::where('user_id', $user)->where('id', $id)->delete();

   		return redirect()->back();

   	}

   	public function destroyMessage($id){
   		$user = Session::get('user_id');
   		$comment = Comment::where('user_id',$user)->where('id', $id)->delete();

   		return redirect()->back();
   	}

}
