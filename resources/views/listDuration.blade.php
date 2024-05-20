<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table>
                @php($c=1)
                @foreach ($deals as $deal)
                <tr>
                    <td>{{ $c++ }}</td>   
                    <td><a href="{{ route('updateDeal',$deal['id']) }}">{{ $deal['id'] }}</a></td>   
                    <td>{{ $deal['title'] }}</td>   
                  <td>{{ $deal['products'] }}</td>   
                </tr>  
                @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>