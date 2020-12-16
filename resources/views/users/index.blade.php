<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('users.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">+ Tambah User</a>
            </div>
            <div class="bg-white">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">No</th>
                            <th class="border px-6 py-4">Name</th>
                            <th class="border px-6 py-4">Email</th>
                            <th class="border px-6 py-4">Roles</th>
                            <th class="border px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @forelse ($users as $item)
                        <tr>
                            <td class="border px-6 py-4">{{ $i }}</td>
                            <td class="border px-6 py-4">{{ $item->name }}</td>
                            <td class="border px-6 py-4">{{ $item->email }}</td>
                            <td class="border px-6 py-4">{{ $item->roles }}</td>
                            <td class="border px-6 py-4 text-center">
                                <a href="{{ route('users.edit',$item->id) }}" class="bg-blue-400 hover:bg-blue-700 text-white font-bold py-1 px-4 inline-block rounded">Edit</a>
                                <form action="{{ route('users.destroy',$item->id) }}" class="bg-red-400 hover:bg-red-700 text-white font-bold inline-block py-1 px-4 rounded">Hapus</form>
                            </td>
                        </tr>
                        <?php $i++ ?>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center font-bold">Data Tidak Ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-5">
                {{ $users->links() }}
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
</x-app-layout>
