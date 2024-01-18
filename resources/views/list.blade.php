<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document List</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<body>
    <div class="container col-md-8 mt-5">
        <h1>Document Activity List</h1>

        <table class="table table-bordered mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>Document ID</th>
                    <th>Sender</th>
                    <th>Created Date/Sent for Sign</th>
                    <th>Activity Date</th>
                    <th>Activity By</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th style="width: 170px;">Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($response->result as $document)
                    <tr>
                        <td>{{ $document->documentId }}</td>
                        <td>{{ $document->senderDetail->name }}</td>
                        <td>{{ date('Y-m-d H:i:s', $document->createdDate) }}</td>
                        <td>{{ date('Y-m-d H:i:s', $document->activityDate) }}</td>
                        <td>{{ $document->activityBy }}</td>
                        <td>{{ $document->messageTitle }}</td>
                        <td>{{ $document->status }}</td>

                        {{-- <td>
                            <form method="GET" action="{{ url('download-pdf') }}">
                                @csrf
                                <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                                <button type="submit" class="btn btn-link">PDF</button>
                            </form>
                            @if ($document->status != 'Completed')
                                <form method="POST" action="{{ url('sendRemind') }}">
                                    @csrf
                                    <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                                    <button type="submit" class="btn btn-link">Remind</button>
                                </form>
                                <form method="POST" action="{{ url('extendExpiry') }}">
                                    @csrf
                                    <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                                    <button type="submit" class="btn btn-link">Extend expiry</button>
                                </form>
                            @endif
                            @if ($document->status == 'Completed')
                                <form method="GET" action="{{ url('download-audittrail') }}">
                                    @csrf
                                    <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                                    <button type="submit" class="btn btn-link"
                                        style="white-space: nowrap;">Audit-trail</button>
                                </form>
                            @endif
                        </td> --}}
                        <td>
                            <div class="d-flex flex-column align-items-start">
                                <form method="GET" action="{{ url('download-pdf') }}">
                                    @csrf
                                    <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                                    <button type="submit" class="btn btn-link">Download PDF</button>
                                </form>
                                <form method="POST" action="{{ url('revokeDocument') }}">
                                    @csrf
                                    <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                                    <button type="submit" class="btn btn-link">Revoke Document</button>
                                </form>
                                @if ($document->status != 'Completed')
                                    <form method="POST" action="{{ url('sendRemind') }}">
                                        @csrf
                                        <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                                        <button type="submit" class="btn btn-link">Send Reminder</button>
                                    </form>

                                    <form method="POST" action="{{ url('extendExpiry') }}">
                                        @csrf
                                        <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                                        <button type="submit" class="btn btn-link">Extend Expiry</button>
                                    </form>
                                @endif

                                @if ($document->status == 'Completed')
                                    <form method="GET" action="{{ url('download-audittrail') }}">
                                        @csrf
                                        <input type="hidden" name="documentId" value="{{ $document->documentId }}">
                                        <button type="submit" class="btn btn-link"
                                            style="white-space: nowrap;">Audit-trail</button>
                                    </form>
                                @endif
                            </div>
                        </td>



                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
