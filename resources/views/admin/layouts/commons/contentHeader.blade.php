<div class="mb-4">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach($breadcumb as $b)
                @if($b[1]=='active')
                    <li class="breadcrumb-item active"  aria-current="page">@lang('commons/content_header.'.$b[0])</li>
                @else
                    <li class="breadcrumb-item"><a href="@if(isset($b[2])){{ route($b[1],$b[2]) ?? ''}}@else {{ route($b[1]) ?? ''}} @endif">@lang('commons/content_header.'.$b[0])</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>
