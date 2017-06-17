@extends('layouts/layout')

@section('content')
	<div class="container scoreboard">
		<div class="content">
			<header>
				<div>სახელი</div>
				<div>ქულა</div>
			</header>

			<div class="board">
				@if($scores)
					@foreach($scores as $score)
						<div class="score">
							<div class="name">{{ $score->fined_name }}</div>
							<div class="scores">{{ $score->score }}</div>
						</div>
					@endforeach
				@endif

			</div>
		</div>
	</div>
@endsection 