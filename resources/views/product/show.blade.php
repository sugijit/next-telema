<x-app-layout>
    {{-- 表示モーダル --}}
    <style>
        .modal-check-show:checked, .modal-check-show:focus {
            background-color: #059e52;
            border-color: #059e52;
        }
        .modal-check-hide:checked, .modal-check-hide:focus {
            background-color: #f63b57;
            border-color: #f63b57;
        }
    </style>
    <div id="settingsModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-[600000]" onclick="closeModal(event)"> <!-- hidden nemeh-->
        <div class="bg-white p-6 rounded-xl shadow-lg relative" onclick="event.stopPropagation()">
            <button onclick="closeModal()" class="text-xl absolute top-3 right-6 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 class="text-sm font-bold mt-3 !mb-6 border-b pb-3 text-gray-600">{{$current_list['product_name']}}</h2>
            <form id="settingsForm" action="{{ route('product.canView') }}" method="POST">
                @csrf
                <div class="grid grid-cols-3 gap-x-16">
                    <input type="text" value={{$id}} class="hidden" name="product_id">
                    @foreach($hard_header as $key => $head)
                        <div class="mb-1 flex text-xs">
                            <label class="block text-sm font-medium min-w-[120px]">{{ $head }}</label>
                            <div class="flex modal">
                                <input type="radio" name="{{ $key }}" value="1" class="modal-check-show mr-2 transform scale-[80%]" {{ isset($view_settings[$key]) && $view_settings[$key] == 1 ? 'checked' : ''}}>
                                <input type="radio" name="{{ $key }}" value="0" class="modal-check-hide mr-2 transform scale-[80%]" {{ isset($view_settings[$key]) && $view_settings[$key] == 0 ? 'checked' : ''}}>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center text-sm mt-8">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">保存</button>
                    <button type="button" onclick="closeModal()" class="ml-2 bg-gray-300 py-2 px-4 rounded">キャンセル</button>
                </div>
            </form>
        </div>
    </div>
    {{-- フィールドモーダル --}}
    <div id="settingsFieldModal" class="hidden p-6 fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-[600000]" onclick="closeFieldModal(event)">
        <div class="bg-white min-w-[500px] p-6 rounded-xl shadow-lg relative" onclick="event.stopPropagation()">
            <button onclick="closeFieldModal()" class="text-xl absolute top-3 right-6 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 class="text-sm font-bold mt-3 !mb-3 border-b pb-3 text-gray-600">{{$current_list['product_name']}}のメモ用のフィールド追加</h2>
            <form id="settingsFieldForm" action="{{ route('product.addField') }}" method="POST">
                @csrf
                <input type="text" value={{$id}} class="hidden" name="product_id">
                <div>
                    <button type="button" onclick="addField()"><i class="fa-solid mb-4 fa-circle-plus text-xl text-green-500"></i></button>
                    <div id="field-container">
                        <!-- フィールドが追加される場所 -->
                    </div>
                </div>
                <div class="text-center text-sm mt-8">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">保存</button>
                    <button type="button" onclick="closeFieldModal()" class="ml-2 bg-gray-300 py-2 px-4 rounded">キャンセル</button>
                </div>
            </form>
        </div>
    </div>


    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('リスト一覧') }}
        </h2>
    </x-slot> --}}

    <div class="py-3">
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

                <div class="p-6 !pt-0 bg-white border-b border-gray-200">
                    <div class="mb-4 flex justify-end gap-3">
                        {{-- <a href="#" class="text-sm bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            リスト追加
                        </a> --}}
                        <button id="settings_button" onclick="openModal()"><i class="text-sm fa-solid fa-gear text-white bg-blue-500 hover:bg-blue-700 py-2 px-4 rounded">　表示設定</i></button>
                        <button id="field_button" onclick="openFieldModal()"><i class="text-sm fa-solid fa-gear text-white bg-blue-500 hover:bg-blue-700 py-2 px-4 rounded">　メモフィールド設定</i></button>
                    </div>

                    <div class="overflow-scroll !h-[650px]">
                        <table class="min-w-full divide-y divide-gray-200 ">
                            <thead class="bg-blue-100 sticky top-0">
                                <tr>
                                    @foreach($header as $head)
                                        {{-- 幅小さいもの　そのまま --}}
                                        @if($head == "id" || $head == "ids" || $head == "server_color")
                                            <th class="px-3 py-3 !w-full text-left text-xs font-medium text-gray-500 uppercase" ondblclick="resizeTable()">{{$head}}</th>
                                        {{-- ちょっと広く見せたい --}}
                                        @else  
                                            <th class="px-3 py-3 w-full text-left text-xs font-medium text-gray-500 uppercase min-w-24" ondblclick="resizeTable()">{{$head}}</th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($list_items as $rowIndex => $list_item)
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        @foreach ($list_item as $colIndex => $value)
                                        @if (Str::startsWith($colIndex, "telema"))
                                            <td class="px-3 py-2 whitespace-nowrap bg-green-50 text-xs">
                                        @else
                                        <td class="px-3 py-2 whitespace-nowrap text-xs">
                                        @endif
                                                @if (Str::startsWith($colIndex, "telema"))
                                                    <div id="editable-text-{{ $rowIndex }}-{{ $colIndex }}" class="editable p-0" onclick="makeEditable({{ $rowIndex }}, '{{ $colIndex }}')">
                                                        {{ $value == null ? '-' : $value }}
                                                    </div>
                                                @else
                                                    <div>
                                                        @php
                                                            $phoneRegex = '/^(\d{2,4}-\d{2,4}-\d{4}|\d{10,11})$/';
                                                            $isValidPhoneNumber = preg_match($phoneRegex, $value);
                                                        @endphp

                                                        @if ($isValidPhoneNumber)
                                                            <a href="tel:{{$value}}">{{ $value == null ? '-' : $value }}</a> 
                                                        @else
                                                            {{ $value == null ? '-' : $value }}
                                                        @endif
                                                       
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
<script src="{{asset('/js/modals.js')}}"></script>
</x-app-layout>