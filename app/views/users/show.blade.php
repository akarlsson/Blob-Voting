@extends('layouts.scaffold')

@section('main')

<h1>Show User</h1>

<p>{{ link_to_route('users.index', 'Return to all users') }}</p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Fbid</th>
				<th>Username</th>
				<th>Forename</th>
				<th>Lastname</th>
				<th>Profileimage</th>
				<th>City</th>
				<th>Gender</th>
				<th>Email</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>{{ $user->fbid }}</td>
					<td>{{ $user->username }}</td>
					<td>{{ $user->forename }}</td>
					<td>{{ $user->lastname }}</td>
					<td>{{ $user->profileimage }}</td>
					<td>{{ $user->city }}</td>
					<td>{{ $user->gender }}</td>
					<td>{{ $user->email }}</td>
                    <td>{{ link_to_route('users.edit', 'Edit', array($user->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('users.destroy', $user->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
        </tr>
    </tbody>
</table>

@stop