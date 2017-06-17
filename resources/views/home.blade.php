@extends('layouts/layout')

@section('content')
	<div class="container products">
		<header>
			<div class="col-lg-2 col-md-2 col-sm-2">ბრალდებული:</div>
			<div class="col-lg-2 col-md-2 col-sm-2">მოწმე:</div>
			<div class="col-lg-6 col-md-6 col-sm-6">აღწერა:</div>
			<div class="col-lg-1 col-md-1 col-sm-1">ქულა:</div>
			<div class="col-lg-1 col-md-1 col-sm-1">დრო:</div>
		</header>

		<div class="products_list">
			<div class="last_post" style="display: none;">0</div>

		</div>
		<div class="load_more">მეტის ჩატვირთვა</div>
		<div class="clear"></div>
		<div class="btn btn-danger add_product">დამატება</div>			

		<div class="my_modal">
			<div class="header">
				<h2>მონაცემები:</h2>
				<i class="fa fa-close"></i>
			</div>
			<form method="post" action="{{ route('add_product') }}" id="form">
				<label for="name">ბრალდებული:</label>
				<select class="form-control" id="select" name="select">
					@if(isset($users))
						@foreach($users as $user)
							<option value="{{ $user->id }}">{{ $user->name }}</option>
						@endforeach
					@endif
					
				</select>
				
				<label>მოწმე N1: {{ $auth_user_name }}</label>
				<input type="text" name="person" id='person' class="form-control" placeholder="სხვები მაგ: ჩიქო,ცირკაჩა" >
				
				<label for="text">აღწერა:</label>
				<textarea name="text" class="form-control" id="text" class="text" ></textarea>
				
				<label for="points">ქულა:</label>
				<input type="text" id="points" name="points" placeholder="მაგ: +5 / -5" class="form-control" >
				<br>
				{{ csrf_field() }}
				<input type="button" name="send" id="send" value="დამატება" class="btn btn-primary">
			</form>
		</div>
		
	</div>
@endsection
