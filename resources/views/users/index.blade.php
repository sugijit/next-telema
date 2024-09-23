<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ユーザー一覧') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 flex justify-between">
                        <div><i class="text-3xl fa-solid fa-users text-gray-400 ml-2"></i></i><span class="text-xl ml-4 text-gray-400">ユーザー一覧</span></div>
                        <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            ユーザー追加
                        </a>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">会社名</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">名前</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">メール</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">役割</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm">{{ $user->company->name }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm">{{ $user->user_cd }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm">{{ $user->name }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm">{{ $user->email }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-xs">{{ ($user->role === 'admin') ? '管理者' : '一般ユーザー' }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-xs font-medium">
                                        <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">変更</a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('本当に削除してもいいですか?')">削除</button>
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