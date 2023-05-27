@if ($message = Session::get('success'))
    <div class="toast show d-flex align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <strong>Success!</strong> {{ $message ?? ''}}
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
@endif


@if ($message = Session::get('error'))
    <div class="toast show d-flex align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <strong>Error!</strong> {{ $message ?? ''}}
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
@endif


@if ($message = Session::get('warning'))
    <div class="toast show d-flex align-items-center text-white bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <strong>Warning!</strong> {{ $message ?? ''}}
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
@endif


@if ($message = Session::get('info'))
    <div class="toast show d-flex align-items-center text-white bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <strong>Info!</strong> {{ $message ?? ''}}
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
@endif


@if ($errors->any())
    <div class="toast show d-flex align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <strong>Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
@endif

<br>


