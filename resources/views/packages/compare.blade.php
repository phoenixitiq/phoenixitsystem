@extends('layouts.app')

@section('content')
<div class="compare-section py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-8">مقارنة الباقات</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="p-4 border-b"></th>
                        @foreach($packages as $package)
                        <th class="p-4 border-b text-center">
                            <h3 class="text-xl font-bold">{{ $package->name }}</h3>
                            <div class="text-2xl font-bold mt-2">
                                {{ number_format($package->price) }} ريال
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-4 border-b">عدد المنشورات الشهرية</td>
                        @foreach($packages as $package)
                        <td class="p-4 border-b text-center">{{ $package->posts_per_month }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="p-4 border-b">المنصات المدعومة</td>
                        @foreach($packages as $package)
                        <td class="p-4 border-b text-center">
                            @foreach($package->platforms as $platform)
                            <i class="fab fa-{{ $platform }} mx-1"></i>
                            @endforeach
                        </td>
                        @endforeach
                    </tr>
                    <!-- المزيد من المقارنات -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 