@extends('layouts.scaffold')

@section('main')


<p>{{ link_to_route('items.index', 'Zurück zur Liste') }}</p>

<h1>{{ $item->name }}</h1>
					<p>{{ $item->body }}</p>
					<p><img src="{{ Request::root() }}/photos/{{ $item->image }}" /></p>
					<p>Eingeschickt von {{ $item->author }}</p>
                    <!--
                    <td>{{ link_to_route('items.edit', 'Edit', array($item->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('items.destroy', $item->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>-->
                    
                    <?php 
					
					
					if (Holmes::is_mobile()==false) {
						
						?>
                        
                        <script>
						var red = "<?php echo Config::get('app.fbtablink').'&'.rand(0,3000000).'&app_data=detail'.$item->id ?>";
						
						setTimeout(function(){window.location.href=red;}, 200);
						
						</script>
                        
                        <?php
						
					} 
					
					?>
                    
@stop