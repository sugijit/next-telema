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

    {{-- hide and seek --}}
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
    <div id="settingsFieldModalAdd" class="hidden p-6 fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-[600000]" onclick="closeFieldModalAdd(event)">
        <div class="bg-white min-w-[500px] max-h-[80%] overflow-y-scroll p-6 rounded-xl shadow-lg relative" onclick="event.stopPropagation()">
            <button onclick="closeFieldModalAdd()" class="text-xl absolute top-3 right-6 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 class="text-sm font-bold mt-3 !mb-3 border-b pb-3 text-gray-600">{{$current_list['product_name']}}のメモ用のフィールド追加</h2>
            <form id="settingsFieldForm" action="{{ route('product.addField') }}" method="POST">
                @csrf
                <input type="text" value={{$id}} class="hidden" name="product_id">
                <div>
                    <button type="button" onclick="addField()"><i class="fa-solid mb-4 fa-circle-plus text-xl text-green-500"></i></button>
                    <div id="field-container1">
                        @php $currentFieldIndex = 1; @endphp
                        @foreach ($fields as $field) 
                            @foreach ($field as $key => $value) 
                                @if (strpos($key, 'field_type') !== false) 
                                    <div class="flex align-center gap-3 mb-2 opacity-20" id="field-{{ $currentFieldIndex }}">
                                        <p class="pt-1">{{ $currentFieldIndex }}</p>
                                        <input class="text-xs rounded-md placeholder:text-[0.6rem]" type="text" name="field_name_{{ $currentFieldIndex }}" placeholder="(英字) 例：result" value="{{ $field['field_name_'.$currentFieldIndex] }}" readonly>
                                        <input class="text-xs rounded-md" type="text" name="field_value_{{ $currentFieldIndex }}" placeholder="例：結果" value="{{ $field['field_value_'.$currentFieldIndex] }}" readonly>
                                        <select class="text-xs rounded-md" name="field_type_{{ $currentFieldIndex }}" onchange="toggleOptions({{ $currentFieldIndex }}, this)" readonly style="pointer-events: none;">
                                            <option value="text" {{ $value === 'text' ? 'selected' : '' }}>テキスト式</option readonly>
                                            <option value="select" {{ $value === 'select' ? 'selected' : '' }}>選択式</option readonly>
                                        </select>
                                        <div id="options-container1-{{ $currentFieldIndex }}" class="{{ $value === 'select' ? '' : 'hidden' }}">
                                            <input class="text-xs rounded-md" type="text" name="options_{{ $currentFieldIndex }}" placeholder="選択肢 (カンマで区切る)" value="{{ $field['options_'.$currentFieldIndex] }}" readonly>
                                        </div>
                                    </div>
                                    @php $currentFieldIndex++; @endphp
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
                <div class="text-center text-sm mt-8">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">保存</button>
                    <button type="button" onclick="closeFieldModalAdd()" class="ml-2 bg-gray-300 py-2 px-4 rounded">キャンセル</button>
                </div>
            </form>
        </div>
    </div>
    {{-- フィールドモーダル  DELETE --}}
    <div id="settingsFieldModalDelete" class="hidden p-6 fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-[600000]" onclick="closeFieldModalDelete(event)">
        <div class="bg-white min-w-[500px] max-h-[80%] overflow-y-scroll p-6 rounded-xl shadow-lg relative" onclick="event.stopPropagation()">
            <button onclick="closeFieldModalDelete()" class="text-xl absolute top-3 right-6 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 class="text-sm font-bold mt-3 !mb-3 border-b pb-3 text-gray-600">{{$current_list['product_name']}}のメモ用のフィールド削除</h2>
            <form id="settingsFieldForm" method="POST">
                @csrf
                <input type="text" value={{$id}} class="hidden" name="product_id">
                <div>
                    <div id="field-container2">
                        @php $currentFieldIndex = 1; @endphp
                        @foreach ($fields as $field) 
                            @foreach ($field as $key => $value) 
                                @if (strpos($key, 'field_type') !== false) 
                                    <div class="flex align-center gap-3 mb-2" id="field-{{ $currentFieldIndex }}">
                                        <button type="button" onclick="removeField({{ $currentFieldIndex }})" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                                        <p class="pt-1 opacity-40">{{ $currentFieldIndex }}</p>
                                        <input class="text-xs rounded-md placeholder:text-[0.6rem] opacity-40" type="text" name="field_name_{{ $currentFieldIndex }}" placeholder="(英字) 例：result" value="{{ $field['field_name_'.$currentFieldIndex] }}" disabled>
                                        <input class="text-xs rounded-md opacity-40" type="text" name="field_value_{{ $currentFieldIndex }}" placeholder="例：結果" value="{{ $field['field_value_'.$currentFieldIndex] }}"  disabled>
                                        <select class="text-xs rounded-md opacity-40" name="field_type_{{ $currentFieldIndex }}" onchange="toggleOptions({{ $currentFieldIndex }}, this)"  disabled>
                                            <option value="text" {{ $value === 'text' ? 'selected' : '' }}>テキスト式</option>
                                            <option value="select" {{ $value === 'select' ? 'selected' : '' }}>選択式</option>
                                        </select>
                                        <div id="options-container2-{{ $currentFieldIndex }}" class="{{ $value === 'select' ? '' : 'hidden' }} opacity-40">
                                            <input class="text-xs rounded-md" type="text" name="options_{{ $currentFieldIndex }}" placeholder="選択肢 (カンマで区切る)" value="{{ $field['options_'.$currentFieldIndex] }}"  disabled>
                                        </div>
                                    </div>
                                    @php $currentFieldIndex++; @endphp
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="py-3">
        <div class="w-full mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="mx-6 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap text-sm font-medium text-center">
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
                        <button id="settings_button" onclick="openModal()"><i class="text-sm fa-solid fa-gear text-white bg-blue-500 hover:bg-blue-700 py-2 px-4 rounded">　表示設定</i></button>
                        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white bg-blue-500 hover:bg-blue-700 rounded text-sm px-4 py-2 text-center inline-flex items-center" type="button"><strong>フィールド設定　 </strong><i class="ml-2 fa-solid fa-circle-chevron-down"></i>
                        </button>
                        <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                <li>
                                    <a id="field_add" href="#" onclick="openFieldModalAdd()" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">追加</a>
                                </li>
                                <li>
                                    <a id="field_update" href="#" onclick="openFieldModal()" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">変更</a>
                                </li>
                                <li>
                                    <a id="field_delete" href="#" onclick="openFieldModalDelete()" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">削除</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="overflow-scroll !h-[650px]">
                        <table class="min-w-full divide-y divide-gray-200 ">
                            <thead class="bg-blue-100 sticky top-0">
                                <tr>
                                    @foreach($header as $head)
                                        @if($head == "id" || $head == "ids" || $head == "server_color")
                                            <th class="px-3 py-3 !w-full text-left text-xs font-medium text-gray-500 uppercase" ondblclick="resizeTable()">{{$head}}</th>
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    function makeEditable(rowIndex, colIndex) {
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
                textDiv.textContent = newValue || "-";
            } else {
                alert('変更の保存に失敗しました');
            }
            textDiv.style.display = 'block';
            inputField.style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('エラーが発生しました');
            textDiv.style.display = 'block';
            inputField.style.display = 'none';
        });
    }

    function removeField(fieldId) {
        const productId = "{{ $id }}";
        const fieldElement = document.getElementById(`field-${fieldId}`);
        if (fieldElement) {
            const fieldName = fieldElement.querySelector('input[name^="field_name_"]').value;
            fetch(`/products/${productId}/delete-field`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ field_name: fieldName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fieldElement.remove();
                } else {
                    alert('Field deletion failed.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the field.');
            });
        }
        window.location.reload();
    }
</script>
<script src="{{asset('/js/modals.js')}}"></script>
</x-app-layout>