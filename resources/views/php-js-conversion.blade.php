<script type='text/javascript'>
    {{-- variables from php file --}}
    var cityLongitude = {{ $cityLongitude }}; 
    var cityLatitude= {{ $cityLatitude }}; 
    var cityTemperature = {{ $cityTemperature }}; 
    var pattern = '{{ $pattern }}'; 
    var cityEntered = '{{ ($cityOne != '') ? true : false }}'; 
    var chosenCity = '{{ $chosenCity }}';
    var score = {{ $score }};
    var dropDown = {!! $jsMap !!};
    var cityTraits = {!! $cityTraits !!};
        
    {{-- set-up variables --}}
    var regex = new RegExp(pattern, 'i');
    var imagePath = 'images/' + chosenCity + '.png'; 
    var imagePath = imagePath.split(' ').join('_');
    var error = false; 
    @if(count($errors) > 0)  
        error = true; 
    @endif
</script>
