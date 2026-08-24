@extends('layouts.admin')

@section('title', 'Admins - BUGGXIT Admin')
@section('page-title', 'Admins')
@section('page-description', 'Invite and manage admin accounts')

@section('content')
    <div class="space-y-6">
        {{-- Invite New Admin --}}
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
            <h2 class="text-lg font-semibold text-bone mb-1 flex items-center">
                <i class="fas fa-paper-plane text-gold mr-2"></i> Invite New Admin
            </h2>
            <p class="text-sm text-bone-dim mb-6">
                Enter an email address to send an invitation. They'll receive a link to create their own admin
                account with full admin permissions.
            </p>

            <form method="POST" action="{{ route('admin.admins.invite') }}" class="flex flex-col sm:flex-row gap-4">
                @csrf
                <div class="flex-1">
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="colleague@example.com"
                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone focus:outline-none focus:border-gold">
                    @error('email')
                        <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300 whitespace-nowrap">
                    Send Invitation
                </button>
            </form>
        </div>

        {{-- Pending Invitations --}}
        @if ($invitations->isNotEmpty())
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl overflow-hidden">
                <h2 class="text-lg font-semibold text-bone p-6 pb-4 flex items-center">
                    <i class="fas fa-hourglass-half text-gold mr-2"></i> Pending Invitations
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-line/50">
                        <thead class="bg-ink-raised2/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-bone-dim uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-bone-dim uppercase">Invited By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-bone-dim uppercase">Sent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-bone-dim uppercase">Expires</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-bone-dim uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line/50">
                            @foreach ($invitations as $invitation)
                                <tr class="hover:bg-ink-raised2/30">
                                    <td class="px-6 py-4 text-sm text-bone">{{ $invitation->email }}</td>
                                    <td class="px-6 py-4 text-sm text-bone-dim">{{ $invitation->invitedBy->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-bone-dim">{{ $invitation->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($invitation->isExpired())
                                            <span class="text-bad">Expired</span>
                                        @else
                                            <span class="text-bone-dim">{{ $invitation->expires_at->format('d M Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.admins.invitations.revoke', $invitation) }}"
                                            onsubmit="return confirm('Revoke this invitation?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-bad hover:text-bad/80 transition-colors text-sm">
                                                <i class="fas fa-xmark mr-1"></i> Revoke
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Current Admins --}}
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl overflow-hidden">
            <h2 class="text-lg font-semibold text-bone p-6 pb-4 flex items-center">
                <i class="fas fa-user-shield text-gold mr-2"></i> Current Admins
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-line/50">
                    <thead class="bg-ink-raised2/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-bone-dim uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-bone-dim uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-bone-dim uppercase">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line/50">
                        @foreach ($admins as $admin)
                            <tr class="hover:bg-ink-raised2/30">
                                <td class="px-6 py-4 text-sm text-bone">{{ $admin->name }}</td>
                                <td class="px-6 py-4 text-sm text-bone-dim">{{ $admin->email }}</td>
                                <td class="px-6 py-4 text-sm text-bone-dim">{{ $admin->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
