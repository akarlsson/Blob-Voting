@extends('layouts.scaffold')

@section('main')

<h1>Create User</h1>

{{ Form::open(array('route' => 'users.store')) }}
    <ul>
        <li>
            {{ Form::label('fbid', 'Fbid:') }}
            {{ Form::text('fbid') }}
        </li>

        <li>
            {{ Form::label('username', 'Username:') }}
            {{ Form::text('username') }}
        </li>

        <li>
            {{ Form::label('forename', 'Forename:') }}
            {{ Form::text('forename') }}
        </li>

        <li>
            {{ Form::label('lastname', 'Lastname:') }}
            {{ Form::text('lastname') }}
        </li>

        <li>
            {{ Form::label('profileimage', 'Profileimage:') }}
            {{ Form::text('profileimage') }}
        </li>

        <li>
            {{ Form::label('city', 'City:') }}
            {{ Form::text('city') }}
        </li>

        <li>
            {{ Form::label('gender', 'Gender:') }}
            {{ Form::text('gender') }}
        </li>

        <li>
            {{ Form::label('email', 'Email:') }}
            {{ Form::text('email') }}
        </li>

        <li>
            {{ Form::submit('Submit', array('class' => 'btn')) }}
        </li>
    </ul>
{{ Form::close() }}

@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif

@stop


