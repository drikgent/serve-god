@extends('layouts.admin')

@section('content')
    <section class="admin-header">
        <div>
            <span class="eyebrow admin-eyebrow">Manage Admins</span>
        </div>
    </section>

    <div class="admin-grid">
        <section class="admin-panel">
            <div class="section-heading"><h2>Create New Account</h2></div>
            <form method="POST" action="{{ route('admin.admins.store') }}" class="stack-form">
                @csrf
                <input type="text" name="name" placeholder="Full name" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role">
                    <option value="editor">Editor</option>
                    <option value="super_admin">Super Admin</option>
                </select>
                <button type="submit" class="primary-button">Create account</button>
            </form>
        </section>

        <section class="admin-panel">
            <div class="section-heading"><h2>Current admins</h2></div>
            @foreach($admins as $admin)
                <div class="admin-list-row">
                    <div class="admin-row-main">
                        <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="admin-inline-name-form">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required>
                            <button type="submit" class="ghost-button">Save</button>
                        </form>
                        <span>{{ $admin->email }} &bull; {{ str_replace('_', ' ', ucfirst($admin->role)) }}</span>
                    </div>
                    <span>{{ $admin->is_active ? 'Active' : 'Disabled' }}</span>
                </div>
            @endforeach
        </section>
    </div>
@endsection
