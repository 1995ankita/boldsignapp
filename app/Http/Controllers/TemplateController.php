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
        $this->apiKey = 'MGIwZDNmNzUtYWVmNi00ZDcyLTlmODYtNjNjMTk0MGM3Nzc0'; //pooja
        //$this->apiKey = 'NWIzNDA1MGQtMjViNy00YTI0LWJiYjEtYTc5OWZmZTE1MTUy'; //dhara
    }
    public function showMailForm()
    {
        return view('form');
    }
    public function sendMail(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $url = 'https://api.boldsign.com/v1/document/send';
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
                        'id' => 'RadioButton1',
                        'name' => 'RadioButton',
                        'fieldType' => 'RadioButton',
                        'groupName' => 'ConditionalLogic',
                        'pageNumber' => 1,
                        'bounds' =>  array(
                            'x' => 50,
                            'y' => 50,
                            'width' => 20,
                            'height' => 20
                        ),
                        "value" => "off",
                        'isRequired' => false,
                    ),
                    array(
                        'id' => 'Radiobutton2',
                        'name' => 'RadioButton',
                        'fieldType' => 'RadioButton',
                        'groupName' => 'ConditionalLogic',
                        'pageNumber' => 1,
                        'bounds' => array(
                            'x' => 50,
                            'y' => 70,
                            'width' => 20,
                            'height' => 20
                        ),
                        "value" => "off",
                        "conditionalRules" => array(
                            array(
                                "fieldId" => "TextBoxField",
                                "isChecked" => true
                            )
                        ),
                        'isRequired' => false,
                    ),
                    array(
                        'id' => 'TextBoxField',
                        'name' => 'TextBoxField',
                        'fieldType' => 'TextBox',
                        'pageNumber' => 1,
                        'bounds' => array(
                            'x' => 50,
                            'y' => 100,
                            'width' => 200,
                            'height' => 80
                        ),
                        'isRequired' => true,
                        'multiline' => true,
                    ),
                    array(
                        'id' => 'correct',
                        'name' => 'correct',
                        'fieldType' => 'TextBox',
                        'pageNumber' => 1,
                        'bounds' => array(
                            'x' => 75,
                            'y' => 50,
                            'width' => 100,
                            'height' => 15
                        ),
                        'isRequired' => false,
                        'isReadOnly' => true,
                        "value" => "correct address",
                    ),
                    array(
                        'id' => 'incorrect',
                        'name' => 'incorrect',
                        'fieldType' => 'TextBox',
                        'pageNumber' => 1,
                        'bounds' => array(
                            'x' => 75,
                            'y' => 70,
                            'width' => 200,
                            'height' => 15
                        ),
                        'isRequired' => false,
                        'isReadOnly' => true,
                        'value' => 'incorrect address',
                    )
                ),

                'locale' => 'EN'
            )),
            'cc' => json_encode(array(
                "emailAddress" => "pooja.solapurmath461@gmail.com",

            )),
            'cc' => json_encode(array(
                "emailAddress" => "sytm33@gmail.com",

            )),
            'Files' => new CURLFile($filePath, 'application/pdf', 'sample.pdf'),
            'Title' => 'eSign Document',
        );

        // $signerData = [
        //     'name' => 'Hanky',
        //     'emailAddress' => 'hankyWhites@cubeflakes.com',
        //     'signerType' => 'Signer',
        //     'formFields' => [
        //         [
        //             'id' => 'string',
        //             'name' => 'string',
        //             'fieldType' => 'RadioButton',
        //             'groupName' => 'Group1',
        //             'pageNumber' => 1,
        //             'bounds' => [
        //                 'x' => 50,
        //                 'y' => 50,
        //                 'width' => 20,
        //                 'height' => 20
        //             ],
        //             'isRequired' => true,
        //         ],
        //         [
        //             'id' => 'string1',
        //             'name' => 'string1',
        //             'fieldType' => 'RadioButton',
        //             'groupName' => 'Group1',
        //             'pageNumber' => 1,
        //             'bounds' => [
        //                 'x' => 100,
        //                 'y' => 100,
        //                 'width' => 20,
        //                 'height' => 20
        //             ],
        //             'isRequired' => true,
        //         ]
        //     ],
        //     'locale' => 'EN'
        // ];

        // $postData = array(
        //     'AutoDetectFields' => 'true',
        //     'Message' => '',
        //     'Signers' =>  json_encode($signerData),
        //     'Files' => new CURLFile($filePath, 'application/pdf', 'sample.pdf'),
        //     'Title' => 'eSign Document',
        // );


        $headers = array(
            'accept: application/json',
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
                'X-API-KEY: MGIwZDNmNzUtYWVmNi00ZDcyLTlmODYtNjNjMTk0MGM3Nzc0'
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
