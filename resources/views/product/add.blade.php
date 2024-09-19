<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('リスト追加') }}
        </h2>
    </x-slot> --}}

    <div class="py-3">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="mx-6 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap  text-sm font-medium text-center">
                        @foreach ($products as $product)
                        <li class="">
                            <a href="{{ route('products', $product['id']) }}" class="inline-block p-4 border-b-4 rounded-t-lg">{{ $product["product_name"] }}</a>
                        </li>
                        @endforeach
                        <li class="">
                            <a href="{{ route('products.add') }}" class="inline-block p-3 border-b-4 border-b-green-500 rounded-t-lg text-green-500 text-xl"><i class="fa-solid fa-circle-plus"></i></a>
                        </li>
                    </ul>
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 text-left max-w-[500px] mx-auto">
                        <form action="{{ route('products.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf


                            <div class="my-8">
                                <label for="list_select" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">リストを選択</label>
                                <select name="list_select" required id="list_select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">リストを選択してください</option>
                                    @foreach($lists as $list)
                                        <option value="{{ $list['id'] }}">{{ $list['list_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>




                            <label for="csv_file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">リストファイル選択（.csvのみ）</label>
                            <div><input type="file" name="csv_file" accept=".csv" required></div>
                            <div class="mt-8">
                                <label for="product_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">リスト名を付ける</label>
                                <input type="text" name="product_name" placeholder="例：ソフトバンク" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                            </div>
                            <div class="mt-8">
                                <label for="table_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    リスト名を付ける<span>（英語）</span>
                                    <div class="relative inline-block ml-2 align-middle">
                                        <i class="fas fa-info-circle text-blue-500 cursor-pointer" 
                                           onmouseover="showTooltip()" onmouseout="hideTooltip()"></i>
                                        <div id="tooltip" class="absolute bottom-full left-1/2 transform leading-6 mb-2 w-80 p-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 transition-opacity duration-300 ease-in-out pointer-events-none">
                                            <li>短く</li>
                                            <li>分かりやすく</li>
                                            <li>2つ以上の言葉になる場合「 _ 」をつけて続ける<br><span>　 例：next_link</span></li>
                                            <li>小文字</li>
                                        </div>
                                    </div>
                                </label>
                                <input type="text" id="table_name" name="table_name" placeholder="例：softbank" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                required pattern="[a-z0-9_]+" title="小文字の英数字とアンダースコアのみを使用してください。" />
                            </div>
                            <div class="text-right">
                                <button type="submit" class="mt-12 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    CSVアップロード
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="{{asset('/js/tooltip.js')}}"></script>
</x-app-layout>