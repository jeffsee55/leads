<table class="form-table">
    <tbody><tr class="user-user-login-wrap">
        <tr class="user-role-wrap"><th><label for="role">Agent</label></th>
            <td>
                <?php
                $user_id = $user ? $user->ID : null;
                $agent_id = get_user_meta($user_id, '_agent_id', true);
                wp_dropdown_users(array(
                    'selected' => $agent_id,
                    'name' => 'agent',
                    'show_option_all' => 'Select an agent',
                    'role__in' => ['super_agent', 'agent'],
                )); ?>
            </td>
        </tr>
    </tbody>
</table>

<h1>
    Listing Searches <a href="/wp-admin/post-new.php?post_type=listing_search&user_id=' . $user_id . '" class="page-title-action">Add New</a>
</h1>
<div id="listingAlerts"></div>
<h1>Favorites</h1>
<div id="favorites" style="position: relative"></div>
<h1>Recently Viewed</h1>
<div id="recent" style="position: relative"></div>
