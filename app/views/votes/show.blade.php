@extends('layouts.scaffold')

@section('main')

<h1>Show Vote</h1>

<p>{{ link_to_route('votes.index', 'Return to all votes') }}</p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Fbid</th>
				<th>Item_id</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>{{ $vote->fbid }}</td>
					<td>{{ $vote->item_id }}</td>
                    <td>{{ link_to_route('votes.edit', 'Edit', array($vote->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('votes.destroy', $vote->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
        </tr>
    </tbody>
</table>

@stop