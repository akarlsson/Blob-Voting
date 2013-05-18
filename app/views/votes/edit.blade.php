@extends('layouts.scaffold')

@section('main')

<h1>Edit Vote</h1>
{{ Form::model($vote, array('method' => 'PATCH', 'route' => array('votes.update', $vote->id))) }}
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
            {{ Form::submit('Update', array('class' => 'btn btn-info')) }}
            {{ link_to_route('votes.show', 'Cancel', $vote->id, array('class' => 'btn')) }}
        </li>
    </ul>
{{ Form::close() }}

@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif

@stop