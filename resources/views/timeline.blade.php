<!-- resources/views/timeline.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Timeline</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Document Timeline</h1>

        <div class="card mt-3">
            <div class="card-header">
                Document ID: {{ $document->documentId }}
            </div>
            <div class="card-body">
                <h5 class="card-title">Sender: {{ $document->senderDetail->name }}</h5>
                <p class="card-text">Message Title: {{ $document->messageTitle }}</p>
                <p class="card-text">Status: {{ $document->status }}</p>

                <!-- Timeline -->
                <ul class="timeline">
                    <li>
                        <p>Sent: {{ date('Y-m-d H:i:s', $document->createdDate) }}</p>
                    </li>
                    @if($document->senderDetail->isViewed)
                        <li>
                            <p>Opened: {{ date('Y-m-d H:i:s', $document->activityDate) }}</p>
                        </li>
                    @endif
                    @foreach($document->signerDetails as $signer)
                    <li>
                        <p>Signed by {{ $signer->signerName }}:
                            @if(isset($signer->activityDate))
                                {{ date('Y-m-d H:i:s', $signer->activityDate) }}
                            @else
                                Not signed yet
                            @endif
                        </p>
                    </li>
                @endforeach
                    <li>
                        <p>Received: {{ date('Y-m-d H:i:s', $document->expiryDate) }}</p>
                    </li>
                </ul>

                <!-- Download PDF Button -->
                <form method="GET" action="{{ url('download-pdf') }}">
                    @csrf
                    <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                    <button type="submit" class="btn btn-primary">Download PDF</button>
                </form>
            </div>
        </div>

        <!-- Timeline content goes here -->

        <!-- Back to Document List Button -->
        <a href="{{ url('document-list') }}" class="btn btn-secondary mt-3">Back to Document List</a>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
