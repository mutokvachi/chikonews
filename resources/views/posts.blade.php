@extends('layouts.layout')

@section('content')
	<div class="posts container">
		<div class="btn btn-danger btn-lg">დაამატე პოსტი +</div>
		<br><br>
		
		<div class="all_posts">
			
		</div>

		<div class="my_modal">
			<div class="header">
				<h2>პოსტის დამატება:</h2>
				<i class="fa fa-close"></i>
			</div>
			<form method="post" id="form" action="{{ route('posts') }}">
				
				<label for="text">აღწერა:</label>
				<textarea name="text" class="form-control" id="text" class="text" ></textarea>
				
				<br>
				{{ csrf_field() }}
				<input type="button" name="send" id="send" value="დამატება" class="btn btn-primary">
			</form>
		</div>
	</div>
@endsection