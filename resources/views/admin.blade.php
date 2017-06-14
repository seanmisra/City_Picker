@extends('layouts.master')

@push('head')
    {{-- Overrides to all.css --}}
    <link rel='stylesheet' href='css/styleAdmin.css'>             
@endpush 

@section('content') 
    <header class = 'jumbotron'>
        <h1>Admin Panel</h1>
        <p>A Bare-Bones Admin Panel: Compile/Add/Edit</p>
    </header>

    @if(count($errors) > 0)
        <div class='alert alert-danger'>
            <h2>Oops...</h2>
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @elseif($_POST) 
        <div class='alert alert-success'>
            {!! $successMessage !!}
        </div>
    @endif    

    <form method='POST'>
        {{ csrf_field() }}
        <label for = 'option' id='task'>Task</label>
        <select class='form-control' id='option' name='option'>
            <option value='Compile' {{ ($option == 'Compile' || (old('option') == 'Compile')) ? 'SELECTED' : '' }}>Compile Data</option>
            <option value='City' {{ ($option == 'City' || (old('option') == 'City')) ? 'SELECTED' : '' }}>Add New City</option>
            <option value='Image' {{ ($option == 'Image' || (old('option') == 'Image')) ? 'SELECTED' : '' }}>Change Image Opacity</option>
        </select>
        <br>

        {{-- Compile Data --}}
        <div class='adminOption compileData'>
            <button type='submit'>COMPILE NOW!</button>
            <br><br><br>
        </div>

        {{-- Add New City --}}
        <div class='adminOption newCity'>
            <input type = 'text' name = 'inputCityName' placeholder='City Name  (Required)' value='{{old("inputCityName")}}'>
            <br>
            <input type = 'text' name = 'trait1V' placeholder='Trait 1V  (Required)' value='{{old("trait1V")}}'> 
            <br>
            <input type = 'text' name = 'trait2V' placeholder='Trait 2V' value='{{old("trait2V")}}'> 
            <br>
            <input type = 'text' name = 'trait3V' placeholder='Trait 3V' value='{{old("trait3V")}}'>  
            <br>
            <input type = 'text' name = 'trait4V' placeholder='Trait 4V' value='{{old("trait4V")}}'> 
            <br>
            <input type = 'text' name = 'trait5V' placeholder='Trait 5V' value='{{old("trait5V")}}'>  
            <br>
            <input type = 'text' name = 'trait6V' placeholder='Trait 6V' value='{{old("trait6V")}}'>  
            <br>
            <input type = 'text' name = 'trait7V' placeholder='Trait 7V' value='{{old("trait7V")}}'>  
            <br>
            <input type = 'text' name = 'trait8V' placeholder='Trait 8V' value='{{old("trait8V")}}'> 
            <br>
            <input type = 'text' name = 'trait9V' placeholder='Trait 9V' value='{{old("trait9V")}}'> 
            <br>
            <input type = 'text' name = 'trait10V' placeholder='Trait 10V' value='{{old("trait10V")}}'>  
            <br>
            <input type = 'text' name = 'trait1P' placeholder='Trait 1P' value='{{old("trait1P")}}'> 
            <br>
            <input type = 'text' name = 'trait2P' placeholder='Trait 2P' value='{{old("trait2P")}}'> 
            <br>
            <input type = 'text' name = 'trait3P' placeholder='Trait 3P' value='{{old("trait3P")}}'>  
            <br>
            <input type = 'text' name = 'trait4P' placeholder='Trait 4P' value='{{old("trait4P")}}'>  
            <br>
            <input type = 'text' name = 'trait5P' placeholder='Trait 5P' value='{{old("trait5P")}}'> 
            <br>
            <input type = 'text' name = 'trait6P' placeholder='Trait 6P' value='{{old("trait6P")}}'>  
            <br>
            <input type = 'text' name = 'inputTemperature' placeholder='Temperature  (Required)' value='{{old("inputTemperature")}}'> 
            <br>
            <input type = 'text' name = 'inputLatitude' placeholder='Latitude  (Required)' value='{{old("inputLatitude")}}'> 
            <br>
            <input type = 'text' name = 'inputLongitude' placeholder='Longitude  (Required)' value='{{old("inputLongitude")}}'> 
            <br><br><br>
            <button type='submit'>Generate JSON</button>
            <br><br><br>
        </div>

        {{-- Adjust Image --}}
        <div class='adminOption imageOpacity'>
            <input type = 'text' name = 'imageFileName' placeholder='Image name (no path/no extension in name)' value='{{old("imageFileName")}}'>
            <br>
            <input type = 'text' name = 'imageFileOpacity' placeholder='Desired Opacity (0-100)' value='{{old("imageFileOpacity")}}'>
            <br><br>
            <p><strong>*Both fields are required. The image must be from the <em>public/images/</em> folder.</strong></p>
            <br>
            <button type= 'submit'>Convert Image</button>
            <br><br>
            <p>The extension must be .png. Conversion time takes about 5 minutes. For optimal results, change max_execution_time in your php.ini file to 500. You can open several windows and run several opacity scripts in unison.</p>
            <p>Will default to name.png output to sync with rest of site. &#9728;</p>
            <br><br><br><br><br>
        </div>
    </form>
@endsection

@push('body')
    {{-- All scripts: external and custom --}}
    <script src='js/allScriptsAdmin.js'></script>
@endpush
