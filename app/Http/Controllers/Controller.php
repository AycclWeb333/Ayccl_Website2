<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;  

    /**
     * Get common file validation rules and messages.
     * 
     * @return array
     */
    protected function getFileValidation()
    {
        return [
            'rules' => [
                'files'       => 'nullable', // Individual controllers can add 'required' if needed
                'files.*'     => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB
                'files_pdf'   => 'nullable',
                'files_pdf.*' => 'mimes:pdf|max:10240', // 10MB
            ],
            'messages' => [
                'files.*.image'    => __('adminlte::adminlte.file_type_image'),
                'files.*.mimes'    => __('adminlte::adminlte.file_type_image'),
                'files.*.max'      => __('adminlte::adminlte.file_limit_image'),
                'files_pdf.*.mimes' => __('adminlte::adminlte.file_type_pdf'),
                'files_pdf.*.max'   => __('adminlte::adminlte.file_limit_pdf'),
            ]
        ];
    }
}
