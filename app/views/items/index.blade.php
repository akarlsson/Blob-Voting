@extends('layouts.scaffold')

@section('main')

<h1>Alle Bewerber</h1>

<p><a href="{{ Request::root() }}/items/create" class="btn">Neuen Eintrag anlegen</a></p>

@if ($items->count())
  

            @foreach ($items as $item)
                <div class="item">
                    <h3 style="margin: 0 0 5px 0">{{ $item->name }}</h3>
					<p><img src="{{ Request::root() }}/photos/{{ $item->image }}" width="120" style="float:left;margin-right:10px;" />{{ Str::limit($item->body, 150) }}</p>
					<p>
                    <?php
					//$sender = json_decode(file_get_contents('http://graph.facebook.com/'.$item->author))->name;
					//$senderemail = json_decode(file_get_contents('http://graph.facebook.com/'.$item->author))->email;
					$fbuser = User::where("fbid","=",$item->author)->first();
					?>
                    </p>
                    <p>
                     {{ Form::open(array('method' => 'DELETE', 'route' => array('items.destroy', $item->id),'style' => 'display:inline;')) }}
                     <a href="{{ Request::root() }}/items/{{ $item->id }}/edit#top" class="btn btn-success">Bearbeiten</a> 
                            {{ Form::submit('Löschen', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </p>
                    <?php echo '<p  class="subline">Eingeschickt von <a href="http://facebook.com/'.$item->author.'" target="_blank">'.$fbuser->username.'</a><br />'.$fbuser->email.'</p>'; ?>
               </div>
            @endforeach
        
@else
    There are no items
@endif

@stop