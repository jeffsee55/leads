<?php

namespace Heidi\Plugin\Controllers\Frontend;

use Heidi\Core\Controller;
use ReCaptcha\ReCaptcha;
use Heidi\Plugin\Models\MailNotifier;

class UserLoginController extends Controller
{
    public function renderFooterSignUp()
    {
        if( !is_user_logged_in() ) :
            echo view('forms.alert-sign-up');
        endif;
    }

    public function renderLogin()
    {
        if( !is_user_logged_in() ) :
            echo ' | ';
            echo '<a href="#" data-reveal-id="signupModal" id="sign-up-button" class="button">Sign Up</a>';
            echo view('forms.sign-up');
            echo '<a href="#" data-reveal-id="signinModal" id="sign-in-button" class="button">Sign In</a>';
            echo view('forms.sign-in');
        else :
            echo sprintf( '| <a class="favorites" href="/profile">%s\'s <i class="fa fa-heart"></i> <span>Favorites</span></a>', get_userdata(get_current_user_id())->first_name);
            echo '<form method="post" id="sign-out" action="'. esc_url( admin_url('admin-post.php') ) .'">';
            echo '<input type="hidden" name="action" value="q4_sign_out" />';
            echo '<input type="submit" class="button" value="Sign Out" />';
            echo '</form>';
        endif;
    }

    public function handleSignOut()
    {
        wp_logout();
        $url = $this->setRedirectUrl(get_site_url(), 'sign_out_success');
        wp_redirect($url);
        die();
    }

    public function handleSignUp()
    {
        $url = $this->setRedirectUrl($_SERVER['HTTP_REFERER'], 'recaptcha_failure');
        $secret = get_field('google_recaptcha_secret', 'option');
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
        if ($resp->isSuccess()) {
            if($lead = get_user_by('email', $_POST['email']))
            {
                $response = $this->signIn($lead);
                $this->redirect($response);
            } else {
                $lead_id = wp_insert_user([
                    'user_login' => $_POST['email'],
                    'user_email' => $_POST['email'],
                    'user_pass' => $_POST['phone'],
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'role' => 'lead'
                ]);
                $lead = get_user_by('ID', $lead_id);
                update_user_meta($lead->ID, '_phone_number', $_POST['phone']);
                update_user_meta($lead->ID, '_password', $_POST['phone']);
                if(isset($_POST['agent']))
                    update_user_meta($lead->ID, '_agent_id', $_POST['agent']);
                $response = $this->signIn($lead);
                (new MailNotifier)->sendNewLeadMessages($lead);
                wp_redirect(get_site_url() . '/sign-up-welcome');
                die();
            }
        } else {
            $response = $resp->getErrorCodes();
            $this->redirect((object)['error' => $response]);
        }
    }

    function signIn($lead)
    {
        return wp_signon([
            'user_login' => $lead->user_email,
            'user_password' => get_user_meta($lead->ID, '_password', true),
            'remember' => true
        ]);
    }

    function redirect($response, $newUser = false)
    {
        if(is_wp_error($response))
        {
            $message = 'sign_up_error';
        } elseif($response->error) {
            $message = 'recaptcha_error';
        } elseif($newUser) {
            $message = 'sign_up_success';
        } else {
            $message = 'sign_in_success';
        }
        $url = $this->setRedirectUrl($_SERVER['HTTP_REFERER'], $message);
        wp_redirect($url);
        die();
    }

    function setRedirectUrl($url, $message)
    {
        $url = parse_url($url);
        parse_str($url['query'], $params);
        $params['message_type'] = $message;
        $output = implode('&', array_map(
            function ($v, $k) { return sprintf("%s=%s", $k, $v); },
            $params,
            array_keys($params)
        ));
        $query = '?' . $output;
        return get_site_url() . $query;
    }
}
