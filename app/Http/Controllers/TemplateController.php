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
        $this->apiKey = 'MGIwZDNmNzUtYWVmNi00ZDcyLTlmODYtNjNjMTk0MGM3Nzc0';//pooja
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
                        'id' => 'string',
                        'name' => 'string',
                        'fieldType' => 'Signature',
                        'pageNumber' => 1,
                        'bounds' => array(
                            // 'x' => 500,
                            // 'y' => 50,
                            'width' => 100,
                            'height' => 100
                        ),
                        'isRequired' => true
                    )
                ),
                'locale' => 'EN'
            )),
            'cc' => json_encode(array(
                "emailAddress" => "ankita.hirpara56@gmail.com",

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
