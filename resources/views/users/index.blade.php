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
                        @if($user->role == 'nl_admin')
                            <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ユーザー追加
                            </a>
                        @endif
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">代理店名</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">名前</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">メール</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">役割</th>
                                @if($user->role == 'nl_admin')
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">操作</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $userr)
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm">{{ $userr->company->name }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm">{{ $userr->user_cd }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm">{{ $userr->name }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm">{{ $userr->email }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-xs">{{ ($userr->role === 'admin') ? '管理者' : '一般ユーザー' }}</td>
                                    @if($user->role == 'nl_admin')
                                        <td class="px-6 py-2 whitespace-nowrap text-xs font-medium">
                                            <a href="{{ route('users.edit', $userr) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">変更</a>
                                            <form action="{{ route('users.destroy', $userr) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('本当に削除してもいいですか?')">削除</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>