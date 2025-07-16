@extends('admin.layouts.app')

@section('title', 'Visualizar Banner')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Visualizar Banner</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.banners.edit', $banner) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                Editar
            </a>
            <a href="{{ route('admin.banners.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações do Banner</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Título</label>
                        <p class="text-gray-900">{{ $banner->title }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ordem</label>
                        <p class="text-gray-900">{{ $banner->order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $banner->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>

                    @if($banner->link)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Link</label>
                            <a href="{{ $banner->link }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                {{ $banner->link }}
                            </a>
                        </div>
                    @endif

                    @if($banner->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                            <p class="text-gray-900">{{ $banner->description }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Criado em</label>
                        <p class="text-gray-900">{{ $banner->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Atualizado em</label>
                        <p class="text-gray-900">{{ $banner->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Imagem</h3>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-auto">
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <form action="{{ route('admin.banners.toggle_status', $banner) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 {{ $banner->is_active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-md transition-colors">
                    {{ $banner->is_active ? 'Desativar' : 'Ativar' }}
                </button>
            </form>
            
            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja deletar este banner?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                    Deletar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 