<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Server Error') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-white shadow-sm sm:rounded-lg p-10">
                <img src="{{ asset('images/error_500.png') }}" class="mx-auto w-48 mb-6 bg-white"  alt="error_500"/>
                <h1 class="text-3xl font-bold text-gray-800">Error 500 - Server Error</h1>
                <p class="text-gray-600 mt-4">
                    Oops! Something went wrong on our side. Please try again later or return to the homepage.
                </p>

                <div class="mt-6 flex justify-center gap-4">
                    <a href="{{ url('/home') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Go to Homepage
                    </a>
                    <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Try Again
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
