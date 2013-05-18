@extends('layouts.scaffold')

@section('main')

<h1>Create Vote</h1>

{{ Form::open(array('route' => 'votes.store')) }}
    <ul>
        <li>
            {{ Form::label('fbid', 'Fbid:') }}
            {{ Form::text('fbid') }}
        </li>

        <li>
            {{ Form::label('item_id', 'Item_id:') }}
            {{ Form::input('number', 'item_id') }}
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


