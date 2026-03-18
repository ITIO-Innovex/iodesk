<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Web_form_entry_pdf extends App_pdf
{
    protected $data = [];

    public function __construct($data = [])
    {
        $this->data = is_array($data) ? $data : [];

        parent::__construct();

        $formName = isset($this->data['form']['name']) ? $this->data['form']['name'] : 'Web Form';
        $entryId  = isset($this->data['entry']['id']) ? (int) $this->data['entry']['id'] : 0;
        $this->SetTitle($formName . ' - Entry #' . $entryId);
    }

    public function prepare()
    {
        $html = $this->buildHtml();
        $this->writeHTML($html, true, false, true, false, '');

        return $this;
    }

    /**
     * Document type identifier used by the PDF system.
     *
     * @return string
     */
    protected function type()
    {
        return 'web_form_entry';
    }

    /**
     * Required by the abstract App_pdf, but not used because we render HTML manually in prepare().
     *
     * @return string
     */
    protected function file_path()
    {
        return '';
    }

    protected function buildHtml()
    {
        $form  = isset($this->data['form']) && is_array($this->data['form']) ? $this->data['form'] : [];
        $fields = isset($this->data['fields']) && is_array($this->data['fields']) ? $this->data['fields'] : [];
        $entry  = isset($this->data['entry']) && is_array($this->data['entry']) ? $this->data['entry'] : [];
        $entryData = isset($this->data['entry_data']) && is_array($this->data['entry_data']) ? $this->data['entry_data'] : [];

        $logoUrl = pdf_logo_url();
        $formName = $this->esc($form['name'] ?? 'Web Form');
        $entryId = (int) ($entry['id'] ?? 0);
        $createdAt = $this->esc($entry['created_at'] ?? '');

        $html = '<style>
          .title { font-size: 16px; font-weight: bold; }
          .muted { color:#666; font-size:10px; }
          table.meta { width:100%; }
          table.kv { width:100%; border-collapse:collapse; }
          table.kv th { width:30%; background:#f5f5f5; border:1px solid #ddd; padding:6px; font-size:12px; text-align:left; }
          table.kv td { width:70%; border:1px solid #ddd; padding:6px; font-size:12px; }
        </style>';

        $html .= '<br><br><table class="meta"><tr>';
        $html .= '<td width="30%">' . $logoUrl . '</td>';
        $html .= '<td width="70%" style="text-align:right;">';
        $html .= '<div class="title">' . $formName . '</div>';
        $html .= '<div class="muted">Entry #' . $entryId . ' | Created: ' . $createdAt . '</div>';
        $html .= '</td></tr></table><br>';

        $html .= '<br><br><table class="kv" width="100%">';
        foreach ($fields as $f) {
            $label = $this->esc($f['label'] ?? $f['name'] ?? '');
            $name  = $f['name'] ?? '';
            $val   = $entryData[$name] ?? '';

            if (is_array($val)) {
                $out = '';
                foreach ($val as $p) {
                    if (!$p) { continue; }
                    $out .= $this->esc(basename($p)) . '<br>';
                }
                $val = $out;
            } else {
                $val = $this->esc((string) $val);
            }

            $html .= '<tr><th>' . $label . '</th><td>' .  html_entity_decode($val) . '</td></tr>';
        }
        $html .= '</table>';

        return $html;
    }

    protected function esc($str)
    {
        return htmlspecialchars((string) $str, ENT_QUOTES, 'UTF-8');
    }
}

