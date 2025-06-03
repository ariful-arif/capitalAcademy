<h2>You've been gifted access to our course platform!</h2>
<p>Hello {{ $user->name }},</p>
<p>You’ve been gifted the following course(s):</p>
<ul>
    @foreach ($courses as $course)
        <li>{{ $course['title'] }}</li>
    @endforeach
</ul>
<p>You can log in using these credentials:</p>
<ul>
    <li>Email: {{ $user->email }}</li>
    <li>Password: {{ $password }}</li>
</ul>
<p>Please log in and change your password after first login.</p>
