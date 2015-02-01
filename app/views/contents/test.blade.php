@extends('layout')

@section('content')
@stop


@section('script')
	$(document).ready(function(){
		var username = "jeraldpunx";
		var password = "punx";
		var type = "pu";
		//http://sosemergency.16mb.com/login
		$.post('http://localhost:8000/login', {username: username, password: password, type: type}, function(data) {
			alert(data);
			console.log(data); 
			//var newData = json.parse(data);
        	//alert(newData);
        	//console.log(newData); 
        }, "JSON");
	});
	
@stop


