<h2 style="font-family: sans-serif;">New Lead</h2>
<style>.serif { font-family: sans-serif; padding: 5px; }</style>
<table>
    <tr><td class="serif">Name</td><td class="serif">Email</td><td class="serif">Phone</td></tr>
    <tr><td class="serif alt">{{ $lead->display_name }}</td><td class="serif">{{ $lead->user_email }}</td><td class="serif">{{ get_user_meta($lead->ID, '_phone_number', true) }}</td></tr>
</table>
