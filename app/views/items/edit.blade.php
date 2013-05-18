@extends('layouts.scaffold')

@section('main')

<h1>Edit Item</h1>
{{ Form::model($item, array('method' => 'PATCH', 'files' => 'true', 'route' => array('items.update', $item->id))) }}
    <ul>
        <li>
            {{ Form::label('name', 'Location:') }}
            {{ Form::text('name',$item->name,array('style'=>'width:420px')) }}
        </li>

        <li>
            {{ Form::label('body', 'Beschreibung:') }}
            {{ Form::textarea('body') }}
        </li>

        <li>
            {{ Form::label('image', 'Bild:') }}
            <img src="{{ Request::root() }}/photos/{{ $item->image }}" width="200" />
            {{ Form::file('image') }}
        </li>

        <li>
            {{ Form::label('author', 'Autor:') }}
            {{ Form::text('author') }}
        </li>
        

        <li>
            {{ Form::label('active', 'Aktiv?') }}
            {{ Form::select('active',array('0'=>'Nein','1'=>'Ja')) }}
        </li>

        <li>
            {{ Form::submit('Update', array('class' => 'btn btn-info')) }}
            {{ link_to_route('items.show', 'Cancel', $item->id, array('class' => 'btn')) }}
        </li>
    </ul>
{{ Form::close() }}

@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif

@stop