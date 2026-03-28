<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
</head>
<body>

<a href="{{ route('users.index') }}">⬅ Back</a>

<h2>{{ $user['name'] }}</h2>

<p><strong>Email:</strong> {{ $user['email'] }}</p>
<p><strong>Username:</strong> {{ $user['username'] }}</p>
<p><strong>Phone:</strong> {{ $user['phone'] }}</p>
<p><strong>Website:</strong> {{ $user['website'] }}</p>

<hr>

<h3>Address</h3>
<p>
    {{ $user['address']['street'] }},
    {{ $user['address']['suite'] }}<br>
    {{ $user['address']['city'] }},
    {{ $user['address']['zipcode'] }}
</p>

<h4>Geo</h4>
<p>
    Lat: {{ $user['address']['geo']['lat'] }} <br>
    Lng: {{ $user['address']['geo']['lng'] }}
</p>

<hr>

<h3>Company</h3>
<p>
    <strong>Name:</strong> {{ $user['company']['name'] }} <br>
    <strong>Catch Phrase:</strong> {{ $user['company']['catchPhrase'] }} <br>
    <strong>Business:</strong> {{ $user['company']['bs'] }}
</p>

</body>
</html>
