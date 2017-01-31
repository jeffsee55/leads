<h2 style="font-family: sans-serif;">{{ $data['title'] }}</h2>
<style>.serif { font-family: sans-serif; padding: 5px; }</style>
<table>
    <tr><td class="serif">Name</td><td class="serif">Email</td><td class="serif">Phone</td></tr>
    <tr>
        <td class="serif alt">
            {{ $data['first_name'] }} {{ $data['last_name'] }}
        </td>
        <td class="serif">
            <strong>{{ $data['email'] }}</strong>
        </td>
        <td class="serif">
            <strong>{{ $data['phone_number'] }}</strong>
        </td>
    </tr>
</table>
<h4 style="font-family: sans-serif;">Message</h4>
<p style="font-family: sans-serif;">{{ $data['message'] }}</p>
@if(! empty($data['date']))
    <table>
        <tr><td class="serif alt">Showing Requested</td><td class="serif">{{ $data['date'] }}</td></tr>
    </table>
@endif
@if(isset($data['listing_title']))
    <h4 style="font-family: sans-serif;">Listing</h4>
    <p class="serif"> {{ $data['listing_title'] }}</p>
@endif
