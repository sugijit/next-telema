<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 flex justify-between">
                        <div><i class="text-3xl fa-solid fa-building text-gray-400 ml-2"></i><span class="text-xl ml-4 text-gray-400">代理店一覧</span></div>
                        <a href="{{ route('companies.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            代理店追加
                        </a>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">代理店名</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">担当者</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">担当者電話番号</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">住所</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($companies as $company)
                                <tr>
                                    <td> <a href="{{ route('companies.show', $company) }}" class="px-6 py-3 text-sm whitespace-nowrap">{{ $company->name }} </a></td>

                                    <td class="px-6 py-3 whitespace-nowrap text-xs">{{ $company->manager_name }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-xs">{{ $company->manager_phone }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-xs">{{ $company->address }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-xs font-medium">
                                        <a href="{{ route('companies.edit', $company) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">編集</a>
                                        <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">削除</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>