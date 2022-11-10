@if(Session::has('Failed'))
    <div class="error-bg mb-2" style="color:red">
        <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong') }}</div>
        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li>{{Session::get('Failed')}}</li>
        </ul>
    </div>
@endif

@if(Session::has('Success'))
    <div class="success-bg mb-2" style="color:green">
        <div class="font-medium text-green-600">{{Session::get('Success')}}</div>
    </div>
@endif