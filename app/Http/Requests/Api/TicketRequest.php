<?php

namespace App\Http\Requests\Api;


use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function rules()
    {
        return [
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ];
    }
}
