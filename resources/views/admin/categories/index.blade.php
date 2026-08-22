@extends('layouts.admin')

@section('title', 'Manage Categories - BUGGXIT Admin')
@section('page-title', 'Categories')
@section('page-description', 'Manage your dress categories')

@section('header-actions')
    <div class="mt-4 md:mt-0">
        <a href="{{ route('admin.categories.create') }}"
            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300 text-sm shadow-lg shadow-gold/20">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4" />
            </svg>
            Add New Category
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line/50">
                <thead class="bg-ink-raised2/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase tracking-wider">Dresses</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-bone-dim uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line/50">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-ink-raised2/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-bone">{{ $category->name }}</div>
                                @if ($category->description)
                                    <div class="text-xs text-bone-faint">{{ Str::limit($category->description, 40) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-bone-dim">{{ $category->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-bone-dim">{{ $category->dresses_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $category->is_active ? 'bg-good/10 text-good border border-good/30' : 'bg-bone-faint/10 text-bone-dim border border-bone-faint/30' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="p-2 text-bone-dim hover:text-gold transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this category? It will be removed from all dresses.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-bone-dim hover:text-bad transition-colors"
                                            title="Delete" {{ $category->dresses_count > 0 ? 'disabled' : '' }}>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-bone-faint" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <p class="mt-4 text-bone-dim text-lg">No categories found</p>
                                <p class="text-bone-faint text-sm mt-1">Create your first category</p>
                                <a href="{{ route('admin.categories.create') }}"
                                    class="mt-6 inline-flex items-center px-4 py-2 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Category
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection