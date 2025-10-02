@extends('layouts.app')


@section('content')

<div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden transition-colors duration-500">
  
  <!-- HEADER -->
  <div class="bg-blue-600 dark:bg-blue-800 text-white px-4 py-3">
    <h1 class="text-2xl font-bold text-center">Work Order</h1>
  </div>

  <!-- MENU -->
  <nav class="flex flex-col p-4 space-y-3">
    <a href="{{ route('workorder.cisterns') }}" class="block bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-600 text-blue-700 dark:text-gray-200 font-semibold py-2 px-4 rounded-lg text-center transition">
      Cisterns
    </a>
    <a href="{{ route('workorder.drybox') }}" class="block bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-600 text-blue-700 dark:text-gray-200 font-semibold py-2 px-4 rounded-lg text-center transition">
      Dry Box
    </a>
    <a href="{{ route('workorder.platform') }}" class="block bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-600 text-blue-700 dark:text-gray-200 font-semibold py-2 px-4 rounded-lg text-center transition">
      Platform
    </a>
    <a href="{{ route('workorder.pneumatic') }}" class="block bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-600 text-blue-700 dark:text-gray-200 font-semibold py-2 px-4 rounded-lg text-center transition">
      Pneumatic
    </a>
  </nav>

  <!-- FOOTER con imagen -->
  <div class="p-4">
    <a href="https://via.placeholder.com/400x200.png?text=Work+Order+Image" target="_blank">
      <img src="https://via.placeholder.com/400x200.png?text=Work+Order+Image" 
           alt="Work Order Illustration" 
           class="rounded-lg shadow-md w-full object-cover">
    </a>
  </div>
</div>

@endsection