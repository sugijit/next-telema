<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('リスト一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="mx-6 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap  text-sm font-medium text-center">
                        @foreach ($products as $product)
                        <li class="">
                            <a href="{{ route('products', $product['id']) }}" class="inline-block p-4 border-b-4 rounded-t-lg {{ ($product['id'] == $id ) ? 'text-blue-600 border-b-blue-600' : 'hover:text-gray-600 hover:border-gray-300' }}">{{ $product["product_name"] }}</a>
                        </li>
                        @endforeach
                        <li class="">
                            <a href="{{ route('products.add') }}" class="inline-block p-3 border-b-4 rounded-t-lg text-green-500 text-xl"><i class="fa-solid fa-circle-plus"></i></a>
                        </li>
                    </ul>
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 text-right">
                        <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            リスト追加
                        </a>
                    </div>
                    <div class="overflow-x-scroll">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-100">
                                <tr>
                                    @foreach($header_jp as $header)
                                        {{-- 幅小さいもの　そのまま --}}
                                        @if($header == "id" || $header == "ids" || $header == "server_color")
                                            <th class="px-3 py-3 !w-full text-left text-xs font-medium text-gray-500 uppercase">{{$header}}</th>
                                        {{-- ちょっと広く見せたい --}}
                                        @else  
                                            <th class="px-3 py-3 !w-full text-left text-xs font-medium text-gray-500 uppercase min-w-24">{{$header}}</th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($list_items as $rowIndex => $list_item)
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        @foreach ($list_item as $colIndex => $value)
                                            <td class="px-3 py-2 whitespace-nowrap text-xs">
                                                @if (Str::startsWith($colIndex, "telema"))
                                                    <div id="editable-text-{{ $rowIndex }}-{{ $colIndex }}" class="editable" onclick="makeEditable({{ $rowIndex }}, '{{ $colIndex }}')">
                                                        {{ $value == null ? '-' : $value }}
                                                    </div>
                                                @else
                                                    <div>
                                                        {{ $value == null ? '-' : $value }}
                                                    </div>
                                                @endif
                                                <input class="text-xs px-2 py-1" id="editable-input-{{ $rowIndex }}-{{ $colIndex }}" type="text" style="display:none;" value="{{ $value }}" onblur="saveChanges({{ $rowIndex }}, '{{ $colIndex }}')" />
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- {{ $list_items->links() }} --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       function makeEditable(rowIndex, colIndex) {
            console.log(rowIndex);
            console.log(colIndex);
            
            const textDiv = document.getElementById(`editable-text-${rowIndex}-${colIndex}`);
            const inputField = document.getElementById(`editable-input-${rowIndex}-${colIndex}`);
            textDiv.style.display = 'none';
            inputField.style.display = 'block';
            inputField.focus();
        }

        function saveChanges(rowIndex, colIndex) {
            const id = "{{ $id }}";
            const textDiv = document.getElementById(`editable-text-${rowIndex}-${colIndex}`);
            const inputField = document.getElementById(`editable-input-${rowIndex}-${colIndex}`);
            const newValue = inputField.value;

            // データをサーバーに送信
            fetch('/update-cell', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    rowIndex: rowIndex,
                    colIndex: colIndex,
                    value: newValue,
                    id: id,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (newValue == null || newValue == "" || newValue == " " || newValue == "　") {
                        textDiv.textContent = "-";
                    } else {
                        textDiv.textContent = newValue;
                    }
                } else {
                    alert('変更の保存に失敗しました');
                }
                textDiv.style.display = 'block';
                inputField.style.display = 'none';
                if(newValue == null || newValue == "") {
                    textDiv.value = "-";
                }

            })
            .catch(error => {
                console.error('Error:', error);
                alert('エラーが発生しました');
                textDiv.style.display = 'block';
                inputField.style.display = 'none';
            });
        }
    </script>
</x-app-layout>