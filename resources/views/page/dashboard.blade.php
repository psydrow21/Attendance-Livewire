@extends('template')

@section('section')

{{-- Apex Chart --}}
<div class="flex flex-wrap gap-12">

    <livewire:attendance-radial-chart>

    <livewire:attendance-pie-chart>


    <div class="max-w-sm w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6">

        <dotlottie-wc
        src="https://lottie.host/166f73d9-f6c9-403d-9348-71e46fe1defe/1UFkQd19QK.lottie"
        style="width: 200px;height: 200px"
        class="mx-auto block"
        autoplay
        loop
        ></dotlottie-wc>

        <dotlottie-wc
        src="https://lottie.host/7a906583-d8a9-4620-8488-b9c34d6452e7/NekxK6CaIa.lottie"
        style="width: 200px;height: 200px"
        class="mx-auto block"
        autoplay
        loop></dotlottie-wc>

    </div>



</div>

<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>

@endsection
