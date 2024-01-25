<?php
class Mailer
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('email');
    }
    //=============================================================
    // Eamil Templates
    public function mail_template($to = '', $slug = '', $mail_data = '')
    {

        $template = $this->CI->db->get_where('email_templates', array('slug' => $slug))->row_array();

        // var_dump($template);exit();

        $body = $template['body'];

        $template_id = $template['id'];

        $data['head'] = $subject = $template['subject'];

        $data['content'] = $this->mail_template_variables($body, $slug, $mail_data);

        $data['title'] = $template['name'];

        $template = $this->CI->load->view('admin/general_settings/email_templates/email_preview', $data, true);

        send_email($to, $subject, $template);

        return true;
    }

    //=============================================================
    // GET Eamil Templates AND REPLACE VARIABLES
    public function mail_template_variables($content, $slug, $data = '')
    {
        switch ($slug) {
            case 'email-verification':
                $content = str_replace('{FULLNAME}', $data['fullname'], $content);
                $content = str_replace('{VERIFICATION_LINK}', $data['verification_link'], $content);
                return $content;
                break;

            case 'forget-password':
                $content = str_replace('{FULLNAME}', $data['fullname'], $content);
                $content = str_replace('{RESET_LINK}', $data['reset_link'], $content);
                return $content;
                break;

            case 'general-notification':
                $content = str_replace('{CONTENT}', $data['content'], $content);
                return $content;
                break;

            default:
                # code...
                break;
        }
    }

    //=============================================================
    public function registration_email($username, $email_verification_link)
    {
        $login_link = base_url('auth/login');

        $tpl = '<h3>Hi ' . strtoupper($username) . '</h3>
            <p>Welcome to IDDMS!</p>
            <p>Active your account with the link above :</p>
            <p>' . $email_verification_link . '</p>

            <br>
            <br>

            <p>Regards, <br>
            <p>© 2022 IDDMS - All rights reserved</p>
            </p>
    ';
        return $tpl;
    }

    //=============================================================
    public function pwd_reset_email($username, $reset_link)
    {
        $tpl = '<h3>Hi ' . strtoupper($username) . '</h3>
            <p>Welcome to IDDMS!</p>
            <p>We have received a request to reset your password. If you did not initiate this request, you can simply ignore this message and no action will be taken.</p>
            <p>To reset your password, please click the link below:</p>
            <p>' . $reset_link . '</p>

            <br>
            <br>

            <p>© 2022 IDDMS - All rights reserved</p>
    ';
        return $tpl;
    }

}