<!doctype html> 
<html lang='en'>
    <head>
        {{-- Metadata --}}
        <title>City Picker</title>
        <meta charset='UTF-8'>
        <meta name = 'description' content='Find a city to travel or live in'>
        <meta name ='author' content='Sean Misra'>
        <meta name='keywords' content='Cities, World, Harvard Extension, Laravel, DWA15, Sean Misra'>
        
        {{-- Global CSS --}}
        <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>
        <link rel='stylesheet' href='css/all.css'>     
        
        {{-- Any additional CSS dependencies should go here --}}
        @stack('head')
    </head>

    <body>
        {{-- All body content should go here --}}
        @yield('content')
        
        {{-- Any additional JS dependencies should go here --}}
        @stack('body')
    </body>
</html> 