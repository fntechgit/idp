@extends('layout')
@section('content')
<h1>{!! Config::get('app.app_name') !!} - 404</h1>
<div class="container">
    <p>
        404. That's an error.
    </p>
    <p>
        The page you requested is invalid. That's all we know.
    </p>
</div>
@stop