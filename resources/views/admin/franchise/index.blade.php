@extends('layouts.admin_app')
@section('content')
<div class="container">
	<div class="row" style="padding-top: 1%;">
		<div class="col"><h1>Список Франчайзи/Партнеров</h1></div>
		<div class="col"><a style="float: right;"href="{{route('franchise_create')}}"><button class="btn btn-dark">Создать нового</button></a></div>
	</div>
	<div class="row">
		<div class="col-6">
			<h1>Franchaise</h1>
			@foreach($data['franchise_all'] as $franchise)
				<div class="card">
					<div class="card-body">
					<a href="{{ route('franchise_show',['id' => $franchise->id])}}">
						<p>Имя: {{$franchise->name}}</p></a>
					<p> Фамилия: {{$franchise->last_name}}</p>
					</div>
				</div>
				<hr>
			@endforeach
		</div>
		<div class="col-6">
			<h1>Partner</h1>
			@foreach($data['partners_all'] as $partner)
				<div class="card">
					<div class="card-body">
					<a href="{{ route('franchise_show',['id' => $partner->id])}}">
						<p>Имя: {{$partner->name}}</p></a>
					<p> Фамилия: {{$partner->last_name}}</p>
					<p> Принадежит к Franchise:<a href="{{ route('franchise_show',['id' => $partner->franchise_id])}}"> {{$partner->franchise->name}}</a></p>
					</div>
				</div>
				<hr>
			@endforeach
		</div>
	</div>
</div>
@endsection