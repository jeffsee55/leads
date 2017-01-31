<?php

namespace Heidi\Plugin\Controllers\Frontend;

use Heidi\Core\Controller;

class MessagesController extends Controller
{

    public function __construct()
    {
        add_filter('acf/load_field/name=agent', [$this, 'filterAgentName'], 10, 3);
    }

    public function filterAgentName($field)
    {
        $agents = get_users(['role__in' => ['agent', 'super_agent'], 'fields' => ['id', 'display_name']]);
        $agentOptions[null] = 'Select an agent';
        foreach($agents as $agent)
        {
            $agentOptions[$agent->id] = $agent->display_name;
        }

        $field['choices'] = $agentOptions;

        return $field;
    }

    public function renderModal()
    {
        global $post;

        ?>
            <style>
                .acf-form-submit {
                    padding: 1rem;
                    border-top: 1px solid #dadada;
                    background: #f9f9f9;
                }
                [data-name="interested_in"] {
                    display: none;
                }
            </style>
        <div id="SendModal" class="reveal-modal contact-modal" data-reveal>
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
            <div class="reveal-banner"></div>
            <div class="reveal-header">
                <h4>Ask the Experts</h4>
            </div>
            <div class="reveal-body">
                <h4><?php echo listing_address( $post->ID ) ?></h4>
                <h4><?php echo get_post_meta( $post->ID, '_city', true ) ?></h4>
            </div>
            <div class="">
                <?php
                $new_post = array(
                    'post_id' => 'new_post',
                    'new_post' => array(
                        'post_type' => 'message',
                        'post_status' => 'publish',
                        'post_title' => 'Potential Buyer - ' . $post->post_title
                    ),
                    'field_groups'       => array(103798), // Create post field group ID(s)
                    'form'               => true, // False if embedding in another form
                    'return'             => '', // Redirect to new post url
                    'html_before_fields' => '',
                    'label_placement' => 'left',
                    'instruction_placement' => 'field',
                    'html_after_fields'  => '<input type="hidden" name="listing_id" value="' . $post->ID . '">',
                    'submit_value'       => 'Send',
                    'updated_message'    => 'Message Sent',
                );
                acf_form( $new_post );
                ?>
            </div>
        </div>

        <?php
    }

    public function renderForm()
    {
        global $post;

        ?>
            <style>
                .acf-form {
                    border: 1px solid #dadada;
                }
                .acf-form-submit {
                    padding: 1rem;
                    border-top: 1px solid #dadada;
                    background: #f9f9f9;
                }
                [data-name="schedule_showing"], [data-name="date"] {
                    display: none;
                }
            </style>
                <?php
                $new_post = array(
                    'post_id' => 'new_post',
                    'new_post' => array(
                        'post_type' => 'message',
                        'post_status' => 'publish',
                        'post_title' => 'New Message',
                    ),
                    'field_groups'       => array(103798), // Create post field group ID(s)
                    'form'               => true, // False if embedding in another form
                    'return'             => '', // Redirect to new post url
                    'html_before_fields' => '',
                    'label_placement' => 'left',
                    'instruction_placement' => 'field',
                    'submit_value'       => 'Send',
                    'updated_message'    => 'Message Sent',
                );
                acf_form( $new_post );
                ?>

        <?php
    }
}
