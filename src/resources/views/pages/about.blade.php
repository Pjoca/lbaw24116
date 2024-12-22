@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="w-full max-w-2xl bg-gradient-to-r from-white to-blue-50 border-2 border-teal-400 rounded-lg p-8 shadow-lg mx-auto">
        <!-- About Header -->
        <div class="flex justify-center items-center w-20 h-20 bg-white rounded-full mx-auto -mt-16 shadow-lg border-4 border-white">
            <img src="{{ asset('images/LOGO_CONNECT_FEUP.png') }}" alt="Logo" class="w-full h-full object-cover rounded-full">
        </div>

        <div class="text-center mt-6">
            <h1 class="text-2xl font-bold text-teal-500 mb-4">About Us</h1>
            <p class="text-gray-600 mb-6">
                Welcome to Connect Feup! We are a passionate team of students dedicated to creating a platform 
                that connects everyone of FEUP, fosters strong bonds, and empowers the community to achieve more together.
            </p>
        </div>

        <!-- Mission Section -->
        <div class="text-left mb-6">
            <h2 class="text-xl font-semibold text-teal-500 mb-2">Our Mission</h2>
            <p class="text-gray-600">
                Our mission is to provide an inclusive, user-friendly space that enhances communication and collaboration 
                across our college, making life simpler and more connected for everyone.
            </p>
        </div>

        <!-- Values Section -->
        <div class="text-left mb-6">
            <h2 class="text-xl font-semibold text-teal-500 mb-2">Our Values</h2>
            <ul class="list-disc list-inside text-gray-600">
                <li>Innovation: Continuously striving to improve and innovate.</li>
                <li>Community: Building stronger relationships and partnerships.</li>
                <li>Integrity: Operating with transparency and trust.</li>
            </ul>
        </div>

        <!-- Team Section -->
        <div class="text-left mb-6">
            <h2 class="text-xl font-semibold text-teal-500 mb-2">Our Team</h2>
            <p class="text-gray-600">
                Behind Connect Feup! is a team of dedicated individuals with diverse skills and 
                a shared passion for technology, creativity, and social good. Together, we work tirelessly to bring our 
                vision to life.
            </p>
        </div>

        <!-- Contact Us Section -->
        <div class="text-left mt-8">
            <h2 class="text-xl font-semibold text-teal-500 mb-2">Contact Us</h2>
            <p class="text-gray-600 mb-2">
                Have questions, feedback, or want to collaborate with us? Feel free to reach out!
            </p>
            <p class="text-gray-600">
                Email us at:
                <ul class="list-disc list-inside ml-4">
                    <li>Rui Borges - <a href="mailto:202207475@up.pt" class="text-teal-500 hover:underline">202207475@up.pt</a></li>
                    <li>Tiago Torrado - <a href="up202206399@up.pt" class="text-teal-500 hover:underline">202206399@up.pt</a></li>
                    <li>Pedro Paixão - <a href="up202008467@up.pt" class="text-teal-500 hover:underline">202008467@up.pt</a></li>
                    <li>Alexandre Chouzende - <a href="up202207429@up.pt" class="text-teal-500 hover:underline">202207429@up.pt</a></li>
                </ul>
            </p>
        </div>    

    </div>
</div>
@endsection
