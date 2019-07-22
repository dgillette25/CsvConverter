<?php

namespace CsvConverter;

use Exception;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Converter
{
    protected $headers = array(
            'Content-type' => 'text/csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=new-file.csv',
            'Expires' => '0'
        );
    protected $callback;
    protected $file_headers = [];

    /**
     * Converts an Array into Csv
     *
     * @param array $data
     * @param array $columnHeaders
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function convertArray(array $data, array $columnHeaders = [])
    {
        if (!count($data)) {
            return "";
        }
        $columns = $this->getFileHeaders($columnHeaders ?: $data[0]);
        return $this->convertDataToFile($data, $columnHeaders, $columns);
    }

    /**
     * Converts an Array into Csv
     *
     * @param stdClass $data
     * @param array $columnHeaders
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function convertObject(stdClass $data, array $columnHeaders = [])
    {
        if (!count($data)) {
            return "";
        }
        $columns = $this->getFileHeaders($columnHeaders ?: $data[0]);
        return $this->convertDataToFile($data, $columnHeaders, $columns);
    }

    private function convertDataToFile($data, $columnHeaders, $columns)
    {
        try {
            $this->callback = function () use ($data, $columnHeaders, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($data as $data_obj) {
                    fputcsv($file, $this->getDataFields($data_obj, $columnHeaders));
                }
                fclose($file);
            };
            return $this->formatCsvForReturn();
        } catch (Exception $e) {
            echo $e->errorMessage();
        }
    }

    /**
     * @param string $fileName
     */
    public function setName(string $fileName)
    {
        $this->headers['Content-Disposition'] = "attachment; filename={$fileName}.csv";
    }

    /**
     * @param array $fileHeaders
     */
    public function setFileHeaders(array $fileHeaders)
    {
        $this->file_headers = $fileHeaders;
    }

    /**
     * @param $data_obj
     * @return array
     */
    public function getFileHeaders(array $data_obj = []) : array
    {
        if (empty($this->file_headers)) {
            $this->file_headers = array_keys((array)$data_obj);
        }

        return $this->file_headers;
    }

    /**
     * Create Array for CSV row from object
     *
     * @param $data_obj
     * @param array $columnHeaders
     * @return array
     */
    public function getDataFields($data_obj, array $columnHeaders = []) : array
    {
        $data_obj = (array)$data_obj;
        if (empty($columnHeaders)) {
            return $data_obj;
        }
        $csv_row = [];
        foreach ($columnHeaders as $field) {
            $csv_row[] = $data_obj[$field];
        }
        return $csv_row;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function formatCsvForReturn()
    {
        return new StreamedResponse($this->callback, 200, $this->headers);
    }
}
