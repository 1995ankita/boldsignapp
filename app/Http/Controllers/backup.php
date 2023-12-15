<?php

namespace App\Http\Controllers;

use CURLFile;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function showMailForm()
    {
        return view('template');
    }
    public function sendMail(Request $request)
    {

        $name = $request->input('name');
        $email = $request->input('email');
        $ccRecipients = [
            ['EmailAddress' => 'dharapateltka@gmail.com'],
            ['EmailAddress' => 'pooja.solapurmath461@gmail.com'],
            ['EmailAddress' => 'sytm33@gmail.com']
        ];
        // $ccRecipients = array(
        //     'ankita.hirpara56@gmail.com',
        //     'pooja.solapurmath461@gmail.com',
        //     'sytm33@gmail.com'
        // );


        $url = 'https://api.boldsign.com/v1/document/send';
        $apiKey = 'MGIwZDNmNzUtYWVmNi00ZDcyLTlmODYtNjNjMTk0MGM3Nzc0';
        $filePath = public_path('pdfs/file-example_PDF_500_kB.pdf');

        $postData = array(
            'AutoDetectFields' => 'true',
            'Message' => '',
            'Signers' => json_encode(array(
                'name' => $name,
                'emailAddress' => $email,
                'signerType' => 'Signer',
                'formFields' => array(
                    array(
                        'id' => 'string',
                        'name' => 'string',
                        'fieldType' => 'Signature',
                        'pageNumber' => 1,
                        'bounds' => array(
                            'x' => 500,
                            'y' => 50,
                            'width' => 100,
                            'height' => 100
                        ),
                        'isRequired' => true
                    )
                ),
                'locale' => 'EN'
            )),
            'Files' => new CURLFile($filePath, 'application/pdf', 'sample.pdf'),


            'CC' => [
                ['EmailAddress' => 'dharapateltka@gmail.com'],
                ['EmailAddress' => 'pooja.solapurmath461@gmail.com'],
                ['EmailAddress' => 'sytm33@gmail.com']
            ],


            // 'CC[0].EmailAddress' => 'dharapateltka@gmail.com',
            // 'CC[1].EmailAddress' => 'pooja.solapurmath461@gmail.com',
            // 'CC[2].EmailAddress' => 'sytm33@gmail.com',
            // 'CC[0].EmailAddress:sytm33@gmail.com',
            //  'CC' => json_encode($ccRecipients),
            'Title' => 'eSign Document',
        );

        $headers = array(
            'accept: application/json',
            'X-API-KEY: ' . $apiKey,
            'Content-Type: multipart/form-data'
        );
        $postData['CC'] = json_encode($ccRecipients);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);
        echo $response;
        return "Mail sent to $name ($email)";
    }
}
