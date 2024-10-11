<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">代理店追加</h2>
                    <form action="{{ route('companies.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">代理店名:</label>
                            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        </div>
                        <div class="mb-4">
                            <label for="manager_name" class="block text-gray-700">担当者名:</label>
                            <input type="text" name="manager_name" id="manager_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        </div>
                        <div class="mb-4">
                            <label for="manager_phone" class="block text-gray-700">担当者電話番号:</label>
                            <input type="text" name="manager_phone" id="manager_phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <div class="mb-4">
                            <label for="manager_mail" class="block text-gray-700">担当者メール:</label>
                            <input type="email" name="manager_mail" id="manager_mail" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <div class="mb-4">
                            <label for="address" class="block text-gray-700">住所:</label>
                            <input type="text" name="address" id="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <div class="mb-4">
                            <label for="plan" class="block text-gray-700">プラン:</label>
                            <input type="text" name="plan" id="plan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <div class="mt-6 text-right">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">作成</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>