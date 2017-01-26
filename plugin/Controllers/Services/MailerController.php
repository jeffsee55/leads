<?php

namespace Heidi\Plugin\Controllers\Services;

use Heidi\Core\Controller;
use Heidi\Core\Q4_List_Table;

class MailerController extends Controller
{
    public function registerSMTP($phpmailer) {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '8661db25c9b5dd';
        $phpmailer->Password = 'dde285839762da';
        $phpmailer->From = "info@frazerfinerfoods.com";
        $phpmailer->FromName = "Contact";
    }
}
