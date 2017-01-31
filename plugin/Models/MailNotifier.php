<?php

namespace Heidi\Plugin\Models;

class MailNotifier
{
    public function sendContactMessage($data, $seller = false)
    {
        if(!isset($data['title']))
            $data['title'] = 'Potential Buyer';

        if(isset($data['listing_title']))
        {
            $data['listing_title'] = get_the_title($data['listing_id']);
            $data['title'] .= ' - ' . $data['listing_title'];
        }

        $lead = get_user_by( 'user_email', $data['lead_id']);

        if($lead)
        {
            $agent_id = get_user_meta( $lead->ID, '_agent_id', true );
            if( $agent_id ) {
                $agent = get_user_by( 'ID', $agent_id );
            }
        }

        $agent_email_array = array(
            'pam@drewsineath.com',
            'drew@drewsineath.com',
        );

        if( isset($data['agent']['user_email']))
        {
            $agent_email_array[] = $data['agent']['user_email'];
        }

        $agentEmails = array_unique($agent_email_array);

        $headers = array('Content-Type: text/html; charset=UTF-8');

        ob_start();
        view('emails.message', compact('data'));
        $markup = ob_get_clean();

        wp_mail( $agentEmails, 'New Message', $markup, $headers);
    }

    function sendNewLeadMessages($lead)
    {
        $this->newLeadAgents($lead);
        $this->newLeadWelcome($lead);
    }

    private function newLeadAgents($lead)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');

        ob_start();
        view('emails.new-lead', compact('lead'));
        $markup = ob_get_clean();

        $agent_email_array = array(
            'pam@drewsineath.com',
            'drew@drewsineath.com',
        );

        $agent_id = get_user_meta( $lead->ID, '_agent_id', true );

        if( $agent_id ) {
            $agent_email_array[] = get_userdata($agent_id)->user_email;
        }

        $agentEmails = array_unique($agent_email_array);

        wp_mail( $agentEmails, 'New Lead', $markup, $headers);

    }

    private function newLeadWelcome($lead)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8');

        ob_start();
        view('emails.new-lead-welcome', compact('lead'));
        $markup = ob_get_clean();

        wp_mail( $lead->user_email, 'New Lead', $markup, $headers);

    }
}
