@extends('layouts.master')

@push('head')
    <link href='https://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet'>
@endpush 

@section('content')
    <div class='loader'>
        <p>{{$loadStatement}}</p>
    </div>
    <div class='visiblePage'>
        <header>
            <div class='jumbotron'>
                {{-- Second part of "if" statement: when data is valid but no city found --}}
                @if(count($errors) > 0 || ($chosenCity == "no city found" && old('cityOne')))
                    <h1>Flight Delayed</h1>
                    <p id = 'tagline'>See message below</p>
                @else
                    <h1>City Picker</h1>
                    <p id = 'tagline'>Find a city to travel or live in</p>
                @endif
                    <a href='#' data-toggle='popover' data-html='true' id='info' title='{{$popTitle}}' data-content='{{$popContent}}' data-trigger='click'>
                        <span class='glyphicon glyphicon-globe'></span>
                    </a>
            </div>
            @if(count($errors) > 0)
                <div class='alert alert-danger'>
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
                <br>
            {{-- elseif: main cause of "no city found" is max miles being too small --}}
            @elseif($chosenCity == 'no city found' && old('cityOne')) 
                <div class='alert alert-danger'>
                    City not found: try increasing the max miles
                    <br>
                </div>
                <br>
            @elseif(!$submitted)
                <div class = 'tutorial'>
                    <img src='images/giphy.gif' id = 'transition' height = 120 alt = 'dancing dots for modern visual effect'>
                    <div id='tutorialContent'>
                        {{-- Mini tutorial generated with JS --}}
                    </div>
                    <br><br><br>
                </div>
                <br>
            @endif
        </header>

        <main>
            @if($submitted && count($errors) == 0)
                <div class = 'infoSquare leftSquare' id= 'mapZoom'></div>
                <div class = 'infoSquare leftSquare bottomSquare' id='weatherBlock'></div>
                <div class = 'infoSquare rightSquare' id='mapBlock'></div>
                <div class = 'infoSquare rightSquare bottomSquare' id='infoBlock'></div>
            @endif
            <form method='GET' id = 'mainForm'>
                <div class = 'form-group'>
                    {{-- Label is only for screen readers --}}
                    <label class='sr-only' for='cityOne'>City One</label>
                    {{-- "Magic" label is controlled w/ JS --}}
                    <div class='magicLabel' id='magicOne'></div>
                    <input type = 'text' class = 'form-control' name = 'cityOne' id ='cityOne' value='{{ $cityOne ?: old("cityOne") }}' pattern='[A-Za-z]{2}.*' required>
                    <small id='cityOneDetail' class='form-text text-muted'>Enter a city you like (required)</small>
                </div>
                <br>
                <div class = 'form-group'>
                    {{-- Label is only for screen readers --}}
                    <label class='sr-only' for='cityTwo'>City Two</label>
                    {{-- "Magic" label is controlled w/ JS --}}
                    <div class='magicLabel' id='magicTwo'></div>
                    <input type = 'text' class = 'form-control' name = 'cityTwo' id ='cityTwo' value='{{ $cityTwo ?: old("cityTwo") }}'>
                    <small id='cityTwoDetail' class='form-text text-muted'>Enter a second city you like (optional)</small>
                </div>
                <br>
                <div class = 'form-group'>
                    {{-- Label is only for screen readers --}}
                    <label class='sr-only' for='cityThree'>City Three</label>
                    {{-- "Magic" label is controlled w/ JS --}}
                    <div class='magicLabel' id='magicThree'></div>
                    <input type = 'text' class = 'form-control' name = 'cityThree' id ='cityThree' value='{{ $cityThree ?: old("cityThree") }}'>
                    <small id='cityThreeDetail' class='form-text text-muted'>Enter a third city you like (optional)</small>
                </div>
                <br><br>
                <div class = 'form-group'>
                    {{-- "Magic" span is controlled w/ JS --}}
                    <span id = 'magicTemp'></span>
                    <label for='temperature'>Temperature:</label>
                    <input type = 'range' min=40 max=80 step=.3 id='temperature' name='temperature' value='{{ $temperature }}'>
                    <small id='temperatureDetail' class='form-text text-muted'>Enter your ideal temperature: <strong id='currentTemp'>{{ $temperature }}°  F</strong></small>
                </div>
                <br><br>
                <div class='form-group'>
                    <label for = 'purpose'>Reason for Visit: </label>
                    <select class='form-control purposeInput' id='purpose' name='purpose'>
                        {{-- below option with several ternaries is for the Close Trip option --}}
                        <option value='Close Trip' id='closeTripChoice'
                        {{ ($purpose == 'Close Trip' || (old('purpose') == 'Close Trip')) ? 'SELECTED' : ((!$geolocation) ? 'disabled' : '' )}}>
                        Close Trip{{ (!$geolocation) ? ' (finding location: refresh page)' : '' }}</option>
                        <option value='Vacation' {{ ($purpose == 'Vacation' || (old('purpose') == 'Vacation')) ? 'SELECTED' : '' }}>Vacation</option>
                        <option value='Permanent' {{ ($purpose == 'Permanent' || (old('purpose') == 'Permanent')) ? 'SELECTED' : '' }}>Permanent</option>
                    </select>
                </div>
                <div class='form-group' id ='closeTripOption'>
                    <label for = 'purpose'>Max Trip Distance<small>(miles)</small></label>
                    <input type = 'number' class = 'form-control purposeInput' min=50 name = 'tripDistance' id ='tripDistance' value={{($tripDistance) ?: 1200}}>
                </div>
                <br><br><br><br>
                <div class='form-group' id = 'submit'>
                    <button type='submit' id ='submitButton' class='btn btn success'><span id='buttonText'>Get City</span><span id='arrowIcon'>&rarr;</span></button>
                </div>
            </form>
        </main>

        <footer class='footer'>
            {{-- There is a footer image, but this is controlled with PHP/JS --}}
        </footer>
    </div>
@endsection


@push('body')
    {{-- Converts PHP variables to JS variables --}}
    @include('php-js-conversion')

    {{-- All scripts: external and custom --}}
    <script src='js/allScripts.js'></script>

    {{-- Google Map script, w/ API key embedded via php --}}
    <script src='{{ $googleLink }}'></script>
@endpush
