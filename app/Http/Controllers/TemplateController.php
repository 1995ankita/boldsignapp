<?php

namespace App\Http\Controllers;

use CURLFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        // $this->apiKey = 'MGIwZDNmNzUtYWVmNi00ZDcyLTlmODYtNjNjMTk0MGM3Nzc0'; //pooja
        $this->apiKey = 'NWIzNDA1MGQtMjViNy00YTI0LWJiYjEtYTc5OWZmZTE1MTUy'; //dhara
    }
    public function showMailForm()
    {
        return view('form');
    }
    public function sendMail(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $OnBehalfOf = $request->input('OnBehalfOf');
        $url = 'https://api.boldsign.com/v1/document/send';
        $filePath = public_path('pdfs/file-example_PDF_500_kB.pdf');

        $signer = array(
            'name' => $name,
            'emailAddress' =>  $email,
            'signerType' => 'Signer',
            'formFields' => array(
                array(
                    'id' => 'string',
                    'name' => 'string',
                    'fieldType' => 'Signature',
                    'pageNumber' => 1,
                    'bounds' => array(
                        'x' => 100,
                        'y' => 100,
                        'width' => 200,
                        'height' => 25,
                    ),
                    'isRequired' => true,
                ),
            ),
            'locale' => 'EN',
        );

        $emailString = "dharapateltka@gmail.com, dharapateltka@gmail.com, sytm33@gmail.com , ankita.hirpara56@gmail.com,pooja.solapurmath461@gmail.com";
        $emails = array_map('trim', explode(',', $emailString));
        $uniqueEmails = array_unique($emails);

        $ccRecipients = array_map(function ($email) {
            return ['emailAddress' => $email];
        }, $uniqueEmails);

        foreach ($ccRecipients as $ccRecipient) {
            if ($ccRecipient['emailAddress'] === $signer['emailAddress']) {
                continue;
            }
            $ccFormData[] = [
                'name' => 'cc[]',
                'contents' => json_encode($ccRecipient),
            ];
        }

        $ccValues = [];
        foreach ($ccFormData as $index => $ccRecipient) {
            $ccValues[] = $ccRecipient['contents'];
        }

        $postData = array(
            'AutoDetectFields' => 'false',
            'Message' => '',
            'OnBehalfOf' => $OnBehalfOf,
            'Signers' => json_encode($signer),
            'cc' => $ccValues,
            'Files' => new CURLFile($filePath, 'application/pdf', 'sample.pdf'),
            'Title' => 'eSign Document',

        );
        // $postData = array(
        //     'AutoDetectFields' => 'true',
        //     'Message' => 'Sign the document',
        //     'Signers' => json_encode(array(
        //         'name' => $name,
        //         'emailAddress' => $email,
        //         'signerType' => 'Signer',
        //         'formFields' => array(
        //             array(
        //                 'id' => 'RadioButton1',
        //                 'name' => 'RadioButton',
        //                 'fieldType' => 'RadioButton',
        //                 'groupName' => 'ConditionalLogic',
        //                 'pageNumber' => 1,
        //                 'bounds' =>  array(
        //                     'x' => 50,
        //                     'y' => 50,
        //                     'width' => 20,
        //                     'height' => 20
        //                 ),
        //                 "value" => "off",
        //                 'isRequired' => false,
        //             ),
        //             array(
        //                 'id' => 'Radiobutton2',
        //                 'name' => 'RadioButton',
        //                 'fieldType' => 'RadioButton',
        //                 'groupName' => 'ConditionalLogic',
        //                 'pageNumber' => 1,
        //                 'bounds' => array(
        //                     'x' => 50,
        //                     'y' => 70,
        //                     'width' => 20,
        //                     'height' => 20
        //                 ),
        //                 "value" => "off",
        //                 "conditionalRules" => array(
        //                     array(
        //                         "fieldId" => "TextBoxField",
        //                         "isChecked" => true
        //                     )
        //                 ),
        //                 'isRequired' => false,
        //             ),
        //             array(
        //                 'id' => 'TextBoxField',
        //                 'name' => 'TextBoxField',
        //                 'fieldType' => 'TextBox',
        //                 'pageNumber' => 1,
        //                 'bounds' => array(
        //                     'x' => 50,
        //                     'y' => 100,
        //                     'width' => 200,
        //                     'height' => 80
        //                 ),
        //                 'isRequired' => true,
        //                 'multiline' => true,
        //             ),
        //             array(
        //                 'id' => 'correct',
        //                 'name' => 'correct',
        //                 'fieldType' => 'TextBox',
        //                 'pageNumber' => 1,
        //                 'bounds' => array(
        //                     'x' => 75,
        //                     'y' => 50,
        //                     'width' => 100,
        //                     'height' => 15
        //                 ),
        //                 'isRequired' => false,
        //                 'isReadOnly' => true,
        //                 "value" => "correct address",
        //             ),
        //             array(
        //                 'id' => 'incorrect',
        //                 'name' => 'incorrect',
        //                 'fieldType' => 'TextBox',
        //                 'pageNumber' => 1,
        //                 'bounds' => array(
        //                     'x' => 75,
        //                     'y' => 70,
        //                     'width' => 200,
        //                     'height' => 15
        //                 ),
        //                 'isRequired' => false,
        //                 'isReadOnly' => true,
        //                 'value' => 'incorrect address',
        //             )
        //         ),

        //         'locale' => 'EN'
        //     )),
        //     'cc' => $ccRecipients,

        //     'Files' => new CURLFile($filePath, 'application/pdf', 'sample.pdf'),
        //     'Title' => 'eSign Document',
        // );
        $headers = array(
            'X-API-KEY: ' . $this->apiKey,
            'Content-Type: multipart/form-data'
        );

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
    public function createIdentity()
    {
        $name = 'dhara';
        $email = 'dharakadivar25@gmail.com';
        $url = 'https://api.boldsign.com/v1/senderIdentities/create';

        $data = [
            'name' => $name,
            'email' => $email,
        ];

        $headers = [
            'accept: */*',
            'X-API-KEY: ' . $this->apiKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        curl_close($ch);
        echo $response;
        return "Identity mail sent to $name ($email)";
    }
    public function sendRemind(Request $request)
    {
        $documentId = $request->input('documentId');
        $url = "https://api.boldsign.com/v1/document/remind?documentId=$documentId";

        $response = Http::withHeaders([
            'accept' => '*/*',
            'X-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->post($url, [
            "message" => "Reminder to sign the document",
        ]);

        if ($response->failed()) {
            return 'HTTP request failed: ' . $response->body();
        }
        return "Reminder sent.";
    }
    public function list()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.boldsign.com/v1/document/list?page=1&pagesize=30',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-API-KEY: NWIzNDA1MGQtMjViNy00YTI0LWJiYjEtYTc5OWZmZTE1MTUy'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return view('list', ['response' => json_decode($response)]);
    }
    public function downloadPdf(Request $request)
    {
        $documentId = $request->input('documentId');
        $apiUrl = "https://api.boldsign.com/v1/document/download?documentId=$documentId";

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'X-API-KEY' => $this->apiKey,
        ])->get($apiUrl);

        if ($response->successful()) {

            $pdfContent = $response->body();
            Storage::disk('pdfs')->put("document_$documentId.pdf", $pdfContent);
            return response()->json(['message' => 'PDF downloaded and stored successfully']);
        } else {
            return response()->json(['error' => 'Failed to download PDF'], $response->status());
        }
    }
    public function downloadAudittrail(Request $request)
    {
        $documentId = $request->input('documentId');
        $apiUrl = "https://api.boldsign.com/v1/document/downloadAuditLog?documentId=$documentId";
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'X-API-KEY' => $this->apiKey,
        ])->get($apiUrl);

        if ($response->successful()) {
            $pdfContent = $response->body();
            Storage::disk('audit-pdfs')->put("audit-trail_$documentId.pdf", $pdfContent);
            return response()->json(['message' => 'PDF downloaded and stored successfully']);
        } else {
            return response()->json(['error' => 'Failed to download PDF'], $response->status());
        }
    }
}
