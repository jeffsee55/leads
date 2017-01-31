<div id="signupModal" class="reveal-modal medium" data-reveal aria-labelledby="Sign Up" aria-hidden="true" role="dialog">
    <div class="row">
        <div class="columns large-6 signup-cta">
            {{ the_field('q4_sign_up_message', 'option') }}
        </div>
        <div class="columns large-6 signup-form">
            <form method="post" id="sign-up" action="{{ admin_url('admin-post.php') }}">
                <input type="hidden" name="action" value="q4_sign_up" />
                <input required="" type="text" name="first_name" placeholder="First Name" value="Test" />
                <input required="" type="text" name="last_name" placeholder="Last Name"  value="User"/>
                <input required="" type="email" name="email" placeholder="Email"   value="user@example2.com"/>
                <input required="" type="tel" name="phone" placeholder="Phone (used as a one-time password)" value="23234234"/>
                Have you been working with one of our agents?
                <?php
                wp_dropdown_users(array(
                    'selected' => 0,
                    'name' => 'agent',
                    'show_option_all' => 'Select an agent',
                    'role__in' => ['super_agent', 'agent'],
                ));
                ?>
                <div style="margin: 0 0.5rem 1rem" id="navRecaptcha"></div>
                <input type="submit" value="Sign Up">
            </form>
        </div>
    </div>
</div>


<script>
var googleKey = '<?php the_field('google_recaptcha_key', 'option') ?>';
var navRecaptcha;
var showingRecaptcha;
var CaptchaCallback = function() {
    navRecaptcha = grecaptcha.render('navRecaptcha', {
            'sitekey' : googleKey,
        }
    );
    var showingElement = document.getElementById('showingRecaptcha');
    if(showingElement) {
        showingRecaptcha = grecaptcha.render(showingElement, {'sitekey' : googleKey });
        grecaptcha.reset(navRecaptcha);
    }
};
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit"
    async defer>
</script>
