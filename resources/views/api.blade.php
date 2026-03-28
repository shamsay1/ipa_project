<!DOCTYPE html>
<html>
<head>
    <title>API Users</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>

<h2>Users from API</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user['id'] }}</td>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['email'] }}</td>
            <td>{{ $user['username'] }}</td>
            <td>
                <a href="{{ route('users.show', $user['id']) }}">
                    View Details
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
