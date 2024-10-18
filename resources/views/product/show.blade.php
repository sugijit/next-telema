<x-app-layout>
    {{-- 表示モーダル --}}
    <style>
        .modal-check-show:checked,
        .modal-check-show:focus {
            background-color: #059e52;
            border-color: #059e52;
        }

        .modal-check-hide:checked,
        .modal-check-hide:focus {
            background-color: #f63b57;
            border-color: #f63b57;
        }
        .highlighted {
            background-color: rgb(122, 203, 231) !important;
            font-weight: bold !important;
        }

        /* SWITCH */
            .switch {
            /* switch */
            --switch-width: 46px;
            --switch-height: 20px;
            --switch-bg: rgb(131, 131, 131);
            --switch-checked-bg: rgb(0, 218, 80);
            --switch-offset: calc((var(--switch-height) - var(--circle-diameter)) / 2);
            --switch-transition: all .2s cubic-bezier(0.27, 0.2, 0.25, 1.51);
            /* circle */
            --circle-diameter: 18px;
            --circle-bg: #fff;
            --circle-shadow: 1px 1px 2px rgba(146, 146, 146, 0.45);
            --circle-checked-shadow: -1px 1px 2px rgba(163, 163, 163, 0.45);
            --circle-transition: var(--switch-transition);
            /* icon */
            --icon-transition: all .2s cubic-bezier(0.27, 0.2, 0.25, 1.51);
            --icon-cross-color: var(--switch-bg);
            --icon-cross-size: 6px;
            --icon-checkmark-color: var(--switch-checked-bg);
            --icon-checkmark-size: 10px;
            /* effect line */
            --effect-width: calc(var(--circle-diameter) / 2);
            --effect-height: calc(var(--effect-width) / 2 - 1px);
            --effect-bg: var(--circle-bg);
            --effect-border-radius: 1px;
            --effect-transition: all .2s ease-in-out;
            }

            .switch input {
            display: none;
            }

            .switch {
            display: inline-block;
            }

            .switch svg {
            -webkit-transition: var(--icon-transition);
            -o-transition: var(--icon-transition);
            transition: var(--icon-transition);
            position: absolute;
            height: auto;
            }

            .switch .checkmark {
            width: var(--icon-checkmark-size);
            color: var(--icon-checkmark-color);
            -webkit-transform: scale(0);
            -ms-transform: scale(0);
            transform: scale(0);
            }

            .switch .cross {
            width: var(--icon-cross-size);
            color: var(--icon-cross-color);
            }

            .slider {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            width: var(--switch-width);
            height: var(--switch-height);
            background: var(--switch-bg);
            border-radius: 999px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            position: relative;
            -webkit-transition: var(--switch-transition);
            -o-transition: var(--switch-transition);
            transition: var(--switch-transition);
            cursor: pointer;
            }

            .circle {
            width: var(--circle-diameter);
            height: var(--circle-diameter);
            background: var(--circle-bg);
            border-radius: inherit;
            -webkit-box-shadow: var(--circle-shadow);
            box-shadow: var(--circle-shadow);
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-transition: var(--circle-transition);
            -o-transition: var(--circle-transition);
            transition: var(--circle-transition);
            z-index: 1;
            position: absolute;
            left: var(--switch-offset);
            }

            .slider::before {
            content: "";
            position: absolute;
            width: var(--effect-width);
            height: var(--effect-height);
            left: calc(var(--switch-offset) + (var(--effect-width) / 2));
            background: var(--effect-bg);
            border-radius: var(--effect-border-radius);
            -webkit-transition: var(--effect-transition);
            -o-transition: var(--effect-transition);
            transition: var(--effect-transition);
            }

            /* actions */

            .switch input:checked+.slider {
            background: var(--switch-checked-bg);
            }

            .switch input:checked+.slider .checkmark {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
            }

            .switch input:checked+.slider .cross {
            -webkit-transform: scale(0);
            -ms-transform: scale(0);
            transform: scale(0);
            }

            .switch input:checked+.slider::before {
            left: calc(100% - var(--effect-width) - (var(--effect-width) / 2) - var(--switch-offset));
            }

            .switch input:checked+.slider .circle {
            left: calc(100% - var(--circle-diameter) - var(--switch-offset));
            -webkit-box-shadow: var(--circle-checked-shadow);
            box-shadow: var(--circle-checked-shadow);
            }
        /* SWITCH END*/
    </style>

    {{-- hide and seek --}}
    <div id="settingsModal"
        class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-[600000]"
        onclick="closeModal(event)"> <!-- hidden nemeh-->
        <div class="bg-white p-6 rounded-xl shadow-lg relative" onclick="event.stopPropagation()">
            <button onclick="closeModal()" class="text-xl absolute top-3 right-6 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 class="text-sm font-bold mt-3 !mb-6 border-b pb-3 text-gray-600">{{ $current_list['product_name'] }}</h2>
            <form id="settingsForm" action="{{ route('product.canView') }}" method="POST">
                @csrf
                <div class="grid grid-cols-3 gap-x-16">
                    <input type="text" value={{ $id }} class="hidden" name="product_id">
                    @foreach ($hard_header as $key => $head)
                        @if($head !== "id") 
                            <div class="mb-1 flex text-xs">
                                <label class="block text-sm font-medium min-w-[120px]">{{ $head }}</label>
                                <div class="flex modal">
                                    {{-- switch --}}
                                    <label class="switch">
                                        <input type="hidden" name="{{ $key }}" value="0">
                                        <input name="{{ $key }}" type="checkbox" value="1" {{ isset($view_settings[$key]) && $view_settings[$key] == 1 ? 'checked' : '' }} 
                                        onchange="this.previousElementSibling.value = this.checked ? 1 : 0;">
                                        <div class="slider">
                                            <div class="circle">
                                                <svg class="cross" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 365.696 365.696" y="0" x="0" height="6" width="6" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                    <g>
                                                        <path data-original="#000000" fill="currentColor" d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0"></path>
                                                    </g>
                                                </svg>
                                                <svg class="checkmark" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 24 24" y="0" x="0" height="10" width="10" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                    <g>
                                                        <path class="" data-original="#000000" fill="currentColor" d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"></path>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                    </label>
                                    {{-- switch end --}}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="text-center text-sm mt-8">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">保存</button>
                    <button type="button" onclick="closeModal()"
                        class="ml-2 bg-gray-300 py-2 px-4 rounded">キャンセル</button>
                </div>
            </form>
        </div>
    </div>

    
    {{-- フィールドモーダル 変更 --}}
    <div id="settingsFieldModalUpdate"
        class="hidden p-6 fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-[600000]"
        onclick="closeFieldModalUpdate(event)">
        <div class="bg-white min-w-[500px] max-h-[80%] overflow-y-scroll p-6 rounded-xl shadow-lg relative"
            onclick="event.stopPropagation()">
            <button onclick="closeFieldModalUpdate()"
                class="text-xl absolute top-3 right-6 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 class="text-sm font-bold mt-3 !mb-3 border-b pb-3 text-gray-600">フィールド変更</h2>
            <form id="settingsFieldForm" action="{{ route('product.updateField') }}" method="POST">
                @csrf
                <input type="text" value={{ $id }} class="hidden" name="product_id">
                <div id="field-container">
                    @php $currentFieldIndex = 1; @endphp
                    @foreach ($fields as $field)
                        @foreach ($field as $key => $value)
                            @if (strpos($key, 'field_type') !== false)
                                <div class="flex align-center gap-3 mb-2" id="field-{{ $currentFieldIndex }}">
                                    <p class="pt-1">{{ $currentFieldIndex }}</p>
                                    <input class="text-xs rounded-md opacity-20" type="text"
                                        name="field_name_{{ $currentFieldIndex }}" placeholder="(英字) 例：result"
                                        value="{{ $field['field_name_' . $currentFieldIndex] }}" readonly>
                                    <input class="text-xs rounded-md" type="text"
                                        name="field_value_{{ $currentFieldIndex }}" placeholder="例：結果"
                                        value="{{ $field['field_value_' . $currentFieldIndex] }}">
                                    <select class="text-xs rounded-md" name="field_type_{{ $currentFieldIndex }}"
                                        onchange="toggleOptionsUpdate({{ $currentFieldIndex }}, this)">
                                        <option value="text" {{ $value === 'text' ? 'selected' : '' }}>テキスト式
                                        </option>
                                        <option value="select" {{ $value === 'select' ? 'selected' : '' }}>選択式
                                        </option>
                                        <option value="date" {{ $value === 'date' ? 'selected' : '' }}>日付
                                        </option>
                                        <option value="textarea" {{ $value === 'textarea' ? 'selected' : '' }}>テキストボックス
                                        </option>
                                    </select>
                                    <div id="options-container-{{ $currentFieldIndex }}"
                                        class="{{ $value === 'select' ? '' : 'hidden' }}">
                                        <input class="text-xs rounded-md" type="text"
                                            name="options_{{ $currentFieldIndex }}" placeholder="選択肢 (カンマで区切る)"
                                            value="{{ $field['options_' . $currentFieldIndex] }}">
                                    </div>
                                </div>
                                @php $currentFieldIndex++; @endphp
                            @endif
                        @endforeach
                    @endforeach
                </div>
                @if (!empty($fields))
                    <div class="text-center text-sm mt-8">
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">保存</button>
                        <button type="button" onclick="closeFieldModalUpdate()"
                            class="ml-2 bg-gray-300 py-2 px-4 rounded">キャンセル</button>
                    </div>
                @else
                    <p class="text-center text-gray-400">フィールドありません</p>
                @endif
            </form>
        </div>
    </div>

    {{-- 絞り込みモーダル --}}
    <div id="filterModal"
        class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-[600000]"
        onclick="closeFilterModal(event)">
        <div class="bg-white p-6 rounded-xl shadow-lg relative" onclick="event.stopPropagation()">
            <button onclick="closeFilterModal()" class="text-xl absolute top-3 right-6 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 class="text-sm font-bold mt-3 !mb-6 border-b pb-3 text-gray-600">絞り込み</h2>
            <form id="filterForm" action="{{ route('product.filter') }}" method="GET">
                <input type="text" value="{{ $id }}" class="hidden" name="product_id">
                <div class="mb-4">
                    <label for="search_keyword" class="block text-sm font-medium">キーワード <span class="text-[0.5rem]">　※曖昧検索</span></label>
                    <input type="text" name="search_keyword" id="search_keyword" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ request('search_keyword', '') }}">
                </div>
                <div class="flex gap-3">
                    <div class="mb-4">
                        <label for="date_from" class="block text-sm font-medium">更新日</label>
                        <input type="date" name="date_from" id="date_from" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ request('date_from', '') }}">
                    </div>
                    <div class="pt-2"><div>　</div><div>〜</div></div>
                    <div class="mb-4">
                        <label for="date_to" class="block text-sm font-medium">　 </label>
                        <input type="date" name="date_to" id="date_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ request('date_to', '') }}">
                    </div>
                </div>
                @if(!empty($selectFields))
                <div class="w-full md:min-w-[500px] flex flex-wrap gap-2">
                    @foreach ($selectFields as $field)
                        <div class="mb-4 flex-1 w-full md:w-1/3">
                            <label for="custom_field" class="block text-sm font-medium">
                                @foreach ($field as $key => $value)
                                    @if (strpos($key, 'field_value') !== false)
                                            {{ $value }}
                                    @endif
                                    @if (strpos($key, 'field_name') !== false)
                                            @php 
                                                $come = $value ;
                                            @endphp
                                    @endif
                                @endforeach
                            </label>
                            <select name="{{$come}}" class="w-full rounded-md border-gray-300 text-xs shadow-sm mt-1">
                                <option value="">全て</option>
                                    @if (in_array('agent',$field))
                                    @foreach ($selectUsers as $option)
                                        <option value="{{ $option['name'] }}" class="!text-xs"
                                            {{ $value == $option['name'] ? 'selected' : '' }}>
                                            {{ $option['name']}} {{ "(" . $companies[$option['company_id']-1]['name'] . ")"}}
                                        </option>
                                    @endforeach
                                    @else
                                        @foreach (explode(',', $field["{$key}"]) as $option)
                                            <option value="{{ $option }}" {{ request($come) == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    @endif
                            </select>
                        </div>
                        @if (($loop->index + 1) % 3 === 0) <!-- 3列ごとに改行 -->
                            </div><div class="w-full flex flex-wrap gap-2">
                        @endif
                    @endforeach
                </div>
                @endif
                <div class="text-center mt-4">

                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">絞り込み</button>
                    <button type="button" onclick="closeFilterModal()" class="ml-2 bg-gray-300 py-2 px-4 rounded">キャンセル</button>
                    <button type="button" onclick="resetFilters()" class="ml-2 bg-yellow-500 text-white py-2 px-4 rounded">リセット</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openFilterModal() {
            document.getElementById('filterModal').classList.remove('hidden');
        }

        function closeFilterModal(event) {
            if (event) {
                event.stopPropagation();
            }
            document.getElementById('filterModal').classList.add('hidden');
        }

        function resetFilters() {
            const productId = "{{ $id }}"; // Get the product ID
            window.location.href = `/products/${productId}`; // Redirect to the product page
        }  

    </script>



    {{-- 表示会社 変更 --}}
    <div id="settingsCompany"
    class="hidden p-6 fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-[600000]"
    onclick="closeCompanyModal(event)">
    <div class="bg-white min-w-[500px] max-h-[80%] overflow-y-scroll p-6 rounded-xl shadow-lg relative"
        onclick="event.stopPropagation()">
        <button onclick="closeCompanyModal()"
            class="text-xl absolute top-3 right-6 text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h2 class="text-sm font-bold mt-3 !mb-3 border-b pb-3 text-gray-600">リスト表示可能な代理店</h2>
        <form id="settingsFieldForm" action="{{ route('product.updateCompanies') }}" method="POST">
            @csrf
            <input type="text" value={{ $id }} class="hidden" name="product_id">
            

            <div class="flex flex-wrap -mx-2">
                @foreach($companiess as $key => $company)
                        <div class="w-1/2 px-2 mb-4 flex items-center">
                            @if($user->company_id == $company->id) 
                                <input type="checkbox" id="company{{ $key }}" name="companies[]" value="{{ $company->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" disabled checked>
                                <input type="checkbox" id="company{{ $key }}" name="companies[]" value="{{ $company->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 hidden" checked>
                            @elseif(in_array($company->id, $company_ids))
                                <input type="checkbox" id="company{{ $key }}" name="companies[]" value="{{ $company->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                            @else
                                <input type="checkbox" id="company{{ $key }}" name="companies[]" value="{{ $company->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            @endif
                            <label for="company{{ $key }}" class="ml-2 text-gray-600">{{ $company->name }}</label>
                        </div>
                @endforeach
            </div>

            @if (!empty($fields))
                <div class="text-center text-sm mt-8">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">保存</button>
                    <button type="button" onclick="closeCompanyModal()"
                        class="ml-2 bg-gray-300 py-2 px-4 rounded">キャンセル</button>
                </div>
            @else
                <p class="text-center text-gray-400">フィールドありません</p>
            @endif
        </form>
    </div>
    </div>




    {{-- フィールドモーダル  DELETE --}}
    <div id="settingsFieldModalDelete"
        class="hidden p-6 fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-[600000]"
        onclick="closeFieldModalDelete(event)">
        <div class="bg-white min-w-[500px] max-h-[80%] overflow-y-scroll p-6 rounded-xl shadow-lg relative"
            onclick="event.stopPropagation()">
            <button onclick="closeFieldModalDelete()"
                class="text-xl absolute top-3 right-6 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 class="text-sm font-bold mt-3 !mb-3 border-b pb-3 text-gray-600">
                {{ $current_list['product_name'] }}のメモ用のフィールド削除</h2>
            <form id="settingsFieldForm" method="POST">
                @csrf
                <input type="text" value={{ $id }} class="hidden" name="product_id">
                <div>
                    <div id="field-container2">
                        @php $currentFieldIndex = 1; @endphp
                        @foreach ($fields as $field)
                            @foreach ($field as $key => $value)
                                @if (strpos($key, 'field_type') !== false)
                                    <div class="flex align-center gap-3 mb-2" id="field-{{ $currentFieldIndex }}">
                                        <button type="button" onclick="removeField({{ $currentFieldIndex }})"
                                            class="text-red-500 hover:text-red-700"><i
                                                class="fa-solid fa-trash"></i></button>
                                        <p class="pt-1 opacity-40">{{ $currentFieldIndex }}</p>
                                        <input class="text-xs rounded-md placeholder:text-[0.6rem] opacity-40"
                                            type="text" name="field_name_{{ $currentFieldIndex }}"
                                            placeholder="(英字) 例：result"
                                            value="{{ $field['field_name_' . $currentFieldIndex] }}" disabled>
                                        <input class="text-xs rounded-md opacity-40" type="text"
                                            name="field_value_{{ $currentFieldIndex }}" placeholder="例：結果"
                                            value="{{ $field['field_value_' . $currentFieldIndex] }}" disabled>
                                        <select class="text-xs rounded-md opacity-40"
                                            name="field_type_{{ $currentFieldIndex }}"
                                            onchange="toggleOptions({{ $currentFieldIndex }}, this)" disabled>
                                            <option value="text" {{ $value === 'text' ? 'selected' : '' }}>テキスト式
                                            </option>
                                            <option value="select" {{ $value === 'select' ? 'selected' : '' }}>選択式
                                            </option>
                                        </select>
                                        <div id="options-container2-{{ $currentFieldIndex }}"
                                            class="{{ $value === 'select' ? '' : 'hidden' }} opacity-40">
                                            <input class="text-xs rounded-md" type="text"
                                                name="options_{{ $currentFieldIndex }}" placeholder="選択肢 (カンマで区切る)"
                                                value="{{ $field['options_' . $currentFieldIndex] }}" disabled>
                                        </div>
                                    </div>
                                    @php $currentFieldIndex++; @endphp
                                @endif
                            @endforeach
                        @endforeach
                        @if (empty($fields))
                            <p class="text-center text-gray-400">フィールドありません</p>
                        @endif
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
                                <a href="{{ route('products', $product['id']) }}"
                                    class="inline-block p-4 border-b-4 rounded-t-lg {{ $product['id'] == $id ? 'text-blue-600 border-b-blue-600' : 'hover:text-gray-600 hover:border-gray-300' }}">{{ $product['product_name'] }}</a>
                            </li>
                        @endforeach
                        @if($user->role == 'nl_admin')
                        <li class="">
                            <a href="{{ route('products.add') }}"
                                class="inline-block p-3 border-b-4 rounded-t-lg text-green-500 text-xl"><i
                                    class="fa-solid fa-circle-plus"></i></a>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="p-6 !pt-0 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">

                        <div class="flex gap-6">
                            <table class="mb-4 bg-white text-xs border-2 border-blue-200 rounded-lg">
                                <thead>
                                    <tr class="bg-blue-100 text-left text-gray-600 uppercase tracking-wider">
                                        <th class="px-4 py-1"></th>
                                        <th class="px-4 py-1">本日</th>
                                        <th class="px-4 py-1">今月</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t-2 border-blue-200">
                                        <td class="px-4 py-1 bg-blue-100">私</td>
                                        <td class="px-4 py-1">{{$got_count[$user->role]["user_today"]}}件</td>
                                        <td class="px-4 py-1">{{$got_count[$user->role]["user_this_month"]}}件</td>
                                    </tr>
                                </tbody>
                            </table>
                            @if ($user->role !== "user")
                            <table class="mb-4 bg-white text-xs border border-gray-200 rounded-lg">
                                <thead>
                                    <tr class="bg-gray-100 text-left text-gray-600 uppercase tracking-wider">
                                        <th class="px-4 py-1"></th>
                                        <th class="px-4 py-1">本日</th>
                                        <th class="px-4 py-1">今月</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t">
                                        <td class="px-4 py-1 bg-gray-100">{{$user->company->name}}</td>
                                        <td class="px-4 py-1">{{$got_count[$user->role]["agent_today"]}}件</td>
                                        <td class="px-4 py-1">{{$got_count[$user->role]["agent_this_month"]}}件</td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif
                            @if ($user->role === "nl_admin")
                            <table class="mb-4 bg-white text-xs border border-gray-200 rounded-lg">
                                <thead>
                                    <tr class="bg-gray-100 text-left text-gray-600 uppercase tracking-wider">
                                        <th class="px-4 py-1"></th>
                                        <th class="px-4 py-1">本日</th>
                                        <th class="px-4 py-1">今月</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t">
                                        <td class="px-4 py-1 bg-gray-100">全体</td>
                                        <td class="px-4 py-1">{{$got_count[$user->role]["today"]}}件</td>
                                        <td class="px-4 py-1">{{$got_count[$user->role]["this_month"]}}件</td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif
                        </div>
                        
                        
                        <div class="mb-4 flex justify-end gap-3">
                            {{-- <button onclick="downloadCSV()" ><i class="text-sm fa-solid fa-download text-white bg-green-500 hover:bg-green-700 py-2 px-4 rounded">　ダウンロード</i></button> --}}

                            <button onclick="openFilterModal()"><i class="text-sm fa-solid fa-filter text-white bg-blue-500 hover:bg-blue-700 py-2 px-4 rounded">　絞り込み</i></button>
                            <button id="settings_button" onclick="openModal()"><i
                                    class="text-sm fa-solid fa-eye text-white bg-blue-500 hover:bg-blue-700 py-2 px-4 rounded">　表示設定</i></button>
                            {{-- <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                                class="text-white bg-blue-500 hover:bg-blue-700 rounded text-sm px-4 py-2 text-center inline-flex items-center"
                                type="button"><strong>フィールド設定　 </strong><i
                                    class="ml-2 fa-solid fa-circle-chevron-down"></i>
                            </button> --}}
                            <div id="dropdown"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownDefaultButton">
                                    <li>
                                        <a id="field_update" href="#" onclick="openFieldModalUpdate()"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">変更</a>
                                    </li>
                                    <li>
                                        <a id="field_delete" href="#" onclick="openFieldModalDelete()"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">削除</a>
                                    </li>
                                </ul>
                            </div>
                            @if($user->role == 'nl_admin')
                            <button id="company_setting_button" onclick="openCompanyModal()">
                                <i class="text-sm fa-solid fa-key text-white bg-blue-500 hover:bg-blue-700 py-2 px-4 rounded">　表示企業設定</i>
                            </button>
                            <button id="delete_button" onclick="confirmDelete()">
                                <i class="text-sm fa-solid fa-trash text-white bg-red-500 hover:bg-red-700 py-2 px-4 rounded">　削除</i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <div class="overflow-scroll !h-[650px]">
                        @if($user->company_id == "1")
                            <table class="min-w-full divide-y divide-gray-200">
                        @else
                            <table class="min-w-full divide-y divide-gray-200 select-none">
                        @endif
                            <thead class="bg-blue-100 sticky top-0">
                                <tr>
                                    @foreach ($header as $head)
                                        @if ($head == 'id' || $head == 'ids' || $head == 'server_color')
                                            <th class="px-3 py-3 !w-full text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap"
                                                ondblclick="resizeTable()">{{ $head }}</th>
                                        @else
                                            <th class="px-3 py-3 w-full text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap"
                                                ondblclick="resizeTable()">{{ $head }}</th>
                                        @endif
                                    @endforeach
                                    {{-- <th class="px-3 py-3 !w-full text-left text-xs font-medium text-gray-500 uppercase">ET</th>
                                    <th class="px-3 py-3 !w-full text-left text-xs font-medium text-gray-500 uppercase">NEXTLINK</th> --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 static">
                                @foreach ($list_items as $rowIndex => $list_item)
                                    @if($list_item['telema_tel_status'] == '獲得')
                                        <tr id="record_row" class="bg-green-50 text-gray-400">
                                    @elseif ($list_item['telema_tel_status'] == '架電禁止')
                                        <tr id="record_row" class="bg-red-50 text-gray-400">
                                    @else
                                        <tr id="record_row" class="odd:bg-white even:bg-gray-50">
                                    @endif
                                        @foreach ($list_item as $colIndex => $value)
                                            @if (Str::startsWith($colIndex, 'telema'))
                                                <td class="px-3 py-0 whitespace-nowrap bg-green-50 text-xs h-2">
                                            @else
                                                <td class="px-3 py-0 whitespace-nowrap text-xs">
                                            @endif
                                            @if (!Str::startsWith($colIndex, 'telema'))
                                                <div>
                                                    @php
                                                        $phoneRegex = '/^(\d{2,4}-\d{2,4}-\d{4}|\d{10,11})$/';
                                                        $isValidPhoneNumber = preg_match($phoneRegex, $value);
                                                    @endphp

                                                    @if ($isValidPhoneNumber)
                                                        <a class="" id="editable-phone-{{ $list_item['id'] }}-{{ $colIndex }}" onclick="calledChanges({{ $list_item['id'] }}, '{{ $colIndex }}')"
                                                            href="tel:{{ $value }}" >{{ $value == null ? '-' : $value }}</a>
                                                    @else
                                                        @if (($colIndex === 'created_at' || $colIndex === 'updated_at') && $value != null)
                                                            {{ \Carbon\Carbon::parse($value)->format('Y/m/d H:i') }}
                                                        @else
                                                            {{ $value == null ? '-' : $value }}
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif
                                            @php
                                                if (Str::startsWith($colIndex, 'telema')) {
                                                    $fieldKey = str_replace('telema_', '', $colIndex);
                                                    $field = collect($fields)->firstWhere(
                                                        "field_name_{$fieldKey}",
                                                        $fieldKey,
                                                    );
                                                }
                                            @endphp
                                            @if (Str::startsWith($colIndex, 'telema'))
                                                @php
                                                    $prim = 0;
                                                @endphp
                                                @foreach ($fields as $fieldss)
                                                    @php
                                                    $prim = $prim + 1;
                                                        $fieldTypes = array_filter(
                                                            $fieldss,
                                                            function ($key) {
                                                                return strpos($key, 'field_type_') === 0;
                                                            },
                                                            ARRAY_FILTER_USE_KEY,
                                                        );
                                                    @endphp

                                                    @foreach ($fieldTypes as $key => $valuee)
                                                        @if ($fieldKey === reset($fieldss))
                                                        {{-- <?php print_r($key) ?> --}}
                                                            @if (array_key_exists($key, $fieldss))
                                                                @if ($valuee === 'select')
                                                                    <select
                                                                        id="editable-select-{{ $list_item['id'] }}-{{ $colIndex }}"
                                                                        class="!text-xs block appearance-none min-w-28 w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-1 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                                                        value="{{ $value }}"
                                                                        onchange="saveChangesSelect({{ $list_item['id'] }}, '{{ $colIndex }}')">
                                                                        @if (isset($fieldss["options_{$prim}"]))
                                                                            <option value=""></option>
                                                                            @if($key == 'field_type_2' && $user->role == 'user')
                                                                                @if($value == $user->name)
                                                                                    @foreach ([$user->name] as $option)
                                                                                    <option value="{{ $option }}" class="!text-xs"
                                                                                            {{ $value == $option ? 'selected' : $value }}>
                                                                                            {{ $option }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                @elseif(!isset($value)) 
                                                                                    @foreach ([$user->name] as $option)
                                                                                        <option value="{{ $option }}" class="!text-xs"
                                                                                            {{ $value == $option ? 'selected' : $value }}>
                                                                                            {{ $option }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                @else
                                                                                    @foreach ([$value, $user->name] as $option)
                                                                                        <option value="{{ $option }}" class="!text-xs"
                                                                                            {{ $value == $option ? 'selected' : $value }}>
                                                                                            {{ $option }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                @endif
                                                                            @elseif ($key == 'field_type_2' && $user->role == 'admin')
                                                                                @foreach ($selectUsers as $option)
                                                                                    <option value="{{ $option['name'] }}" class="!text-xs"
                                                                                        {{ $value == $option['name'] ? 'selected' : $value }}>
                                                                                        {{ $option['name'] }}
                                                                                    </option>
                                                                                @endforeach
                                                                                @if($value)
                                                                                    @foreach ([$value] as $option)
                                                                                        <option value="{{ $option }}" class="!text-xs"
                                                                                            {{ $value == $option ? 'selected' : $value }}>
                                                                                            {{ $option }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                @endif
                                                                            @elseif ($key == 'field_type_2' && $user->role == 'nl_admin')
                                                                                @foreach ($selectUsers as $option)
                                                                                    <option value="{{ $option['name'] }}" class="!text-xs"
                                                                                        {{ $value == $option['name'] ? 'selected' : '' }}>
                                                                                        {{ $option['name']}} {{ "(" . $companies[$option['company_id']-1]['name'] . ")"}}
                                                                                    </option>
                                                                                @endforeach
                                                                                @if($value)
                                                                                    @foreach ([$value] as $option)
                                                                                    <option value="{{ $option }}" class="!text-xs"
                                                                                        {{ $value == $option ? 'selected' : $value }}>
                                                                                        {{ $option }}
                                                                                    </option>
                                                                                    @endforeach
                                                                                @endif
                                                                            @else 
                                                                                @foreach (explode(',', $fieldss["options_{$prim}"]) as $option)
                                                                                    <option value="{{ $option }}" class="!text-xs"
                                                                                        {{ $value == $option ? 'selected' : '' }}>
                                                                                        {{ $option }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @endif
                                                                        @endif
                                                                    </select>
                                                                @elseif($valuee === 'date')
                                                                    <div class="editable p-0">
                                                                        {{ $value == null ? '-' : $value }}
                                                                    </div>
                                                                @elseif($valuee == 'textarea')
                                                                    <div id="editable-text-{{ $list_item['id'] }}-{{ $colIndex }}"
                                                                        class="editable p-0 min-w-32 max-w-42 text-wrap"
                                                                        onclick="makeEditable({{ $list_item['id'] }}, '{{ $colIndex }}')">
                                                                        {{ $value == null ? '-' : $value }}
                                                                    </div>

                                                                    <textarea class="text-xs px-2 py-1 max-w-[250px] h-32 resize-none"
                                                                    id="editable-input-{{ $list_item['id'] }}-{{ $colIndex }}"
                                                                    style="display:none;"
                                                                    onblur="saveChanges({{ $list_item['id'] }}, '{{ $colIndex }}')"
                                                                    onkeydown="handleKeyDown(event, {{ $list_item['id'] }}, '{{ $colIndex }}')">{{ $value }}</textarea>
                                                          
                                                                @else
                                                                    <div class="editable p-0">
                                                                        {{ $value == null ? '-' : $value }}
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </td>
                                        @endforeach
                                        {{-- <td class="px-3 py-2 whitespace-nowrap text-xs"><a class="bg-gray-300 rounded py-1 px-2" target="_blank" href="{{$entry_link}}">エントリー登録</a></td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs"><a class="bg-gray-300 rounded py-1 px-2" target="_blank" href="{{$nl_link}}">NextLink登録</a></td> --}}
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

        function saveDateChanges(rowIndex, colIndex) {
            const id = "{{ $id }}"; // Get the product ID
            const inputField = document.getElementById(`editable-date-${rowIndex}-${colIndex}`);
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
                    // Optionally update the UI to reflect the new value
                } else {
                    alert('変更の保存に失敗しました');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('エラーが発生しました');
            });
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

        function saveChangesSelect(rowIndex, colIndex) {
            const id = "{{ $id }}";
            const inputField = document.getElementById(`editable-select-${rowIndex}-${colIndex}`);
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

                    } else {
                        alert('変更の保存に失敗しました');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('エラーが発生しました');
                });
        }

        function removeField(fieldId) {
            if (!confirm('本当に削除してもいいですか？')) {
                return;
            }
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
                        body: JSON.stringify({
                            field_name: fieldName
                        })
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

        function handleKeyDown(event, rowIndex, colIndex) {

            if (event.key === 'Enter') {
                if (event.isComposing || event.keyCode === 229) {
                    return;
                }
                saveChanges(rowIndex, colIndex);
                event.target.blur();
            }
        }
    </script>
    <script src="{{ asset('/js/modals.js') }}"></script>

    <script>
       function downloadCSV() {
        const id = "{{ $id }}"; // Get the product ID
        const urlParams = new URLSearchParams(window.location.search); // 現在のURLのクエリパラメータを取得

        // 新しいURLを作成
        const downloadUrl = `/products/${id}/download-csv?${urlParams.toString()}`;
        window.location.href = downloadUrl; // Redirect to the download URL
        }
    </script>

    <script>
        function confirmDelete() {
            if (confirm('本当に削除してもいいですか？')) {
                const productId = "{{ $id }}"; // Get the product ID
                fetch(`/products/${productId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('削除が成功しました');
                        window.location.href = '/products'; // Reload the page to reflect changes
                    } else {
                        alert('削除に失敗しました');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('エラーが発生しました');
                });
            }
        }
    </script>

    {{-- 架電 --}}
    <script>
        function calledChanges(rowIndex, colIndex) {
            if (!confirm("電話をかけてもいいですか？")) {
                event.preventDefault();
                return;
            }
            const id = "{{ $id }}";
            const user_name = "{{ $user->name }}";
            const phone_ahref = document.getElementById(`editable-phone-${rowIndex }-${colIndex }`);
            highlightThis(phone_ahref);

            const phone_number = phone_ahref.textContent;

            console.log(`'utasnii dugaar:' + ${phone_number}`);

            fetch('/called-change', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        rowIndex: rowIndex,
                        colIndex: colIndex,
                        id: id,
                        user_name: user_name,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('yeeey')
                    } else {
                        alert('変更の保存に失敗しました');
                    }
                   
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('エラーが発生しました');
                });
        }
    </script>

    {{-- highlight --}}
    <script>
        function highlightThis(phone_ahref = null) {
            const thisTr = phone_ahref.closest('tr');
            document.querySelectorAll('tr').forEach(function(tr) {
                tr.classList.remove('highlighted');
            });
            thisTr.classList.add('highlighted');
        }
    </script>

</x-app-layout>
