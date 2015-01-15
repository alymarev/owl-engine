<?php

/**
 * Created by PhpStorm.
 * User: лымарев
 * Date: 28.12.2014
 * Time: 12:37
 */
class AbstractMail
{
    private $view;
    private $from;
    private $from_name;
    private $type = "text/html";
    private $encoding = "utf-8";

    /**
     * @param $view
     * @param $from
     */
    function __construct($view, $from)
    {
        $this->from = $from;
        $this->view = $view;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * @param mixed $from_name
     */
    public function setFromName($from_name)
    {
        $this->from_name = $from_name;
    }

    public function send($to, $data, $template)
    {
        $from = "=?utf-8?B?" . base64_encode($this->from_name) . "?=" . " <" . $this->from . ">";
        $headers = "From: " . $from . "\r\nReply-To: " . $from . "\r\nContent-type: " . $this->type . "; charset=\"" . $this->encoding . "\"\r\n";
        $text = $this->view->render($template, $data, true);
        $lines = preg_split("/\\r\\n?|\\n/", $text);
        $subject = $lines[0];
        $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
        $body = "";
        for ($i = 1; $i < count($lines); $i++) {
            $body .= $lines[$i];
            if ($i != count($lines) - 1) $body .= "\n";
        }
        if ($this->type = "text/html") $body = nl2br($body);
        return mail($to, $subject, $body, $headers);
    }


}