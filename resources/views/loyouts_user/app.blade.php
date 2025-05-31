<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   

</head>

<body>

    @include('loyouts_user.header')
    {{-- @section('sidbar')
        Helloe
    @show --}}
    <main class="">
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle with Popper -->

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ដាក់នេះនៅផ្នែក <head> ឬមុនស្គ្រីបផ្ទាល់ខ្លួនរបស់អ្នក -->

</body>

</html>
