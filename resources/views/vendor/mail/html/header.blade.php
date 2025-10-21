<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ url('images/logo.jpg')}}" class="logo" alt="Mega Market">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
