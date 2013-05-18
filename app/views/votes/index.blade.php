@extends('layouts.scaffold')

@section('main')

<h1>All Votes</h1>

<p>{{ link_to_route('votes.create', 'Add new vote') }}</p>

@if ($votes->count())
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Fbid</th>
				<th>Item_id</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($votes as $vote)
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
            @endforeach
        </tbody>
    </table>
@else
    There are no votes
@endif

@stop