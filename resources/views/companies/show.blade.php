<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">企業詳細</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">企業名</th>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $company->name }}</td>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">担当者名</th>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $company->manager_name }}</td>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">担当者電話番号</th>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $company->manager_phone }}</td>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">担当者メール</th>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $company->manager_mail }}</td>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">住所</th>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $company->address }}</td>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">プラン</th>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $company->plan }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-4 text-right">
                        <a href="{{ route('companies.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            企業一覧に戻る
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>