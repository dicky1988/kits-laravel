@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header fw-semibold">
                <i class="fa fa-file-word text-primary me-2"></i>
                Edit Dokumen Word
            </div>

            <div class="card-body p-0">
                <div id="editor" style="width:100%; height:100vh;"></div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="http://localhost:9001/web-apps/apps/api/documents/api.js"></script>

    <script>
        new DocsAPI.DocEditor("editor", {
            documentType: "word",
            document: {
                fileType: "docx",
                title: "clean.docx",
                key: "{{ $key }}",
                url: "{{ $fileUrl }}"
            }
        });
    </script>
@endpush
