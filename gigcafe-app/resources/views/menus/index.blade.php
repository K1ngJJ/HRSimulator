<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Restaurant Simulator') }}   
        </h2>
        <h7 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @include('cart.addto')  
        </h7>
    </x-slot>

    
  

    <div class="container w-full px-5 py-6 mx-auto">
        <div class="grid lg:grid-cols-4 gap-y-6">
            @foreach ($menus as $menu)
                <div class="max-w-xs mx-4 mb-2 rounded-lg shadow-lg">
                    <img class="w-full h-48" src="{{ Storage::url($menu->image) }}" alt="Image" />
                    <div class="px-6 py-4">
                        <h4 class="mb-3 text-xl font-semibold tracking-tight text-green-600 uppercase">
                            {{ $menu->name }}</h4>
                        <p class="leading-normal text-gray-700">{{ $menu->description }}.</p>
                    </div>
                    <div class="flex items-center justify-between p-4">
                        <span class="text-xl text-green-600">₱{{ $menu->price }}</span>
                        
                        <p class="btn-holder text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500 hover:text-green-400"><a href="{{ route('addToCart', $menu->id) }}" class="btn btn-primary btn-block text-center" role="button">-><i class="fa fa-shopping-cart" aria-hidden="true"></i></a> </p> 
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
