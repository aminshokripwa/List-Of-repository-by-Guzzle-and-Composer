<?php

namespace aminshokripwa\Download;

/**
 * Prepare file to download
 */
class Download
{
    public $data;
    public $type;
    /**
     * Considers initial values
     *
     * @param  mixed $data
     * @param  mixed $type
     * @return void
     */
    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }
    /**
     * Perpare File to choose id , name , lastname for each line
     *
     * @param  array $data
     * @return mixed
     */
    public function perpareFile($data)
    {
        $res[] = 'id,name,description';
        foreach ($data as $d) {
            $res[] = $d['id'] . ',' . $d['name'] . ',' . $d['description'];
        }
        return $res;
    }
    /**
     * Save files in order to download them according to their extension
     *
     * @return mixed
     */
    public function serveFileToDownload()
    {
        $filename = time() . '.' . $this->type;
        header('Content-Encoding: UTF-8');
        header('Content-type: application/octet-stream; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $data = $this->perpareFile($this->data);
        $fp = fopen('php://output', 'wb');
        if ($this->type == 'csv') {
            foreach ($data as $line) {
                $val = explode(",", $line);
                fputcsv($fp, $val);
            }
        } elseif ($this->type == 'html') {
            $html = '<html><body><table>';
            foreach ($data as $line) {
                $val = explode(",", $line);
                $html .='<tr><td>'.$val[0].'</td><td>'.$val[1].'</td><td>'.$val[2].'</td></tr>';
            }
            $html .='</table></body></html>';
            fwrite($fp, $html);
        } else {
            fwrite($fp, json_encode($data));
        }
        fclose($fp);
    }
}
