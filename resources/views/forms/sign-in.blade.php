<div id="signinModal" class="reveal-modal small" data-reveal aria-labelledby="Sign In" aria-hidden="true" role="dialog">
    <h2 id="Sign In">Sign In</h2>
    <h5>Not a user? <a href="#" id="force_sign_up" data-reveal-id="signupModal">Sign Up</a></h5>
    <form method="post" id="sign-in" action="'. esc_url( admin_url('admin-post.php') ) .'">
        <input type="hidden" name="action" value="sign_in" />
        <input required="" type="email" name="email" placeholder="Email" />
        <input type="Submit" value="Sign In"/>
    </form>
</div>
