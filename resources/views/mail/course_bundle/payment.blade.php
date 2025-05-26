
@php
    $course_ids = json_decode($bundle->course_ids);
    $courses = \App\Models\Course::whereIn('id', $course_ids)->get();
    $user = App\Models\User::find($bundle->user_id);
@endphp

<x-mail::message>
# Invoice

<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <tr>
        <td style="padding: 8px; font-weight: bold;">{{ get_phrase('Billed to') }}:</td>
        <td style="padding: 8px;">{{ $user->name }} <br> {{ $user->email }}</td>
        <td style="padding: 8px; font-weight: bold;">{{ get_phrase('Date of issue') }}:</td>
        <td style="padding: 8px;">{{ date('d-M-Y') }}</td>
        <td style="padding: 8px; font-weight: bold;">{{ get_phrase('Invoice total') }}:</td>
        <td style="padding: 8px; font-size: 18px; color: black;">${{ number_format($bundle->price, 2) }}</td>
    </tr>
</table>


<table style="width: 100%; border-collapse: collapse;">
    <tr style="background: #f5f5f5;">
        <th style="padding: 10px; text-align: left;">{{ get_phrase('Course bundle') }}</th>
        <th style="padding: 10px; text-align: left;">{{ get_phrase('Included courses') }}</th>
        <th style="padding: 10px; text-align: left;">{{ get_phrase('Total') }}</th>
    </tr>
    @foreach ($courses as $course)
    <tr>
        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $course->title }}</td>
        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
            @foreach ($courses as $course)
                <li>{{ $course->title }}</li>
            @endforeach
    </td>
        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ number_format($bundle->price, 2) }}</td>
    </tr>
    @endforeach
</table>

{{ get_phrase('Thanks') }},<br>
{{ config('app.name') }}
</x-mail::message>

